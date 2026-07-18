<?php

declare(strict_types=1);

namespace Modules\Client\Handlers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Modules\Client\Mail\SendPasswordResetCode;
use Modules\Client\Repositories\ForgotPasswordEloquentResolver;
use Modules\Client\ValuesObjects\ForgotPasswordResult;

final class ForgotPasswordHandler
{
    public function __construct(
        private readonly ForgotPasswordEloquentResolver $resolver,
    ) {}

    public function handle(string $email): ForgotPasswordResult
    {
        $client = $this->resolver->findByEmail($email);

        $code = (string) random_int(100000, 999999);

        Cache::put("password_reset_{$client->id}", $code, now()->addMinutes(10));

        Mail::to($client->email)->send(new SendPasswordResetCode($code));

        return ForgotPasswordResult::success();
    }
}
