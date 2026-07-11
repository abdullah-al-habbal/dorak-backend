<?php
declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;

final class RefreshTokenAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $client = request()->user();

        $client->currentAccessToken()->delete();

        $token = $client->createToken('client-app')->plainTextToken;

        return $this->success(data: [
            'token' => $token,
        ]);
    }
}
