<?php

declare(strict_types=1);

namespace Modules\Client\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\Attributes\Backoff;
use Illuminate\Queue\Attributes\Timeout;
use Illuminate\Queue\Attributes\Tries;
use Modules\Client\Handlers\Client\SendEmailVerificationHandler;
use Modules\Client\Models\ClientModel;

#[Tries(3)]
#[Timeout(120)]
#[Backoff(10, 30, 60)]
class SendEmailVerificationCodeJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public function __construct(
        private readonly string $clientId,
    ) {}

    public function handle(
        SendEmailVerificationHandler $handler,
    ): void {
        $client = ClientModel::find($this->clientId);

        if ($client === null || $client->email_verified_at !== null) {
            return;
        }

        $handler->handle($client);
    }
}
