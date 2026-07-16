<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Modules\Client\Models\ClientModel;
use Modules\Client\Models\SocialAccountModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class SocialLoginAction extends BaseApiAction
{
    public function __invoke(string $provider): JsonResponse
    {
        $data = request()->validate([
            'access_token' => 'required|string',
        ]);

        try {
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($data['access_token']);
        } catch (\Throwable) {
            return $this->unauthorized(
                message: $this->trans('core::messages.invalid_social_token'),
            );
        }

        $providerId = $socialUser->getId();
        $email = $socialUser->getEmail();
        $name = $socialUser->getName() ?? $email ?? $providerId;
        $avatar = $socialUser->getAvatar();

        $socialAccount = SocialAccountModel::where('provider', $provider)
            ->where('provider_id', $providerId)
            ->first();

        if ($socialAccount) {
            $client = $socialAccount->client;
        } else {
            $email ??= "{$provider}_{$providerId}@social.local";
            $name ??= $email;

            $client = ClientModel::where('email', $email)->first();

            if (! $client) {
                $client = ClientModel::create([
                    'name' => $name,
                    'email' => $email,
                    'password' => Hash::make(Str::random(32)),
                ]);
            }

            $client->socialAccounts()->create([
                'provider' => $provider,
                'provider_id' => $providerId,
                'avatar_url' => $avatar,
            ]);
        }

        $token = $client->createToken('client-app')->plainTextToken;

        return $this->success(data: [
            'token' => $token,
            'client' => [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
                'preferred_universe' => $client->preferred_universe,
            ],
        ]);
    }
}
