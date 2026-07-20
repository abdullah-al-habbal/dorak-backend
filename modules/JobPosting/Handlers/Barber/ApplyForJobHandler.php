<?php

declare(strict_types=1);

namespace Modules\JobPosting\Handlers\Barber;

use Modules\JobPosting\CQRS\Command\Barber\ApplyForJobCommand;
use Modules\JobPosting\Eloquent\Resolvers\Barber\ApplyForJobEloquentResolver;
use Modules\JobPosting\ValuesObjects\ApplyForJobResult;

final class ApplyForJobHandler
{
    public function __construct(
        private readonly ApplyForJobEloquentResolver $resolver,
    ) {}

    public function handle(ApplyForJobCommand $command): ApplyForJobResult
    {
        return $this->resolver->resolve($command);
    }
}
