<?php
// modules/Activation/Observers/ActivationLogObserver.php
declare(strict_types=1);

namespace Modules\Activation\Observers;

use Modules\Activation\Models\ActivationLogModel;

final class ActivationLogObserver
{
    public function created(ActivationLogModel $log): void
    {
        $this->syncEntityStatus($log);
    }

    public function updated(ActivationLogModel $log): void
    {
        $this->syncEntityStatus($log);
    }

    private function syncEntityStatus(ActivationLogModel $log): void
    {
        if ($entity = $log->activable) {
            if ($entity->isFillable('status')) {
                $entity->status = $log->status;
                $entity->saveQuietly();
            }
        }
    }
}
