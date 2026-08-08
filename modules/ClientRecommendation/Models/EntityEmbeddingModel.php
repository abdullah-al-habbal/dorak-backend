<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Models;

use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Modules\ClientRecommendation\Eloquent\Casts\EmbeddingCast;

#[Table('entity_embeddings')]
final class EntityEmbeddingModel extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'embedding' => EmbeddingCast::class,
            'metadata' => 'array',
            'computed_at' => 'datetime',
        ];
    }
}
