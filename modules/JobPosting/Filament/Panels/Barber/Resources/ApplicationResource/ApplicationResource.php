<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Barber\Resources\ApplicationResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\JobPosting\Models\ApplicationModel;

class ApplicationResource extends Resource
{
    protected static ?string $model = ApplicationModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'applications';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function form(Form $form): Form
    {
        return ApplicationFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return ApplicationTable::make($table);
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
            'create' => Pages\CreateApplicationPage::route('/create'),
            'view' => Pages\ViewApplicationPage::route('/{record}'),
        ];
    }
}
