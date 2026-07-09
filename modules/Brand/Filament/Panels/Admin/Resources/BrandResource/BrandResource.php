<?php
declare(strict_types=1);

namespace Modules\Brand\Filament\Panels\Admin\Resources\BrandResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Activation\Filament\Actions\ToggleActivationAction;
use Modules\Brand\Models\BrandModel;

class BrandResource extends Resource
{
    protected static ?string $model = BrandModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';

    protected static ?string $recordTitleAttribute = 'name.en';

    protected static ?string $slug = 'brands';

    public static function form(Form $form): Form
    {
        return BrandFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return BrandsTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return BrandInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListBrandsPage::route('/'),
            'create' => Pages\CreateBrandPage::route('/create'),
            'view'   => Pages\ViewBrandPage::route('/{record}'),
            'edit'   => Pages\EditBrandPage::route('/{record}/edit'),
        ];
    }
}
