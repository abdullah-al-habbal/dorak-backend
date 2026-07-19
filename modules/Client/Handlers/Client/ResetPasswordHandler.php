<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Modules\Client\Repositories\ResetPasswordEloquentResolver;
use Modules\Client\ValuesObjects\ResetPasswordResult;

final class ResetPasswordHandler
{
    public function __construct(
        private readonly ResetPasswordEloquentResolver $resolver,
    ) {}

    public function handle(string $email, string $code, string $password): ResetPasswordResult
    {
        $client = $this->resolver->findByEmail($email);

        $cachedCode = Cache::get("password_reset_{$client->id}");

        if ($cachedCode !== $code) {
            return ResetPasswordResult::invalidCode();
        }

        Cache::forget("password_reset_{$client->id}");

        $this->resolver->updatePassword($client, Hash::make($password));
        $this->resolver->deleteTokens($client);

        return ResetPasswordResult::success();
    }
}
