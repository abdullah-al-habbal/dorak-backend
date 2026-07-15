<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\BarberAffiliation\Http\Resources\BarberAffiliationResource;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListBarberAffiliationsAction extends BaseApiAction
{
    public function __invoke(string $barber): JsonResponse
    {
        $affiliations = BarberAffiliationModel::where('barber_id', $barber)
            ->with('affiliable')
            ->get();

        return $this->ok(
            data: BarberAffiliationResource::collection($affiliations),
        );
    }
}
