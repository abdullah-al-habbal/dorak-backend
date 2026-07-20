<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Client\Handlers\Client\UpdateProfileHandler;
use Modules\Client\Http\Requests\Client\UpdateProfileRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateProfileAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateProfileHandler $handler,
    ) {}

    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        $client = $this->handler->handle($request->toCommand());

        return $this->ok(data: [
            'id' => $client->id,
            'name' => $client->name,
            'email' => $client->email,
            'phone' => $client->phone,
            'preferred_universe' => $client->preferred_universe,
        ]);
    }
}
