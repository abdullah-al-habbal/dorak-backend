<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\ClientHistory\Enums\HistoryMediaType;

#[Fillable(['history_id', 'photo_url', 'photo_type', 'uploaded_at'])]
#[Table('client_service_history_media')]
class ClientServiceHistoryMediaModel extends Model
{
    use HasUuids;

    protected function casts(): array
    {
        return [
            'photo_type' => HistoryMediaType::class,
            'uploaded_at' => 'datetime',
        ];
    }

    public function history(): BelongsTo
    {
        return $this->belongsTo(ClientServiceHistoryModel::class, 'history_id');
    }
}
