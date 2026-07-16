<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Modules\Client\Models\ClientModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ResetPasswordAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $data = request()->validate([
            'email' => 'required|email|exists:clients,email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8',
        ]);

        $client = ClientModel::where('email', $data['email'])->first();

        $cached = Cache::get("password_reset_{$client->id}");

        if ($cached !== $data['code']) {
            return $this->unprocessable(
                message: $this->trans('core::messages.invalid_reset_code'),
            );
        }

        Cache::forget("password_reset_{$client->id}");

        $client->update([
            'password' => Hash::make($data['password']),
        ]);

        $client->tokens()->delete();

        return $this->success(message: $this->trans('core::messages.password_reset'));
    }
}
