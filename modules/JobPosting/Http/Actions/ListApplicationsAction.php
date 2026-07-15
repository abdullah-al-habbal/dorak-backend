<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\JobPosting\Http\Resources\ApplicationResource;
use Modules\JobPosting\Models\ApplicationModel;

final class ListApplicationsAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = ApplicationModel::with('jobPosting');

        if ($request->has('barber_id')) {
            $query->where('barber_id', $request->query('barber_id'));
        }

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $applications = $query->orderByDesc('created_at')->paginate(20);

        return $this->paginated(
            paginator: $applications,
            resourceClass: ApplicationResource::class,
        );
    }
}
