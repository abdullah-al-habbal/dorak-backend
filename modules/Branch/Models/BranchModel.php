<?php

// modules/Branch/Models/BranchModel.php
declare(strict_types=1);

namespace Modules\Branch\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Activation\Models\ActivationLogModel;
use Modules\Brand\Models\BrandModel;
use Modules\Chair\Models\ChairModel;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'email', 'password', 'brand_id', 'latitude', 'longitude'])]
#[Hidden(['password', 'remember_token'])]
class BranchModel extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;
    use Notifiable;

    protected $table = 'branches';

    /** @phpstan-ignore-next-line */
    public array $translatable = ['name'];

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'branch';
    }

    public function activationLogs(): MorphMany
    {
        return $this->morphMany(ActivationLogModel::class, 'activable');
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(BrandModel::class);
    }

    public function chairs(): HasMany
    {
        return $this->hasMany(ChairModel::class);
    }

    public function getIsEnabledAttribute(): bool
    {
        return $this->status === 'enabled';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }
}
