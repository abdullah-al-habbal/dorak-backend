<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Modules\Client\Mail\SendEmailVerificationCode;
use Modules\Core\Http\Actions\BaseApiAction;

final class SendEmailVerificationAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $client = request()->user();

        if ($client->email_verified_at !== null) {
            return $this->success(message: $this->trans('core::messages.email_already_verified'));
        }

        $code = (string) random_int(100000, 999999);

        Cache::put("email_verify_{$client->id}", $code, now()->addMinutes(10));

        Mail::to($client->email)->send(new SendEmailVerificationCode($code));

        return $this->success(message: $this->trans('core::messages.verification_code_sent'));
    }
}
