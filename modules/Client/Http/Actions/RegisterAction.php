<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Modules\Client\Models\ClientModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class RegisterAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $data = request()->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:clients,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
        ]);

        $client = ClientModel::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone' => $data['phone'] ?? null,
        ]);

        $token = $client->createToken('client-app')->plainTextToken;

        return $this->created(data: [
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
