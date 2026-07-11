<?php

declare(strict_types=1);

namespace Modules\Language\Filament\Panels\Admin\Resources\LanguageResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Language\Models\LanguageModel;

class LanguageResource extends Resource
{
    protected static ?string $model = LanguageModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-language';

    protected static ?string $recordTitleAttribute = 'name.en';

    protected static ?string $slug = 'languages';

    public static function form(Form $form): Form
    {
        return LanguageFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return LanguagesTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return LanguageInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLanguagesPage::route('/'),
            'create' => Pages\CreateLanguagePage::route('/create'),
            'view' => Pages\ViewLanguagePage::route('/{record}'),
            'edit' => Pages\EditLanguagePage::route('/{record}/edit'),
        ];
    }
}
