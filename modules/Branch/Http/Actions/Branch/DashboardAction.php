<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Branch;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Branch\Http\Resources\Shared\BranchResource;
use Modules\Chair\Models\ChairModel;
use Modules\Core\Enums\SuccessCodeEnum;
use Modules\Core\Http\Actions\BaseApiAction;

final class DashboardAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $branch = $request->user('branch_api');
        $today = now()->startOfDay();

        $stats = [
            'today_bookings' => \Modules\Booking\Models\BookingModel::whereHas('chair', fn ($q) => $q->where('branch_id', $branch->id))
                ->where('time_slot', '>=', $today)->count(),
            'active_chairs' => ChairModel::where('branch_id', $branch->id)->where('status', \Modules\Chair\Enums\ChairStatus::Available)->count(),
            'total_chairs' => ChairModel::where('branch_id', $branch->id)->count(),
            'pending_affiliations' => BarberAffiliationModel::where('affiliable_id', $branch->id)
                ->where('affiliable_type', 'branch')->where('status', 'pending')->count(),
        ];

        return $this->success($stats, SuccessCodeEnum::SUCCESS);
    }
}
