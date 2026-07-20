<?php

declare(strict_types=1);

namespace Modules\JobPosting\Eloquent\Resolvers\Barber;

use Modules\JobPosting\CQRS\Command\Barber\UpdateApplicationStatusCommand;
use Modules\JobPosting\Models\ApplicationModel;

final class UpdateApplicationStatusEloquentResolver
{
    public function resolve(UpdateApplicationStatusCommand $command): ApplicationModel
    {
        $application = ApplicationModel::findOrFail($command->applicationId);
        $application->update(['status' => $command->status]);

        return $application->fresh();
    }
}
