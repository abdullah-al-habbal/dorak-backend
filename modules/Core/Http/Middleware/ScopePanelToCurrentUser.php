<?php

// modules/Core/Http/Middleware/ScopePanelToCurrentUser.php
declare(strict_types=1);

namespace Modules\Core\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Barber\Models\BarberModel;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Booking\Models\BookingModel;
use Modules\Branch\Models\BranchModel;
use Modules\Chair\Models\ChairModel;
use Modules\JobPosting\Models\ApplicationModel;
use Modules\JobPosting\Models\JobPostingModel;
use Modules\OfferedService\Models\OfferedServiceModel;
use Modules\Review\Models\ReviewModel;

final class ScopePanelToCurrentUser
{
    public function handle(Request $request, Closure $next): mixed
    {
        $panelId = filament()->getCurrentPanel()?->getId();

        match ($panelId) {
            'barber' => $this->scopeToBarber($request),
            'branch' => $this->scopeToBranch($request),
            default => null,
        };

        return $next($request);
    }

    private function scopeToBarber(Request $request): void
    {
        $barber = $request->user('barber_dashboard');

        if (! $barber) {
            return;
        }

        $barberId = $barber->getKey();

        BarberModel::addGlobalScope('panel-scope', fn ($q) => $q->whereKey($barberId));

        BookingModel::addGlobalScope('panel-scope', fn ($q) => $q->where('barber_id', $barberId));

        OfferedServiceModel::addGlobalScope(
            'panel-scope',
            fn ($q) => $q
                ->where('serviceable_id', $barberId)
                ->where('serviceable_type', 'barber'),
        );

        ReviewModel::addGlobalScope(
            'panel-scope',
            fn ($q) => $q->whereHas(
                'booking',
                fn ($q) => $q->where('barber_id', $barberId),
            ),
        );

        BarberAffiliationModel::addGlobalScope('panel-scope', fn ($q) => $q->where('barber_id', $barberId));

        ApplicationModel::addGlobalScope('panel-scope', fn ($q) => $q->where('barber_id', $barberId));
    }

    private function scopeToBranch(Request $request): void
    {
        $branch = $request->user('branch');

        if (! $branch) {
            return;
        }

        $branchId = $branch->getKey();

        BranchModel::addGlobalScope('panel-scope', fn ($q) => $q->whereKey($branchId));

        ChairModel::addGlobalScope('panel-scope', fn ($q) => $q->where('branch_id', $branchId));

        OfferedServiceModel::addGlobalScope(
            'panel-scope',
            fn ($q) => $q
                ->where('serviceable_id', $branchId)
                ->where('serviceable_type', 'branch'),
        );

        BookingModel::addGlobalScope(
            'panel-scope',
            fn ($q) => $q->whereHas(
                'chair',
                fn ($q) => $q->where('branch_id', $branchId),
            ),
        );

        JobPostingModel::addGlobalScope('panel-scope', fn ($q) => $q->where('branch_id', $branchId));

        ReviewModel::addGlobalScope(
            'panel-scope',
            fn ($q) => $q->whereHas(
                'booking.chair',
                fn ($q) => $q->where('branch_id', $branchId),
            ),
        );

        BarberAffiliationModel::addGlobalScope('panel-scope', fn ($q) => $q
            ->where('affiliable_id', $branchId)
            ->where('affiliable_type', 'branch'));

        ApplicationModel::addGlobalScope('panel-scope', fn ($q) => $q->whereHas('jobPosting', fn ($q) => $q->where('branch_id', $branchId)));
    }
}
