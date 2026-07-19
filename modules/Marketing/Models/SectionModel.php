<?php

declare(strict_types=1);

namespace Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Marketing\Database\Factories\SectionFactory;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'page_id',
    'type',
    'title',
    'subtitle',
    'content',
    'media_url',
    'sort_order',
    'universe_visibility',
])]
final class SectionModel extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;

    protected $table = 'sections';

    /** @phpstan-ignore-next-line */
    public array $translatable = ['content', 'title', 'subtitle'];

    protected function casts(): array
    {
        return [
            'content' => 'array',
            'title' => 'array',
            'subtitle' => 'array',
            'sort_order' => 'integer',
        ];
    }

    public function page(): BelongsTo
    {
        return $this->belongsTo(MarketingPageModel::class, 'page_id');
    }

    public function testimonials(): HasMany
    {
        return $this->hasMany(TestimonialModel::class, 'section_id');
    }

    protected static function newFactory(): SectionFactory
    {
        return SectionFactory::new();
    }
}
