<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Illuminate\Support\Facades\Cache;
use Modules\Client\CQRS\Command\Client\VerifyEmailCommand;
use Modules\Client\Eloquent\Resolvers\Client\VerifyEmailEloquentResolver;
use Modules\Client\ValuesObjects\VerifyEmailResult;

final class VerifyEmailHandler
{
    public function __construct(
        private readonly VerifyEmailEloquentResolver $resolver,
    ) {}

    public function handle(VerifyEmailCommand $command): VerifyEmailResult
    {
        $client = $this->resolver->findById($command->clientId);

        if ($client === null || $client->email_verified_at !== null) {
            return VerifyEmailResult::alreadyVerified();
        }

        $cached = Cache::get("email_verify_{$client->id}");

        if ($cached !== $command->code) {
            return VerifyEmailResult::invalidCode();
        }

        Cache::forget("email_verify_{$client->id}");

        $this->resolver->markAsVerified($client);

        return VerifyEmailResult::success();
    }
}
