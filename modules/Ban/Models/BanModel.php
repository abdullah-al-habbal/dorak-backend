<?php

// modules/Ban/Models/BanModel.php
declare(strict_types=1);

namespace Modules\Ban\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Admin\Models\AdminModel;

#[Fillable([
    'bannable_id',
    'bannable_type',
    'reason',
    'banned_from',
    'banned_until',
    'admin_id',
])]
class BanModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'bans';

    protected function casts(): array
    {
        return [
            'banned_from' => 'datetime',
            'banned_until' => 'datetime',
        ];
    }

    public function bannable(): MorphTo
    {
        return $this->morphTo();
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(AdminModel::class, 'admin_id');
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('banned_from', '<=', now())
            ->where(fn (Builder $q) => $q
                ->whereNull('banned_until')
                ->orWhere('banned_until', '>', now())
            );
    }
}
