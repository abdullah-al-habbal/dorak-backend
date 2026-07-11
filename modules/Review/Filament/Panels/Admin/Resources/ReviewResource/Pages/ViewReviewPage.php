<?php

declare(strict_types=1);

namespace Modules\Review\Filament\Panels\Admin\Resources\ReviewResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Review\Filament\Panels\Admin\Resources\ReviewResource\ReviewResource;

class ViewReviewPage extends ViewRecord
{
    protected static string $resource = ReviewResource::class;
}
