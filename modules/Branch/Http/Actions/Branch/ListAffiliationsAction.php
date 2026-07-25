<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Branch;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Core\Enums\SuccessCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListAffiliationsAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $branch = $request->user('branch_api');

        $affiliations = BarberAffiliationModel::where('affiliable_id', $branch->id)
            ->where('affiliable_type', 'branch')
            ->with('barber')
            ->get();

        return $this->success(
            data: $affiliations,
            code: SuccessCodeEnum::SUCCESS,
        );
    }
}
