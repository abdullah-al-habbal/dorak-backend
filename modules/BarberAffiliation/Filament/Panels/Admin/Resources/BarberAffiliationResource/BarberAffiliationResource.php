<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Filament\Panels\Admin\Resources\BarberAffiliationResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;

class BarberAffiliationResource extends Resource
{
    protected static ?string $model = BarberAffiliationModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'barber-affiliations';

    public static function form(Form $form): Form
    {
        return BarberAffiliationFormSchema::make($form);
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
            'create' => Pages\CreateBarberAffiliationPage::route('/create'),
            'view' => Pages\ViewBarberAffiliationPage::route('/{record}'),
            'edit' => Pages\EditBarberAffiliationPage::route('/{record}/edit'),
        ];
    }
}
