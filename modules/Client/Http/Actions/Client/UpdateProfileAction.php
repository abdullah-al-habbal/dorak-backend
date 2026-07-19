<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Modules\Client\Http\Requests\Client\UpdateProfileRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateProfileAction extends BaseApiAction
{
    // todo: we need resolver, hanlder and make the Command/Query based on the api
    public function __invoke(UpdateProfileRequest $request): JsonResponse
    {
        $client = $request->user();
        $data = $request->validated();

        $update = array_filter([
            'name' => $data['name'] ?? null,
            'email' => $data['email'] ?? null,
            'password' => isset($data['password']) ? Hash::make($data['password']) : null,
            'phone' => $data['phone'] ?? null,
        ]);

        if ($update !== []) {
            $client->update($update);
        }

        return $this->ok(data: [
            'id' => $client->id,
            'name' => $client->name,
            'email' => $client->email,
            'phone' => $client->phone,
            'preferred_universe' => $client->preferred_universe,
        ]);
    }
}
