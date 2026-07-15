<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Brand\Http\Requests\CreateBrandRequest;
use Modules\Brand\Http\Resources\BrandResource;
use Modules\Brand\Models\BrandModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class CreateBrandAction extends BaseApiAction
{
    public function __invoke(CreateBrandRequest $request): JsonResponse
    {
        $brand = BrandModel::create([
            'name' => [
                'en' => $request->validated('name.en'),
                'ar' => $request->validated('name.ar'),
            ],
            'description' => [
                'en' => $request->validated('description.en', ''),
                'ar' => $request->validated('description.ar', ''),
            ],
            'owner_id' => $request->validated('owner_id'),
            'base_currency_id' => $request->validated('base_currency_id'),
            'logo' => $request->validated('logo'),
        ]);

        $brand->load(['owner', 'baseCurrency']);

        return $this->created(
            data: new BrandResource($brand),
            message: 'Brand created successfully',
        );
    }
}
