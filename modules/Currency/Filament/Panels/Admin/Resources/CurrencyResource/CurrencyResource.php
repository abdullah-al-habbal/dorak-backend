<?php

declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\CurrencyResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Currency\Models\CurrencyModel;

class CurrencyResource extends Resource
{
    protected static ?string $model = CurrencyModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $recordTitleAttribute = 'name.en';

    protected static ?string $slug = 'currencies';

    public static function form(Form $form): Form
    {
        return CurrencyFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return CurrenciesTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return CurrencyInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCurrenciesPage::route('/'),
            'create' => Pages\CreateCurrencyPage::route('/create'),
            'view' => Pages\ViewCurrencyPage::route('/{record}'),
            'edit' => Pages\EditCurrencyPage::route('/{record}/edit'),
        ];
    }
}
