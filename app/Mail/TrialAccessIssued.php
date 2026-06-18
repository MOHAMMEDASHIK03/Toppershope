<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Admission\TrialAccess;

class TrialAccessIssued extends Mailable
{
    use Queueable, SerializesModels;

    public $trial;
    public $rawPassword;

    /**
     * Create a new message instance.
     */
    public function __construct(TrialAccess $trial, string $rawPassword = null)
    {
        $this->trial = $trial;
        // Fallback to plain_password column if raw is not passed (for old testing code)
        $this->rawPassword = $rawPassword ?? $trial->plain_password;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Exclusive Trial Access — Topper\'s Hope',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admission.trial_issued',
            with: [
                'trial' => $this->trial,
                'rawPassword' => $this->rawPassword,
            ],
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
