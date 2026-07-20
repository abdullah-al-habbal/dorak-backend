<?php

declare(strict_types=1);

namespace Modules\Ban\Handlers\Client;

use Modules\Ban\CQRS\Query\Client\CheckClientBanQuery;
use Modules\Ban\Eloquent\Resolvers\Client\CheckClientBanEloquentResolver;

final class CheckClientBanHandler
{
    public function __construct(
        private readonly CheckClientBanEloquentResolver $resolver,
    ) {}

    public function handle(CheckClientBanQuery $query): bool
    {
        return $this->resolver->resolve($query);
    }
}
