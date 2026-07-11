<?php

declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;

final class LogoutAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        request()->user()->currentAccessToken()->delete();

        return $this->noContent();
    }
}
