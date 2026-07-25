<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Barber\Resources\BarberAffiliationResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;

class BarberAffiliationResource extends Resource
{
    protected static ?string $model = BarberAffiliationModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'affiliations';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function form(Form $form): Form
    {
        return BarberAffiliationFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return BarberAffiliationTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return BarberAffiliationInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBarberAffiliationsPage::route('/'),
            'view' => Pages\ViewBarberAffiliationPage::route('/{record}'),
        ];
    }
}
