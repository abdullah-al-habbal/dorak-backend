<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\ClientRecommendation\Enums\EdgeTypeEnum;

#[Fillable([
    'source_type', 'source_id',
    'target_type', 'target_id',
    'edge_type', 'weight', 'context', 'expires_at',
])]
#[Table('recommendation_edges')]
final class RecommendationEdgeModel extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'edge_type' => EdgeTypeEnum::class,
            'weight' => 'float',
            'context' => 'array',
            'expires_at' => 'datetime',
        ];
    }

    public function source(): MorphTo
    {
        return $this->morphTo();
    }

    public function target(): MorphTo
    {
        return $this->morphTo();
    }
}
