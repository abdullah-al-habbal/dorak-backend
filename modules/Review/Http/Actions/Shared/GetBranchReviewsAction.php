<?php

declare(strict_types=1);

namespace Modules\Review\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Review\Handlers\Shared\GetBranchReviewsHandler;
use Modules\Review\Http\Requests\Shared\GetBranchReviewsRequest;
use Modules\Review\Http\Resources\Shared\ReviewResource;

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
