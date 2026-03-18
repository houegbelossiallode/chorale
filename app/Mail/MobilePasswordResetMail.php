<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MobilePasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $newPassword;

    public function __construct(string $newPassword)
    {
        $this->newPassword = $newPassword;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "Votre nouveau mot de passe — Chorale Saint Oscar Romero",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: "emails.mobile_password_reset",
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
