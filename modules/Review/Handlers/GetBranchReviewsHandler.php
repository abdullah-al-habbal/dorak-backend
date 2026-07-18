<?php

declare(strict_types=1);

namespace Modules\Review\Handlers;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Branch\Models\BranchModel;
use Modules\Review\Repositories\GetBranchReviewsEloquentResolver;

final class GetBranchReviewsHandler
{
    public function __construct(
        private readonly GetBranchReviewsEloquentResolver $resolver,
    ) {}

    public function handle(string $branchId, int $perPage): LengthAwarePaginator
    {
        BranchModel::findOrFail($branchId);

        return $this->resolver->getBranchReviews($branchId, $perPage);
    }
}
