<?php

declare(strict_types=1);

namespace Modules\Currency\Filament\Panels\Admin\Resources\ExchangeRateResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Currency\Models\ExchangeRateModel;

class ExchangeRateResource extends Resource
{
    protected static ?string $model = ExchangeRateModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?string $recordTitleAttribute = 'from_currency_id';

    protected static ?string $slug = 'exchange-rates';

    public static function form(Form $form): Form
    {
        return ExchangeRateFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return ExchangeRatesTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ExchangeRateInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListExchangeRatesPage::route('/'),
            'create' => Pages\CreateExchangeRatePage::route('/create'),
            'view' => Pages\ViewExchangeRatePage::route('/{record}'),
            'edit' => Pages\EditExchangeRatePage::route('/{record}/edit'),
        ];
    }
}
