<?php

declare(strict_types=1);

namespace Modules\Preference\Filament\Panels\Admin\Resources\PreferenceResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Preference\Models\PreferenceModel;

class PreferenceResource extends Resource
{
    protected static ?string $model = PreferenceModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'preferences';

    public static function form(Form $form): Form
    {
        return PreferenceFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return PreferencesTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return PreferenceInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPreferencesPage::route('/'),
            'create' => Pages\CreatePreferencePage::route('/create'),
            'view' => Pages\ViewPreferencePage::route('/{record}'),
            'edit' => Pages\EditPreferencePage::route('/{record}/edit'),
        ];
    }
}
