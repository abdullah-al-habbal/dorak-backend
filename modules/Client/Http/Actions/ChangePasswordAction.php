<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Modules\Client\Http\Requests\ChangePasswordRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class ChangePasswordAction extends BaseApiAction
{
    public function __invoke(ChangePasswordRequest $request): JsonResponse
    {
        $client = $request->user();

        $client->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        $client->tokens()->delete();

        return $this->success(message: $this->trans('core::messages.password_changed'));
    }
}
