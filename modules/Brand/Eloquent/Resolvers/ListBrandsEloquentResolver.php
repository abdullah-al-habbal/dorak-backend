<?php

declare(strict_types=1);

namespace Modules\Brand\Eloquent\Resolvers;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Brand\CQRS\Query\ListBrandsQuery;
use Modules\Brand\Models\BrandModel;

final class ListBrandsEloquentResolver
{
    public function resolve(ListBrandsQuery $payload): LengthAwarePaginator
    {
        return BrandModel::with(['owner', 'baseCurrency'])->paginate($payload->perPage);
    }
}
