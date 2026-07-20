<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Laravel\Socialite\Facades\Socialite;
use Modules\Client\CQRS\Command\Client\SocialLoginCommand;
use Modules\Client\Eloquent\Resolvers\Client\SocialLoginEloquentResolver;
use Modules\Client\ValuesObjects\SocialLoginResult;

final class SocialLoginHandler
{
    public function __construct(
        private readonly SocialLoginEloquentResolver $resolver,
    ) {}

    public function handle(SocialLoginCommand $command): SocialLoginResult
    {
        try {
            $socialUser = Socialite::driver($command->provider)->stateless()->userFromToken($command->accessToken);
        } catch (\Exception) {
            return SocialLoginResult::invalidToken();
        }

        $providerId = $socialUser->getId();
        $email = $socialUser->getEmail();
        $name = $socialUser->getName();
        $avatar = $socialUser->getAvatar();

        $socialAccount = $this->resolver->findSocialAccount($command->provider, $providerId);

        if ($socialAccount !== null) {
            $client = $socialAccount->client;
            $isNew = false;
        } else {
            $client = $this->resolver->findClientByEmail($email);

            if ($client === null) {
                $client = $this->resolver->createClient($name, $email);
            }

            $this->resolver->createSocialAccount($client, $command->provider, $providerId, $avatar);
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
