<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
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
            // Get default sender configuration
            $sender = Sender::where('is_default', true)->first();

            // Configure SMTP settings if sender is found
            if ($sender) {
                Config::set('mail.mailers.smtp.host', $sender->smtp_host);
                Config::set('mail.mailers.smtp.port', $sender->smtp_port);
                Config::set('mail.mailers.smtp.username', $sender->smtp_username);
                Config::set('mail.mailers.smtp.password', $sender->smtp_password);
                Config::set('mail.mailers.smtp.encryption', $sender->smtp_encryption);
                Config::set('mail.from.address', $sender->from_email);
                Config::set('mail.from.name', $sender->from_name);

                // Use SMTP mailer
                Config::set('mail.default', 'smtp');
            }

            // Replace variables in subject and content
            $subject = $this->replaceVariables($this->campaign->subject, $this->contact);
            $content = $this->replaceVariables($this->campaign->content, $this->contact);

            // Get sender info
            $senderEmail = $sender ? $sender->from_email : config('mail.from.address');
            $senderName = $sender ? $sender->from_name : config('mail.from.name');

            // Send email
            Mail::to($this->contact->email)->send(
                new CampaignEmail($subject, $content, $senderEmail, $senderName)
            );

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
