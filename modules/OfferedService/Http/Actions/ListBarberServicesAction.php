<?php

declare(strict_types=1);

namespace Modules\OfferedService\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Barber\Models\BarberModel;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\OfferedService\Http\Resources\ServiceResource;

final class ListBarberServicesAction extends BaseApiAction
{
    public function __invoke(string $barber): JsonResponse
    {
        $barber = BarberModel::with('services.currency')->findOrFail($barber);

        return $this->ok(
            data: ServiceResource::collection($barber->services),
        );
    }
}
