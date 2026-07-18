<?php

declare(strict_types=1);

namespace Modules\Brand\Handlers;

use Modules\Brand\CQRS\Command\CreateBrandCommand;
use Modules\Brand\Eloquent\Resolvers\CreateBrandEloquentResolver;
use Modules\Brand\Models\BrandModel;

final class CreateBrandHandler
{
    public function __construct(
        private readonly CreateBrandEloquentResolver $resolver,
    ) {}

    public function handle(CreateBrandCommand $command): BrandModel
    {
        return $this->resolver->resolve($command);
    }
}
