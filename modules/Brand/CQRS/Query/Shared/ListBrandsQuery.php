<?php

declare(strict_types=1);

namespace Modules\Brand\CQRS\Query\Shared;

final readonly class ListBrandsQuery
{
    public function __construct(
        public int $perPage,
    ) {}
}
