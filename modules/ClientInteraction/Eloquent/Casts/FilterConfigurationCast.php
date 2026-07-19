<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Modules\ClientInteraction\ValuesObjects\FilterConfigurationValueObject;

final class FilterConfigurationCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): FilterConfigurationValueObject
    {
        return FilterConfigurationValueObject::fromArray(json_decode($value ?? '{}', true));
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): string
    {
        if ($value instanceof FilterConfigurationValueObject) {
            return json_encode($value->toArray());
        }

        return json_encode($value);
    }
}
