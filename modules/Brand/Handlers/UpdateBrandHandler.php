<?php

declare(strict_types=1);

namespace Modules\Brand\Handlers;

use Modules\Brand\CQRS\Command\UpdateBrandCommand;
use Modules\Brand\Eloquent\Resolvers\UpdateBrandEloquentResolver;
use Modules\Brand\Models\BrandModel;

final class UpdateBrandHandler
{
    public function __construct(
        private readonly UpdateBrandEloquentResolver $resolver,
    ) {}

    public function handle(UpdateBrandCommand $command): BrandModel
    {
        return $this->resolver->resolve($command);
    }
}
