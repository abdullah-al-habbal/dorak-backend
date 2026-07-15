<?php

declare(strict_types=1);

namespace Modules\Review\Filament\Panels\Admin\Resources\ReviewResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Review\Filament\Panels\Admin\Resources\ReviewResource\ReviewResource;

class EditReviewPage extends EditRecord
{
    protected static string $resource = ReviewResource::class;
}
