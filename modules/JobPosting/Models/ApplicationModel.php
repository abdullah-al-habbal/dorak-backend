<?php

// modules/JobPosting/Models/ApplicationModel.php
declare(strict_types=1);

namespace Modules\JobPosting\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Barber\Models\BarberModel;
use Modules\JobPosting\Enums\ApplicationStatus;

#[Fillable(['job_posting_id', 'barber_id', 'profile_snapshot', 'status'])]
#[Table('job_applications')]
class ApplicationModel extends Model
{
    use HasFactory;
    use HasUuids;

    protected function casts(): array
    {
        return [
            'status' => ApplicationStatus::class,
            'profile_snapshot' => 'array',
        ];
    }

    public function jobPosting(): BelongsTo
    {
        return $this->belongsTo(JobPostingModel::class, 'job_posting_id');
    }

    public function barber(): BelongsTo
    {
        return $this->belongsTo(BarberModel::class, 'barber_id');
    }
}
