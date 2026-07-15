<?php

declare(strict_types=1);

namespace Modules\Brand\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Brand\Http\Requests\UpdateBrandRequest;
use Modules\Brand\Http\Resources\BrandResource;
use Modules\Brand\Models\BrandModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateBrandAction extends BaseApiAction
{
    public function __invoke(UpdateBrandRequest $request, string $brand): JsonResponse
    {
        $brand = BrandModel::findOrFail($brand);

        $data = $request->validated();

        if (isset($data['name'])) {
            $data['name'] = [
                'en' => $data['name']['en'] ?? $brand->getTranslation('name', 'en'),
                'ar' => $data['name']['ar'] ?? $brand->getTranslation('name', 'ar'),
            ];
        }

        if (isset($data['description'])) {
            $data['description'] = [
                'en' => $data['description']['en'] ?? $brand->getTranslation('description', 'en'),
                'ar' => $data['description']['ar'] ?? $brand->getTranslation('description', 'ar'),
            ];
        }

        $brand->update($data);
        $brand->load(['owner', 'baseCurrency']);

        return $this->ok(
            data: new BrandResource($brand),
            message: 'Brand updated successfully',
        );
    }
}
