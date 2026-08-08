<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable(['name', 'slug', 'group', 'is_active'])]
#[Table('service_catalog_item_tags')]
#[Translatable(['name'])]
class ServiceCatalogItemTagModel extends Model
{
    use HasFactory, HasTranslations, SoftDeletes;

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
