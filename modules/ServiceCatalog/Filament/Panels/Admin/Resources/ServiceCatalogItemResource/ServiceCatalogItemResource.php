<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\ServiceCatalog\Models\ServiceCatalogItemModel;

class ServiceCatalogItemResource extends Resource
{
    protected static ?string $model = ServiceCatalogItemModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-cube';

    protected static ?string $recordTitleAttribute = 'name.en';

    protected static ?string $slug = 'service-catalog/items';

    public static function form(Form $form): Form
    {
        return ServiceCatalogItemFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return ServiceCatalogItemsTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ServiceCatalogItemInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceCatalogItemsPage::route('/'),
            'create' => Pages\CreateServiceCatalogItemPage::route('/create'),
            'view' => Pages\ViewServiceCatalogItemPage::route('/{record}'),
            'edit' => Pages\EditServiceCatalogItemPage::route('/{record}/edit'),
        ];
    }
}
