<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Modules\Core\Http\Actions\BaseApiAction;

final class VerifyEmailAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $data = request()->validate([
            'code' => 'required|string|size:6',
        ]);

        $client = request()->user();

        if ($client->email_verified_at !== null) {
            return $this->success(message: $this->trans('core::messages.email_already_verified'));
        }

        $cached = Cache::get("email_verify_{$client->id}");

        if ($cached !== $data['code']) {
            return $this->unprocessable(
                message: $this->trans('core::messages.invalid_verification_code'),
            );
        }

        Cache::forget("email_verify_{$client->id}");

        $client->update(['email_verified_at' => now()]);

        return $this->success(message: $this->trans('core::messages.email_verified'));
    }
}
