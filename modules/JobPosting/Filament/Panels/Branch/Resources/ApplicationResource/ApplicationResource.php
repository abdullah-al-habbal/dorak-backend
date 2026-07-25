<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Branch\Resources\ApplicationResource;

use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\JobPosting\Models\ApplicationModel;

class ApplicationResource extends Resource
{
    protected static ?string $model = ApplicationModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'applications';

    protected static ?string $navigationGroup = 'Job Postings';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function table(Table $table): Table
    {
        return ApplicationsTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ApplicationInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListApplicationsPage::route('/'),
            'view' => Pages\ViewApplicationPage::route('/{record}'),
        ];
    }
}
