<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminPasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public string $otp) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Admin password reset OTP — Topper\'s Hope',
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.admin.password_reset_otp',
            with: [
                'otp' => $this->otp,
                'expiresMinutes' => \App\Services\Admin\AdminPasswordResetService::OTP_TTL_MINUTES,
            ],
        );
    }
}
