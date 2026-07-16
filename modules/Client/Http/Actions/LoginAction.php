<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Modules\Client\Models\ClientModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class LoginAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $data = request()->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        $client = ClientModel::where('email', $data['email'])->first();

        if (! $client || ! Hash::check($data['password'], $client->password)) {
            return $this->unauthorized(
                message: $this->trans('core::messages.invalid_credentials'),
            );
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
