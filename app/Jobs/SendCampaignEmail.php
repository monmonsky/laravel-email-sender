<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\View;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mime\Email;
use App\Mail\CampaignEmail;
use App\Models\Campaign;
use App\Models\Contact;
use App\Models\CampaignLog;
use App\Models\Sender;

class SendCampaignEmail implements ShouldQueue
{
    use Queueable;

    public $campaign;
    public $contact;

    /**
     * Create a new job instance.
     */
    public function __construct(Campaign $campaign, Contact $contact)
    {
        $this->campaign = $campaign;
        $this->contact = $contact;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Get sender from campaign or fallback to default
            $sender = $this->campaign->sender_id
                ? Sender::find($this->campaign->sender_id)
                : Sender::where('is_default', true)->first();

            // Replace variables in subject and content
            $subject = $this->replaceVariables($this->campaign->subject, $this->contact);
            $content = $this->replaceVariables($this->campaign->content, $this->contact);

            // Get sender info
            $senderEmail = $sender ? $sender->from_email : config('mail.from.address');
            $senderName = $sender ? $sender->from_name : config('mail.from.name');

            // Render email HTML
            $html = View::make('emails.campaign', [
                'subject' => $subject,
                'content' => $content,
                'senderName' => $senderName,
                'unsubscribeUrl' => '#',
                'preferencesUrl' => '#',
            ])->render();

            // Send using Symfony Mailer directly
            if ($sender) {
                // Build SMTP DSN
                $encryption = $sender->smtp_encryption === 'ssl' ? 'ssl' : ($sender->smtp_encryption === 'tls' ? 'tls' : null);
                $dsn = $this->buildSmtpDsn(
                    $sender->smtp_host,
                    $sender->smtp_port,
                    $sender->smtp_username,
                    $sender->smtp_password,
                    $encryption
                );

                // Create transport and mailer
                $transport = Transport::fromDsn($dsn);
                $mailer = new Mailer($transport);

                // Build email
                $email = (new Email())
                    ->from($senderEmail)
                    ->to($this->contact->email)
                    ->subject($subject)
                    ->html($html);

                // Send
                $mailer->send($email);
            } else {
                // Fallback to Laravel mail
                Mail::to($this->contact->email)->send(
                    new CampaignEmail($subject, $content, $senderEmail, $senderName)
                );
            }

            // Log successful send
            CampaignLog::create([
                'campaign_id' => $this->campaign->id,
                'contact_id' => $this->contact->id,
                'status' => 'sent',
                'sent_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Log failed send
            CampaignLog::create([
                'campaign_id' => $this->campaign->id,
                'contact_id' => $this->contact->id,
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Build SMTP DSN string for Symfony Mailer
     */
    private function buildSmtpDsn($host, $port, $username, $password, $encryption)
    {
        $dsn = 'smtp://';

        if ($username && $password) {
            $dsn .= urlencode($username) . ':' . urlencode($password) . '@';
        }

        $dsn .= $host . ':' . $port;

        if ($encryption) {
            $dsn .= '?encryption=' . $encryption;
        }

        return $dsn;
    }

    /**
     * Replace variables in the text with contact data
     */
    private function replaceVariables($text, $contact)
    {
        $variables = [
            '{{name}}' => $contact->name,
            '{{email}}' => $contact->email,
            '{{phone}}' => $contact->phone ?? '',
        ];

        return str_replace(array_keys($variables), array_values($variables), $text);
    }
}
