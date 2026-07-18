<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\ServiceCatalog\Eloquent\Casts\PriceRangeCast;
use Modules\ServiceCatalog\Eloquent\Casts\ServiceCatalogItemMetadataCast;
use Spatie\Translatable\HasTranslations;

class ServiceCatalogItemModel extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'service_catalog_items';

    protected array $translatable = [
        'name',
        'description',
    ];

    protected $fillable = [
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
    ];

    protected function casts(): array
    {
        return [
            'price_range' => PriceRangeCast::class,
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
