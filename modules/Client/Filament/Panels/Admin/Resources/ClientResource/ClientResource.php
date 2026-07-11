<?php

declare(strict_types=1);

namespace Modules\Client\Filament\Panels\Admin\Resources\ClientResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Client\Models\ClientModel;

class ClientResource extends Resource
{
    protected static ?string $model = ClientModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?string $recordTitleAttribute = 'name.en';

    protected static ?string $slug = 'clients';

    public static function form(Form $form): Form
    {
        return ClientFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return ClientsTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ClientInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClientsPage::route('/'),
            'create' => Pages\CreateClientPage::route('/create'),
            'view' => Pages\ViewClientPage::route('/{record}'),
            'edit' => Pages\EditClientPage::route('/{record}/edit'),
        ];
    }
}
