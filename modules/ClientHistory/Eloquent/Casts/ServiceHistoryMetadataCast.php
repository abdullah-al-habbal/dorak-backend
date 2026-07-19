<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Eloquent\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Modules\ClientHistory\ValuesObjects\ServiceHistoryMetadataValueObject;

final class ServiceHistoryMetadataCast implements CastsAttributes
{
    public function get(Model $model, string $key, mixed $value, array $attributes): ?ServiceHistoryMetadataValueObject
    {
        if ($value === null) {
            return null;
        }

        $decoded = \json_decode($value, true);

        if (! \is_array($decoded)) {
            return null;
        }

        return ServiceHistoryMetadataValueObject::fromArray($decoded);
    }

    public function set(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        if ($value === null) {
            return null;
        }

        if ($value instanceof ServiceHistoryMetadataValueObject) {
            return \json_encode($value->toArray());
        }

        if (\is_array($value)) {
            return \json_encode(ServiceHistoryMetadataValueObject::fromArray($value)->toArray());
        }

        return null;
    }
}
