<?php
// modules/JobPosting/Models/JobPostingModel.php
declare(strict_types=1);

namespace Modules\JobPosting\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Branch\Models\BranchModel;
use Spatie\Translatable\HasTranslations;

class JobPostingModel extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;

    protected $table = 'job_postings';

    protected $fillable = [
        'branch_id', 'title', 'description', 'status',
    ];

    /** @phpstan-ignore-next-line */
    public array $translatable = ['title', 'description'];

    protected function casts(): array
    {
        return [
            'title'       => 'array',
            'description' => 'array',
        ];
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(BranchModel::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(ApplicationModel::class);
    }
}
