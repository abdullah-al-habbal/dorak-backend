<?php

declare(strict_types=1);

namespace Modules\Chair\Observers;

use Modules\Chair\Events\ChairStatusUpdated;
use Modules\Chair\Models\ChairModel;

final class ChairObserver
{
    public function updated(ChairModel $chair): void
    {
        if ($chair->isDirty('status')) {
            ChairStatusUpdated::dispatch(
                chairId: $chair->id,
                branchId: $chair->branch_id,
                status: $chair->status,
            );
        }
    }
}
