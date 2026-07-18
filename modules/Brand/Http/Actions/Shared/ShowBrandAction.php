<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Actions\Shared;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Brand\Http\Resources\Shared\BrandResource;
use Modules\Brand\Models\BrandModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ShowBrandAction extends BaseApiAction
{
    public function __invoke(Request $request, string $brand): JsonResponse
    {
        $brand = BrandModel::with(['owner', 'baseCurrency', 'branches'])->findOrFail($brand);

        return $this->ok(data: new BrandResource($brand));
    }
}
