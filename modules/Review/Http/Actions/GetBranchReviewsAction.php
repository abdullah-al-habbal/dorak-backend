<?php

declare(strict_types=1);

namespace Modules\Review\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Review\Handlers\GetBranchReviewsHandler;
use Modules\Review\Http\Requests\GetBranchReviewsRequest;
use Modules\Review\Http\Resources\ReviewResource;

final class GetBranchReviewsAction extends BaseApiAction
{
    public function __construct(
        private readonly GetBranchReviewsHandler $handler,
    ) {}

    public function __invoke(string $branch, GetBranchReviewsRequest $request): JsonResponse
    {
        $query = $request->toQuery();
        $reviews = $this->handler->handle($query);

        return $this->paginated(paginator: $reviews, resourceClass: ReviewResource::class);
    }
}
