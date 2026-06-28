<?php
// modules/Activation/Models/ActivationLogModel.php
declare(strict_types=1);

namespace Modules\Activation\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Modules\Activation\Enums\ActivationStatusEnum;
use Modules\Admin\Models\AdminModel;

class ActivationLogModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'activation_logs';

    protected $fillable = [
        'activable_id',
        'activable_type',
        'status',
        'reason',
        'admin_id',
        'activated_at',
        'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'status'       => ActivationStatusEnum::class,
            'activated_at' => 'datetime',
            'expires_at'   => 'datetime',
        ];
    }

    public function activable(): MorphTo
    {
        return $this->morphTo();
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(AdminModel::class, 'admin_id');
    }
}
