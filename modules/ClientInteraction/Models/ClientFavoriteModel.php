<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['client_id', 'favorable_id', 'favorable_type'])]
final class ClientFavoriteModel extends Model
{
    use HasUuids;

    protected $table = 'client_favorites';

    public function favorable(): MorphTo
    {
        return $this->morphTo();
    }
}
