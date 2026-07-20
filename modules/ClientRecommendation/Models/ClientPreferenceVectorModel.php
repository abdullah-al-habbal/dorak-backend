<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Client\Models\ClientModel;
use Modules\ClientRecommendation\Eloquent\Casts\EmbeddingCast;

final class ClientPreferenceVectorModel extends Model
{
    use HasUuids;

    protected $table = 'client_preference_vectors';

    protected function casts(): array
    {
        return [
            'embedding' => EmbeddingCast::class,
            'metadata' => 'array',
            'computed_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id');
    }
}
