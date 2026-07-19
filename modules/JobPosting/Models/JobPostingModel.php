<?php

// modules/JobPosting/Models/JobPostingModel.php
declare(strict_types=1);

namespace Modules\JobPosting\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Branch\Models\BranchModel;
use Spatie\Translatable\HasTranslations;

#[Fillable(['branch_id', 'title', 'description', 'status', 'requirements', 'location', 'type'])]
class JobPostingModel extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;

    protected $table = 'job_postings';

    /** @phpstan-ignore-next-line */
    public array $translatable = ['title', 'description'];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'description' => 'array',
            'requirements' => 'array',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(BranchModel::class, 'branch_id');
    }

    public function applications(): HasMany
    {
        return $this->hasMany(ApplicationModel::class, 'job_posting_id');
    }
}
