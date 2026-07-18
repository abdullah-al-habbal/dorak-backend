<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\HasTranslations;

class ServiceCatalogItemTagModel extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

    protected $table = 'service_catalog_item_tags';

    protected array $translatable = [
        'name',
    ];

    protected $fillable = [
        'name',
        'slug',
        'group',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(
            ServiceCatalogItemModel::class,
            'service_catalog_item_tag_assignments',
            'tag_id',
            'item_id',
        );
    }
}
