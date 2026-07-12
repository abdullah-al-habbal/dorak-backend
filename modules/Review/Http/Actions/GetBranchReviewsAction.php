<?php

declare(strict_types=1);

namespace Modules\Review\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Branch\Models\BranchModel;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Review\Http\Resources\ReviewResource;
use Modules\Review\Models\ReviewModel;

final class GetBranchReviewsAction extends BaseApiAction
{
    public function __invoke(string $branch): JsonResponse
    {
        BranchModel::findOrFail($branch);

        $reviews = ReviewModel::where('subject_id', $branch)
            ->where('subject_type', BranchModel::class)
            ->with('author')
            ->orderByDesc('created_at')
            ->paginate(20);

        return $this->paginated(
            paginator: $reviews,
            resourceClass: ReviewResource::class,
        );
    }
}
