<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Handlers\ListJobPostingsHandler;
use Modules\JobPosting\Http\Requests\ListJobPostingsRequest;
use Modules\JobPosting\Http\Resources\JobPostingResource;

final class ListJobPostingsAction extends BaseApiAction
{
    public function __construct(
        private readonly ListJobPostingsHandler $handler,
    ) {}

    public function __invoke(ListJobPostingsRequest $request): JsonResponse
    {
        $query = $request->toQuery();
        $result = $this->handler->handle($query);

        return $this->paginated(
            paginator: $result,
            resourceClass: JobPostingResource::class,
        );
    }
}
