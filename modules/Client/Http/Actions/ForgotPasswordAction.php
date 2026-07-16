<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Modules\Client\Mail\SendPasswordResetCode;
use Modules\Client\Models\ClientModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ForgotPasswordAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $data = request()->validate([
            'email' => 'required|email|exists:clients,email',
        ]);

        $client = ClientModel::where('email', $data['email'])->first();

        $code = (string) random_int(100000, 999999);

        Cache::put("password_reset_{$client->id}", $code, now()->addMinutes(10));

        Mail::to($client->email)->send(new SendPasswordResetCode($code));

        return $this->success(message: $this->trans('core::messages.reset_code_sent'));
    }
}
