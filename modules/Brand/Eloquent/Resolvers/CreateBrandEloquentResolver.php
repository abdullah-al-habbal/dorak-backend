<?php

declare(strict_types=1);

namespace Modules\Brand\Eloquent\Resolvers;

use Modules\Brand\CQRS\Command\CreateBrandCommand;
use Modules\Brand\Models\BrandModel;

final class CreateBrandEloquentResolver
{
    public function resolve(CreateBrandCommand $command): BrandModel
    {
        $brand = BrandModel::create([
            'name' => [
                'en' => $command->nameEn,
                'ar' => $command->nameAr,
            ],
            'description' => [
                'en' => $command->descriptionEn ?? '',
                'ar' => $command->descriptionAr ?? '',
            ],
            'owner_id' => $command->ownerId,
            'base_currency_id' => $command->baseCurrencyId,
            'logo' => $command->logo,
        ]);

        $brand->load(['owner', 'baseCurrency']);

        return $brand;
    }
}
