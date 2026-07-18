<?php

declare(strict_types=1);

namespace Modules\Brand\Handlers\Client;

use Modules\Brand\CQRS\Command\Client\UpdateBrandCommand;
use Modules\Brand\Eloquent\Resolvers\Client\UpdateBrandEloquentResolver;
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
