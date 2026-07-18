<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\SocialLoginHandler;
use Modules\Client\Http\Requests\SocialLoginRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class SocialLoginAction extends BaseApiAction
{
    public function __construct(
        private readonly SocialLoginHandler $handler,
    ) {}

    public function __invoke(string $provider, SocialLoginRequest $request): JsonResponse
    {
        $result = $this->handler->handle(
            provider: $provider,
            accessToken: $request->validated('access_token'),
        );

        if ($result->isInvalidToken()) {
            return $this->unauthorized(message: $this->trans('core::messages.invalid_social_token'));
        }

        return $this->success(data: [
            'token' => $result->token,
            'client' => $result->client,
        ]);
    }
}
