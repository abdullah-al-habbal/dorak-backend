<?php

declare(strict_types=1);

namespace Modules\Client\Handlers;

use Laravel\Socialite\Facades\Socialite;
use Modules\Client\Repositories\SocialLoginEloquentResolver;
use Modules\Client\ValuesObjects\SocialLoginResult;

final class SocialLoginHandler
{
    public function __construct(
        private readonly SocialLoginEloquentResolver $resolver,
    ) {}

    public function handle(string $provider, string $accessToken): SocialLoginResult
    {
        try {
            $socialUser = Socialite::driver($provider)->stateless()->userFromToken($accessToken);
        } catch (\Exception) {
            return SocialLoginResult::invalidToken();
        }

        $providerId = $socialUser->getId();
        $email = $socialUser->getEmail();
        $name = $socialUser->getName();
        $avatar = $socialUser->getAvatar();

        $socialAccount = $this->resolver->findSocialAccount($provider, $providerId);

        if ($socialAccount !== null) {
            $client = $socialAccount->client;
            $isNew = false;
        } else {
            $client = $this->resolver->findClientByEmail($email);

            if ($client === null) {
                $client = $this->resolver->createClient([
                    'name' => $name,
                    'email' => $email,
                ]);
            }

            $this->resolver->createSocialAccount($client, $provider, $providerId, $avatar);
            $isNew = true;
        }

        $token = $client->createToken('client-app')->plainTextToken;

        return SocialLoginResult::success(
            token: $token,
            clientData: [
                'id' => $client->id,
                'name' => $client->name,
                'email' => $client->email,
                'phone' => $client->phone,
            ],
            isNew: $isNew,
        );
    }
}
