<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Handlers\Shared\ListJobPostingsHandler;
use Modules\JobPosting\Http\Requests\Shared\ListJobPostingsRequest;
use Modules\JobPosting\Http\Resources\Shared\JobPostingResource;

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
