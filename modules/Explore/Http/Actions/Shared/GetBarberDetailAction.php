<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Barber\Http\Resources\BarberResource;
use Modules\Barber\Models\BarberModel;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\OfferedService\Http\Resources\Shared\ServiceResource;

final class GetBarberDetailAction extends BaseApiAction
{
    public function __invoke(string $barber): JsonResponse
    {
        $barber = BarberModel::with('services')->findOrFail($barber);

        $data = array_merge(
            (new BarberResource($barber))->toArray(request()),
            [
                'services' => ServiceResource::collection($barber->services),
            ],
        );

        return $this->ok(data: $data);
    }
}
