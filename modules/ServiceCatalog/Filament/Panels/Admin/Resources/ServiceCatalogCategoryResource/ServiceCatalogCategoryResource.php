<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Filament\Panels\Admin\Resources\ServiceCatalogCategoryResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\ServiceCatalog\Models\ServiceCatalogCategoryModel;

class ServiceCatalogCategoryResource extends Resource
{
    protected static ?string $model = ServiceCatalogCategoryModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name.en';

    protected static ?string $slug = 'service-catalog/categories';

    public static function form(Form $form): Form
    {
        return ServiceCatalogCategoryFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return ServiceCatalogCategoriesTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ServiceCatalogCategoryInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListServiceCatalogCategoriesPage::route('/'),
            'create' => Pages\CreateServiceCatalogCategoryPage::route('/create'),
            'view' => Pages\ViewServiceCatalogCategoryPage::route('/{record}'),
            'edit' => Pages\EditServiceCatalogCategoryPage::route('/{record}/edit'),
        ];
    }
}
