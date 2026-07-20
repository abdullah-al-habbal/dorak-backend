<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Handlers\Shared\ShowJobPostingHandler;
use Modules\JobPosting\Http\Requests\Shared\ShowJobPostingRequest;
use Modules\JobPosting\Http\Resources\Shared\JobPostingResource;

final class ShowJobPostingAction extends BaseApiAction
{
    public function __construct(
        private readonly ShowJobPostingHandler $handler,
    ) {}

    public function __invoke(ShowJobPostingRequest $request, string $job): JsonResponse
    {
        $jobPosting = $this->handler->handle($request->toQuery($job));

        return $this->ok(data: new JobPostingResource($jobPosting));
    }
}
