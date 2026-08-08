<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\ClientInteraction\Enums\FavorableTypeEnum;

#[Fillable(['client_id', 'favorable_id', 'favorable_type'])]
#[Table('client_favorites')]
final class ClientFavoriteModel extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'favorable_type' => FavorableTypeEnum::class,
        ];
    }

    public function favorable(): MorphTo
    {
        return $this->morphTo();
    }
}
