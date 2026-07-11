<?php

declare(strict_types=1);

namespace Modules\Branch\Filament\Panels\Admin\Resources\BranchResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Branch\Models\BranchModel;

class BranchResource extends Resource
{
    protected static ?string $model = BranchModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    protected static ?string $recordTitleAttribute = 'name.en';

    protected static ?string $slug = 'branches';

    public static function form(Form $form): Form
    {
        return BranchFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return BranchsTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return BranchInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBranchesPage::route('/'),
            'create' => Pages\CreateBranchPage::route('/create'),
            'view' => Pages\ViewBranchPage::route('/{record}'),
            'edit' => Pages\EditBranchPage::route('/{record}/edit'),
        ];
    }
}
