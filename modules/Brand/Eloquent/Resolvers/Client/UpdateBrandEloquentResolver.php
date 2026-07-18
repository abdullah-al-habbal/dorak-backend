<?php

declare(strict_types=1);

namespace Modules\Brand\Eloquent\Resolvers\Client;

use Modules\Brand\CQRS\Command\Client\UpdateBrandCommand;
use Modules\Brand\Models\BrandModel;

final class UpdateBrandEloquentResolver
{
    public function resolve(UpdateBrandCommand $command): BrandModel
    {
        $brand = BrandModel::findOrFail($command->brandId);

        $data = [];

        if ($command->nameEn !== null || $command->nameAr !== null) {
            $data['name'] = [
                'en' => $command->nameEn ?? $brand->getTranslation('name', 'en'),
                'ar' => $command->nameAr ?? $brand->getTranslation('name', 'ar'),
            ];
        }

        if ($command->descriptionEn !== null || $command->descriptionAr !== null) {
            $data['description'] = [
                'en' => $command->descriptionEn ?? $brand->getTranslation('description', 'en'),
                'ar' => $command->descriptionAr ?? $brand->getTranslation('description', 'ar'),
            ];
        }

        if ($command->ownerId !== null) {
            $data['owner_id'] = $command->ownerId;
        }

        if ($command->baseCurrencyId !== null) {
            $data['base_currency_id'] = $command->baseCurrencyId;
        }

        if ($command->logo !== null) {
            $data['logo'] = $command->logo;
        }

        $brand->update($data);
        $brand->load(['owner', 'baseCurrency']);

        return $brand;
    }
}
