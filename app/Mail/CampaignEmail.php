<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class CampaignEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $subject;
    public $emailContent;
    public $senderEmail;
    public $senderName;

    /**
     * Create a new message instance.
     */
    public function __construct($subject, $emailContent, $senderEmail = null, $senderName = null)
    {
        $this->subject = $subject;
        $this->emailContent = $emailContent;
        $this->senderEmail = $senderEmail;
        $this->senderName = $senderName;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $envelope = new Envelope(
            subject: $this->subject,
        );

        // Set custom sender if provided
        if ($this->senderEmail && $this->senderName) {
            $envelope->from(new Address($this->senderEmail, $this->senderName));
        } elseif ($this->senderEmail) {
            $envelope->from(new Address($this->senderEmail));
        }

        return $envelope;
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            htmlString: $this->emailContent,
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
