<?php

declare(strict_types=1);

namespace Modules\Explore\Eloquent\Resolvers\Shared;

use Modules\Barber\Http\Resources\BarberResource;
use Modules\Barber\Models\BarberModel;
use Modules\Explore\CQRS\Query\Shared\GetBarberDetailQuery;
use Modules\OfferedService\Http\Resources\Shared\ServiceResource;

final class GetBarberDetailEloquentResolver
{
    public function resolve(GetBarberDetailQuery $query): array
    {
        $barber = BarberModel::with('services')->findOrFail($query->barberId);

        return array_merge(
            (new BarberResource($barber))->toArray(request()),
            [
                'services' => ServiceResource::collection($barber->services),
            ],
        );
    }
}
