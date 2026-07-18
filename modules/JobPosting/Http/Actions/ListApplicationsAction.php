<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Handlers\ListApplicationsHandler;
use Modules\JobPosting\Http\Requests\ListApplicationsRequest;
use Modules\JobPosting\Http\Resources\ApplicationResource;

final class ListApplicationsAction extends BaseApiAction
{
    public function __construct(
        private readonly ListApplicationsHandler $handler,
    ) {}

    public function __invoke(ListApplicationsRequest $request): JsonResponse
    {
        $query = $request->toQuery();
        $result = $this->handler->handle($query);

        return $this->paginated(
            paginator: $result,
            resourceClass: ApplicationResource::class,
        );
    }
}
