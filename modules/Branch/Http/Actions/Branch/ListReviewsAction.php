<?php

declare(strict_types=1);

namespace Modules\Branch\Http\Actions\Branch;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Branch\Models\BranchModel;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Review\Http\Resources\Shared\ReviewResource;
use Modules\Review\Models\ReviewModel;

final class ListReviewsAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $branch = $request->user('branch_api');

        $reviews = ReviewModel::where('subject_id', $branch->id)
            ->where('subject_type', BranchModel::class)
            ->with('author')
            ->orderByDesc('created_at')
            ->paginate($request->input('per_page', 15));

        return $this->paginated($reviews, ReviewResource::class);
    }
}
