<?php

declare(strict_types=1);

namespace Modules\JobPosting\Eloquent\Resolvers\Barber;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\JobPosting\CQRS\Query\Barber\ListApplicationsQuery;
use Modules\JobPosting\Models\ApplicationModel;

final class ListApplicationsEloquentResolver
{
    public function resolve(ListApplicationsQuery $payload): LengthAwarePaginator
    {
        $query = ApplicationModel::with('jobPosting');

        if ($payload->barberId !== null) {
            $query->where('barber_id', $payload->barberId);
        }

        if ($payload->status !== null) {
            $query->where('status', $payload->status);
        }

        return $query->orderByDesc('created_at')->paginate($payload->perPage);
    }
}
