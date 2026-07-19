<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\ClientInteraction\Enums\InteractableTypeEnum;
use Modules\ClientInteraction\Enums\InteractionTypeEnum;

#[Fillable(['client_id', 'interaction_type', 'interactable_id', 'interactable_type', 'context'])]
final class ClientInteractionLogModel extends Model
{
    use HasUuids;

    protected $table = 'client_interaction_logs';

    protected function casts(): array
    {
        return [
            'interaction_type' => InteractionTypeEnum::class,
            'interactable_type' => InteractableTypeEnum::class,
            'context' => 'array',
        ];
    }

    public function interactable(): MorphTo
    {
        return $this->morphTo();
    }
}
