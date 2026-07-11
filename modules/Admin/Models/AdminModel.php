<?php

// modules/Admin/Models/AdminModel.php
declare(strict_types=1);

namespace Modules\Admin\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class AdminModel extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;
    use Notifiable;

    protected $table = 'admins';

    /** @phpstan-ignore-next-line */
    public array $translatable = ['name'];

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'admin';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
