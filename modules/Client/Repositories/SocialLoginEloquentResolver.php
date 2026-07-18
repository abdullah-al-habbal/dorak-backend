<?php

declare(strict_types=1);

namespace Modules\Client\Repositories;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\Client\Models\ClientModel;
use Modules\Client\Models\SocialAccountModel;

final class SocialLoginEloquentResolver
{
    public function findSocialAccount(string $provider, string $providerId): ?SocialAccountModel
    {
        return SocialAccountModel::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();
    }

    public function findClientByEmail(string $email): ?ClientModel
    {
        return ClientModel::where('email', $email)->first();
    }

    public function createClient(array $data): ClientModel
    {
        return ClientModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make(Str::random(32)),
        ]);
    }

    public function createSocialAccount(
        ClientModel $client,
        string $provider,
        string $providerId,
        ?string $avatar,
    ): SocialAccountModel {
        return SocialAccountModel::create([
            'client_id' => $client->id,
            'provider' => $provider,
            'provider_id' => $providerId,
            'avatar_url' => $avatar,
        ]);
    }
}
