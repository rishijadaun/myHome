<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

class PasswordResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $userName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $otp, ?string $userName = null)
    {
        $this->otp = $otp;
        $this->userName = $userName ?: 'Valued User';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $fromAddress = config('mail.from.address', 'imrishikrishna@gmail.com');
        $fromName = config('mail.from.name', 'StayNest');

        return new Envelope(
            from: new Address($fromAddress, $fromName),
            replyTo: [
                new Address($fromAddress, $fromName),
            ],
            subject: "{$this->otp} is your StayNest password reset code",
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.password_reset_otp',
            text: 'emails.password_reset_otp_plain',
            with: [
                'otp'      => $this->otp,
                'userName' => $this->userName,
            ],
        );
    }

    /**
     * Get the message headers.
     */
    public function headers(): Headers
    {
        return new Headers(
            text: [
                'X-Auto-Response-Suppress' => 'All',
                'X-Priority'               => '1',
                'X-Mailer'                 => 'StayNest Mailer',
                'Precedence'               => 'bulk',
                'Auto-Submitted'           => 'auto-generated',
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
