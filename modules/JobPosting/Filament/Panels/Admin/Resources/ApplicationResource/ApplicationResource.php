<?php

declare(strict_types=1);

namespace Modules\JobPosting\Filament\Panels\Admin\Resources\ApplicationResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\JobPosting\Models\ApplicationModel;

class ApplicationResource extends Resource
{
    protected static ?string $model = ApplicationModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'applications';

    public static function form(Form $form): Form
    {
        return ApplicationFormSchema::make($form);
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
            'edit' => Pages\EditApplicationPage::route('/{record}/edit'),
        ];
    }
}
