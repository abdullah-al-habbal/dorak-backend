<?php

declare(strict_types=1);

namespace Modules\Explore\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Barber\Http\Resources\BarberResource;
use Modules\Branch\Http\Resources\BranchResource;
use Modules\Branch\Models\BranchModel;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\OfferedService\Http\Resources\ServiceResource;

final class GetBranchDetailAction extends BaseApiAction
{
    public function __invoke(string $branch): JsonResponse
    {
        $branch = BranchModel::with(['chairs.barber.services'])->findOrFail($branch);

        $barbers = $branch->chairs
            ->pluck('barber')
            ->filter()
            ->unique('id')
            ->values();

        $services = $barbers->flatMap->services->unique('id')->values();

        $data = array_merge(
            (new BranchResource($branch))->toArray(request()),
            [
                'chairs_count' => $branch->chairs->count(),
                'barbers' => BarberResource::collection($barbers),
                'services' => ServiceResource::collection($services),
            ],
        );

        return $this->ok(data: $data);
    }
}
