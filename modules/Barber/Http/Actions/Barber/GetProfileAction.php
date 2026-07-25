<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Actions\Barber;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Modules\Barber\Http\Resources\BarberResource;
use Modules\Core\Http\Actions\BaseApiAction;

final class GetProfileAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        return $this->ok(data: new BarberResource(Auth::guard('barber')->user()));
    }
}
