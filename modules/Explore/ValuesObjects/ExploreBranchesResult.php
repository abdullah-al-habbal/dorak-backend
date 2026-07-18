<?php

declare(strict_types=1);

namespace Modules\Explore\ValuesObjects;

use Illuminate\Pagination\LengthAwarePaginator;

final readonly class ExploreBranchesResult
{
    public function __construct(public LengthAwarePaginator $paginator) {}

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        return new self($paginator);
    }
}
