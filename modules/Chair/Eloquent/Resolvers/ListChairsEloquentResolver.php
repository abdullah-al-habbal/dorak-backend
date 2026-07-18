<?php

declare(strict_types=1);

namespace Modules\Chair\Eloquent\Resolvers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Chair\CQRS\Query\ListChairsQuery;
use Modules\Chair\Models\ChairModel;

final class ListChairsEloquentResolver
{
    public function resolve(ListChairsQuery $payload): LengthAwarePaginator
    {
        $query = ChairModel::with(['branch', 'barber']);

        if ($payload->branchId !== null) {
            $query->where('branch_id', $payload->branchId);
        }

        if ($payload->status !== null) {
            $query->where('status', $payload->status);
        }

        return $query->paginate($payload->perPage);
    }
}
