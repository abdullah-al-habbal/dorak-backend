<?php

declare(strict_types=1);

namespace Modules\Review\ValuesObjects;

use Illuminate\Pagination\LengthAwarePaginator;

final readonly class GetBranchReviewsResult
{
    public function __construct(public LengthAwarePaginator $paginator) {}

    public static function fromPaginator(LengthAwarePaginator $paginator): self
    {
        return new self($paginator);
    }
}
