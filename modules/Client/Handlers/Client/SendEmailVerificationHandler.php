<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
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

        // DEVELOPMENT ONLY. Lets local testing read the code without opening
        // Mailtrap. Gated on the `local` environment so staging and production
        // never reach it, and never a substitute for real delivery testing.
        if (app()->environment('local')) {
            Log::debug('Development OTP generated', [
                'type' => 'email_verification',
                'client_id' => $client->id,
                'otp' => $code,
            ]);
        }

        Cache::put("email_verify_{$client->id}", $code, now()->addMinutes(10));
        Cache::forget("email_verify_attempts_{$client->id}");

        Mail::to($client->email)->send(new SendEmailVerificationCode($code));

        return SendEmailVerificationResult::success();
    }
}
