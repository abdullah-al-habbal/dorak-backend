<?php

declare(strict_types=1);

namespace Modules\JobPosting\Handlers\Barber;

use Modules\JobPosting\CQRS\Command\Barber\UpdateApplicationStatusCommand;
use Modules\JobPosting\Eloquent\Resolvers\Barber\UpdateApplicationStatusEloquentResolver;
use Modules\JobPosting\Models\ApplicationModel;

final class UpdateApplicationStatusHandler
{
    public function __construct(
        private readonly UpdateApplicationStatusEloquentResolver $resolver,
    ) {}

    public function handle(UpdateApplicationStatusCommand $command): ApplicationModel
    {
        return $this->resolver->resolve($command);
    }
}
