<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Brand\Http\Resources\BrandResource;
use Modules\Brand\Models\BrandModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListBrandsAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $perPage = (int) $request->query('per_page', 20);
        $brands = BrandModel::with(['owner', 'baseCurrency'])->paginate(min($perPage, 100));

        return $this->paginated(
            paginator: $brands,
            resourceClass: BrandResource::class,
        );
    }
}
