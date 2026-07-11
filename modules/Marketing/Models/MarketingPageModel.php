<?php

declare(strict_types=1);

namespace Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Marketing\Database\Factories\MarketingPageFactory;
use Spatie\Translatable\HasTranslations;

final class MarketingPageModel extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;

    protected $table = 'marketing_pages';

    protected $fillable = [
        'slug',
        'title',
        'meta_description',
    ];

    /** @phpstan-ignore-next-line */
    public array $translatable = ['title', 'meta_description'];

    protected function casts(): array
    {
        return [
            'title' => 'array',
            'meta_description' => 'array',
        ];
    }

    public function sections(): HasMany
    {
        return $this->hasMany(SectionModel::class, 'page_id')->orderBy('sort_order');
    }

    protected static function newFactory(): MarketingPageFactory
    {
        return MarketingPageFactory::new();
    }
}
