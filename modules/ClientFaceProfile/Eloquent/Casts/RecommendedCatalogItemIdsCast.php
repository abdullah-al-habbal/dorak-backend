<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Modules\ClientFaceProfile\ValuesObjects\RecommendedCatalogItemIdsValueObject;

final class RecommendedCatalogItemIdsCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?RecommendedCatalogItemIdsValueObject
    {
        if ($value === null) {
            return null;
        }

        $decoded = \json_decode($value, true);

        if (! \is_array($decoded)) {
            return null;
        }

        return RecommendedCatalogItemIdsValueObject::fromArray($decoded);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof RecommendedCatalogItemIdsValueObject) {
            return \json_encode($value->toArray());
        }

        if (\is_array($value)) {
            return \json_encode(RecommendedCatalogItemIdsValueObject::fromArray($value)->toArray());
        }

        return null;
    }
}
