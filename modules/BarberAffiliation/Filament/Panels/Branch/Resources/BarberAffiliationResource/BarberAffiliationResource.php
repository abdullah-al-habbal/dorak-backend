<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Branch\Resources\BarberAffiliationResource;

use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;

class BarberAffiliationResource extends Resource
{
    protected static ?string $model = BarberAffiliationModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'barber-affiliations';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function table(Table $table): Table
    {
        return BarberAffiliationsTable::make($table);
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
