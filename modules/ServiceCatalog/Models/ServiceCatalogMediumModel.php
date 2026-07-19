<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable(['type', 'path', 'alt', 'sort_order'])]
class ServiceCatalogMediumModel extends Model
{
    protected $table = 'service_catalog_media';

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
