<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ServiceCatalog\Eloquent\Casts\PriceRangeCast;
use Modules\ServiceCatalog\Eloquent\Casts\ServiceCatalogItemMetadataCast;
use Modules\ServiceCatalog\Enums\FormalityEnum;
use Modules\ServiceCatalog\Enums\MaintenanceLevelEnum;
use Modules\ServiceCatalog\Enums\StylePeriodEnum;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable([
    'category_id',
    'name',
    'description',
    'slug',
    'sku',
    'price_range',
    'maintenance_level',
    'style_period',
    'formality',
    'face_shapes',
    'hair_textures',
    'metadata',
    'is_active',
    'sort_order',
])]
#[Table('service_catalog_items')]
#[Translatable([
    'name',
    'description',
])]
class ServiceCatalogItemModel extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected function casts(): array
    {
        return [
            'price_range' => PriceRangeCast::class,
            'maintenance_level' => MaintenanceLevelEnum::class,
            'style_period' => StylePeriodEnum::class,
            'formality' => FormalityEnum::class,
            'face_shapes' => 'array',
            'hair_textures' => 'array',
            'metadata' => ServiceCatalogItemMetadataCast::class,
            'is_active' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCatalogCategoryModel::class, 'category_id');
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            ServiceCatalogItemTagModel::class,
            'service_catalog_item_tag_assignments',
            'item_id',
            'tag_id',
        );
    }

    public function media(): MorphMany
    {
        return $this->morphMany(ServiceCatalogMediumModel::class, 'mediable');
    }
}
