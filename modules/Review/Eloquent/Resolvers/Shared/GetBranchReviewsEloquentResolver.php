<?php

declare(strict_types=1);

namespace Modules\Review\Eloquent\Resolvers\Shared;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Branch\Models\BranchModel;
use Modules\Review\CQRS\Query\Shared\GetBranchReviewsQuery;
use Modules\Review\Models\ReviewModel;

final class GetBranchReviewsEloquentResolver
{
    public function resolve(GetBranchReviewsQuery $payload): LengthAwarePaginator
    {
        return ReviewModel::where('subject_id', $payload->branchId)
            ->where('subject_type', BranchModel::class)
            ->with('author')
            ->orderByDesc('created_at')
            ->paginate($payload->perPage);
    }
}
