<?php
// modules/JobPosting/Models/ApplicationModel.php
declare(strict_types=1);

namespace Modules\JobPosting\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Barber\Models\BarberModel;

class ApplicationModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected $table = 'job_applications';

    protected $fillable = [
        'job_posting_id', 'barber_id', 'profile_snapshot', 'status',
    ];

    protected function casts(): array
    {
        return [
            'profile_snapshot' => 'array',
        ];
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPostingModel::class);
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(BarberModel::class);
    }
}
