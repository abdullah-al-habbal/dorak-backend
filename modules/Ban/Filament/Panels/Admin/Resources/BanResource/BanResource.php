<?php

declare(strict_types=1);

namespace Modules\Ban\Filament\Panels\Admin\Resources\BanResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Ban\Models\BanModel;

class BanResource extends Resource
{
    protected static ?string $model = BanModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-no-symbol';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'bans';

    public static function form(Form $form): Form
    {
        return BanFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return BansTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return BanInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBansPage::route('/'),
            'create' => Pages\CreateBanPage::route('/create'),
            'view' => Pages\ViewBanPage::route('/{record}'),
            'edit' => Pages\EditBanPage::route('/{record}/edit'),
        ];
    }
}
