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
        $brands = BrandModel::with(['owner', 'baseCurrency'])->paginate(20);

        return $this->paginated(
            paginator: $brands,
            resourceClass: BrandResource::class,
        );
    }
}
