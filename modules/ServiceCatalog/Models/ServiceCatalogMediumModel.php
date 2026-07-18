<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ServiceCatalogMediumModel extends Model
{
    protected $table = 'service_catalog_media';

    protected $fillable = [
        'type',
        'path',
        'alt',
        'sort_order',
    ];

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
