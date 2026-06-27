<?php
declare(strict_types=1);

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Modules\Core\Database\Factories\UserFactory;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class UserModel extends Authenticatable
{
    use HasFactory;
    use HasUuids;
    use HasTranslations;
    use Notifiable;

    protected $table = 'users';

    /** @phpstan-ignore-next-line */
    public array $translatable = ['name'];

    protected static function newFactory(): UserFactory
    {
        return UserFactory::new();
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
