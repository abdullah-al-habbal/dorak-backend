<?php

declare(strict_types=1);

namespace Modules\Review\Filament\Panels\Admin\Resources\ReviewResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Review\Filament\Panels\Admin\Resources\ReviewResource\ReviewResource;

class CreateReviewPage extends CreateRecord
{
    protected static string $resource = ReviewResource::class;
}
