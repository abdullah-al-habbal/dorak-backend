<?php

declare(strict_types=1);

namespace Modules\Barber\Models;

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
use Laravel\Sanctum\HasApiTokens;
use Modules\Activation\Models\ActivationLogModel;
use Modules\Ban\Models\BanModel;
use Modules\Barber\Database\Factories\BarberFactory;
use Modules\Barber\Enums\BarberStatusEnum;
use Modules\Booking\Models\BookingModel;
use Modules\OfferedService\Models\OfferedServiceModel;
use Modules\Review\Models\ReviewModel;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'email', 'password', 'status', 'is_freelancer', 'client_id', 'latitude', 'longitude', 'travel_radius'])]
#[Hidden(['password', 'remember_token'])]
class BarberModel extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use HasTranslations;
    use HasUuids;
    use Notifiable;

    protected $table = 'barbers';

    /** @phpstan-ignore-next-line */
    public array $translatable = ['name'];

    protected static function newFactory(): BarberFactory
    {
        return BarberFactory::new();
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $panel->getId() === 'barber';
    }

    public function activationLogs(): MorphMany
    {
        return $this->morphMany(ActivationLogModel::class, 'activable');
    }

    public function bans(): MorphMany
    {
        return $this->morphMany(BanModel::class, 'bannable');
    }

    public function services(): MorphMany
    {
        return $this->morphMany(OfferedServiceModel::class, 'serviceable');
    }

    public function reviews(): MorphMany
    {
        return $this->morphMany(ReviewModel::class, 'subject');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(BookingModel::class, 'barber_id');
    }

    public function getIsEnabledAttribute(): bool
    {
        return $this->status === BarberStatusEnum::Enabled;
    }

    protected function casts(): array
    {
        return [
            'status' => BarberStatusEnum::class,
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'travel_radius' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }
}
