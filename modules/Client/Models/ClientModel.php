<?php

// modules/Client/Models/ClientModel.php
declare(strict_types=1);

namespace Modules\Client\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Ban\Models\BanModel;
use Modules\Booking\Models\BookingModel;
use Modules\Client\Database\Factories\ClientFactory;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'email', 'password', 'preferred_universe'])]
#[Hidden(['password', 'remember_token'])]
class ClientModel extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;
    use Notifiable;

    protected $table = 'clients';

    /** @phpstan-ignore-next-line */
    public array $translatable = ['name'];

    protected static function newFactory(): ClientFactory
    {
        return ClientFactory::new();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'barber' && $this->isBarber();
    }

    public function isBarber(): bool
    {
        return false;
    }

    public function bans(): MorphMany
    {
        return $this->morphMany(BanModel::class, 'bannable');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingModel::class, 'client_id');
    }

    public function isBanned(): bool
    {
        return $this->bans()->active()->exists();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'preferred_universe' => 'string',
        ];
    }
}
