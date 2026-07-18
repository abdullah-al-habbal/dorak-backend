<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Modules\ServiceCatalog\ValuesObjects\ServiceCatalogItemMetadataValueObject;

final class ServiceCatalogItemMetadataCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?ServiceCatalogItemMetadataValueObject
    {
        if ($value === null) {
            return null;
        }

        $decoded = \json_decode($value, true);

        if (! \is_array($decoded)) {
            return null;
        }

        return ServiceCatalogItemMetadataValueObject::fromArray($decoded);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof ServiceCatalogItemMetadataValueObject) {
            return \json_encode($value->toArray());
        }

        if (\is_array($value)) {
            return \json_encode(ServiceCatalogItemMetadataValueObject::fromArray($value)->toArray());
        }

        return null;
    }
}
