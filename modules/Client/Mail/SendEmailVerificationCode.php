<?php

declare(strict_types=1);

namespace Modules\Client\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

final class SendEmailVerificationCode extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public readonly string $code,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Verify your email address',
        );
    }

    public function content(): Content
    {
        return new Content(
            htmlString: "<p>Your email verification code: <strong>{$this->code}</strong></p><p>This code expires in 10 minutes.</p>",
        );
    }
}
