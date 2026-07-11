<?php

declare(strict_types=1);

namespace Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Marketing\Database\Factories\TestimonialFactory;
use Spatie\Translatable\HasTranslations;

final class TestimonialModel extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;

    protected $table = 'testimonials';

    protected $fillable = [
        'section_id',
        'author_name',
        'author_title',
        'quote',
        'avatar_url',
        'rating',
        'display_order',
    ];

    /** @phpstan-ignore-next-line */
    public array $translatable = ['quote', 'author_title'];

    protected function casts(): array
    {
        return [
            'quote' => 'array',
            'author_title' => 'array',
            'rating' => 'integer',
            'display_order' => 'integer',
        ];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(SectionModel::class, 'section_id');
    }

    protected static function newFactory(): TestimonialFactory
    {
        return TestimonialFactory::new();
    }
}
