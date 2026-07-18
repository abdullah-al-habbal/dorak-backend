<?php

declare(strict_types=1);

namespace Modules\Review\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Branch\Models\BranchModel;
use Modules\Review\Models\ReviewModel;

final class GetBranchReviewsEloquentResolver
{
    public function getBranchReviews(string $branchId, int $perPage): LengthAwarePaginator
    {
        return ReviewModel::where('subject_id', $branchId)
            ->where('subject_type', BranchModel::class)
            ->with('author')
            ->orderByDesc('created_at')
            ->paginate(min($perPage, 100));
    }
}
