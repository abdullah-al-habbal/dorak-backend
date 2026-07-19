<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Modules\Client\Enums\UniverseEnum;

#[Fillable(['client_id', 'preferred_universe', 'default_radius', 'hidden_brand_ids', 'show_unavailable'])]
final class ClientDiscoveryPreferenceModel extends Model
{
    use HasUuids;

    protected $table = 'client_discovery_preferences';

    protected function casts(): array
    {
        return [
            'preferred_universe' => UniverseEnum::class,
            'default_radius' => 'float',
            'hidden_brand_ids' => 'array',
            'show_unavailable' => 'boolean',
        ];
    }
}
