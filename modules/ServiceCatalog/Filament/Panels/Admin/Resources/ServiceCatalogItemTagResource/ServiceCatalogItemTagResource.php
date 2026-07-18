<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogItemTagResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\ServiceCatalog\Models\ServiceCatalogItemTagModel;

class ServiceCatalogItemTagResource extends Resource
{
    protected static ?string $model = ServiceCatalogItemTagModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-tag';

    protected static ?string $recordTitleAttribute = 'name.en';

    protected static ?string $slug = 'service-catalog/tags';

    public static function form(Form $form): Form
    {
        return ServiceCatalogItemTagFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return ServiceCatalogItemTagsTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ServiceCatalogItemTagInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceCatalogItemTagsPage::route('/'),
            'create' => Pages\CreateServiceCatalogItemTagPage::route('/create'),
            'view' => Pages\ViewServiceCatalogItemTagPage::route('/{record}'),
            'edit' => Pages\EditServiceCatalogItemTagPage::route('/{record}/edit'),
        ];
    }
}
