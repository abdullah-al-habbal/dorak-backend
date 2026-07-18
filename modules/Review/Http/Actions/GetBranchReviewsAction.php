<?php

declare(strict_types=1);

namespace Modules\Review\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Review\Handlers\GetBranchReviewsHandler;
use Modules\Review\Http\Resources\ReviewResource;

final class GetBranchReviewsAction extends BaseApiAction
{
    public function __construct(
        private readonly GetBranchReviewsHandler $handler,
    ) {}

    public function __invoke(string $branch, Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 20);

        $reviews = $this->handler->handle($branch, $perPage);

        return $this->paginated(paginator: $reviews, resourceClass: ReviewResource::class);
    }
}
