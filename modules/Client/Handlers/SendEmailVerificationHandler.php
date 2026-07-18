<?php

declare(strict_types=1);

namespace Modules\Client\Handlers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Modules\Client\Mail\SendEmailVerificationCode;
use Modules\Client\Models\ClientModel;
use Modules\Client\Repositories\SendEmailVerificationEloquentResolver;
use Modules\Client\ValuesObjects\SendEmailVerificationResult;

final class SendEmailVerificationHandler
{
    public function __construct(
        private readonly SendEmailVerificationEloquentResolver $resolver,
    ) {}

    public function handle(ClientModel $client): SendEmailVerificationResult
    {
        if ($client->email_verified_at !== null) {
            return SendEmailVerificationResult::alreadyVerified();
        }

        $code = (string) random_int(100000, 999999);

        Cache::put("email_verify_{$client->id}", $code, now()->addMinutes(10));

        Mail::to($client->email)->send(new SendEmailVerificationCode($code));

        return SendEmailVerificationResult::success();
    }
}
