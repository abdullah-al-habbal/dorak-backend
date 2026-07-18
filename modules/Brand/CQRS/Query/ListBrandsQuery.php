<?php

declare(strict_types=1);

namespace Modules\Brand\CQRS\Query;

final readonly class ListBrandsQuery
{
    public function __construct(
        public int $perPage,
    ) {}
}
