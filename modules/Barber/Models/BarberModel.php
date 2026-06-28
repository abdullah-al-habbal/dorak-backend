<?php
// modules/Barber/Models/BarberModel.php
declare(strict_types=1);

namespace Modules\Barber\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Activation\Models\ActivationLogModel;
use Modules\Ban\Models\BanModel;
use Modules\Barber\Database\Factories\BarberFactory;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class BarberModel extends Authenticatable implements FilamentUser
{
    use HasFactory;
    use HasUuids;
    use HasTranslations;
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

    public function getIsEnabledAttribute(): bool
    {
        return $this->status === 'enabled';
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
