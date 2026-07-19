<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Client\Models\ClientModel;

#[Fillable(['client_id', 'image_url', 'image_hash', 'is_primary', 'uploaded_at'])]
class ClientFaceProfileModel extends Model
{
    use HasUuids;

    protected $table = 'client_face_profiles';

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'uploaded_at' => 'datetime',
        ];
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(ClientModel::class, 'client_id');
    }

    public function analysisResults(): HasMany
    {
        return $this->hasMany(ClientFaceAnalysisResultModel::class, 'face_profile_id');
    }
}
