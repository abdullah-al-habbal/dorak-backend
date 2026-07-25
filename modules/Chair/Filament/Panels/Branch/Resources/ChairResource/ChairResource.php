<?php

declare(strict_types=1);

namespace Modules\Chair\Filament\Panels\Branch\Resources\ChairResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Chair\Models\ChairModel;

class ChairResource extends Resource
{
    protected static ?string $model = ChairModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $recordTitleAttribute = 'label';

    protected static ?string $slug = 'chairs';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function form(Form $form): Form
    {
        return ChairFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return ChairsTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ChairInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListChairsPage::route('/'),
            'create' => Pages\CreateChairPage::route('/create'),
            'edit' => Pages\EditChairPage::route('/{record}/edit'),
            'view' => Pages\ViewChairPage::route('/{record}'),
        ];
    }
}
