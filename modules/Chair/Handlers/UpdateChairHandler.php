<?php

declare(strict_types=1);

namespace Modules\Chair\Handlers;

use Modules\Chair\CQRS\Command\UpdateChairCommand;
use Modules\Chair\Eloquent\Resolvers\UpdateChairEloquentResolver;
use Modules\Chair\Models\ChairModel;

final class UpdateChairHandler
{
    public function __construct(
        private readonly UpdateChairEloquentResolver $resolver,
    ) {}

    public function handle(UpdateChairCommand $command): ChairModel
    {
        return $this->resolver->resolve($command);
    }
}
