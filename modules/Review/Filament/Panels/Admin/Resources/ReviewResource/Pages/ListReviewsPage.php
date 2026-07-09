<?php
declare(strict_types=1);

namespace Modules\Review\Filament\Panels\Admin\Resources\ReviewResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Review\Filament\Panels\Admin\Resources\ReviewResource\ReviewResource;

class ListReviewsPage extends ListRecords
{
    protected static string $resource = ReviewResource::class;
}
