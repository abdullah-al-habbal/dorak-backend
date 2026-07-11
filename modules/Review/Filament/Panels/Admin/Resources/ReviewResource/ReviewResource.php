<?php

declare(strict_types=1);

namespace Modules\Review\Filament\Panels\Admin\Resources\ReviewResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Review\Models\ReviewModel;

class ReviewResource extends Resource
{
    protected static ?string $model = ReviewModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-star';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'reviews';

    public static function form(Form $form): Form
    {
        return ReviewFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return ReviewsTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ReviewInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListReviewsPage::route('/'),
            'view' => Pages\ViewReviewPage::route('/{record}'),
        ];
    }
}
