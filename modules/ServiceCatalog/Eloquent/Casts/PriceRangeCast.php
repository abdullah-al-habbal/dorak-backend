<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Modules\ServiceCatalog\ValuesObjects\PriceRangeValueObject;

final class PriceRangeCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?PriceRangeValueObject
    {
        if ($value === null) {
            return null;
        }

        $decoded = \json_decode($value, true);

        if (! \is_array($decoded)) {
            return null;
        }

        return PriceRangeValueObject::fromArray($decoded);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof PriceRangeValueObject) {
            return \json_encode($value->toArray());
        }

        if (\is_array($value)) {
            return \json_encode(PriceRangeValueObject::fromArray($value)->toArray());
        }

        return null;
    }
}
