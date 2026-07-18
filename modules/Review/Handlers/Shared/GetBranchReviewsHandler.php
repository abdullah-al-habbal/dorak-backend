<?php

declare(strict_types=1);

namespace Modules\Review\Handlers\Shared;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Branch\Models\BranchModel;
use Modules\Review\CQRS\Query\Shared\GetBranchReviewsQuery;
use Modules\Review\Eloquent\Resolvers\Shared\GetBranchReviewsEloquentResolver;

final class GetBranchReviewsHandler
{
    public function __construct(
        private readonly GetBranchReviewsEloquentResolver $resolver,
    ) {}

    public function handle(GetBranchReviewsQuery $payload): LengthAwarePaginator
    {
        BranchModel::findOrFail($payload->branchId);

        return $this->resolver->resolve($payload);
    }
}
