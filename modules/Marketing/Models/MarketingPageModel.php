<?php

declare(strict_types=1);

namespace Modules\Marketing\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Marketing\Database\Factories\MarketingPageFactory;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable(['slug', 'title', 'meta_description'])]
#[Table('marketing_pages')]
#[Translatable([
    'title',
    'meta_description',
])]
final class MarketingPageModel extends Model
{
    use HasFactory;
    use HasTranslations;
    use HasUuids;

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
