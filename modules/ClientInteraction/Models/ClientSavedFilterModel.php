<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Modules\ClientInteraction\Eloquent\Casts\FilterConfigurationCast;

#[Fillable(['client_id', 'name', 'filter_config'])]
#[Table('client_saved_filters')]
final class ClientSavedFilterModel extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'filter_config' => FilterConfigurationCast::class,
        ];
    }
}
