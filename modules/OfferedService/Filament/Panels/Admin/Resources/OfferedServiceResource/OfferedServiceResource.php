<?php
declare(strict_types=1);

namespace Modules\OfferedService\Filament\Panels\Admin\Resources\OfferedServiceResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\OfferedService\Models\OfferedServiceModel;

class OfferedServiceResource extends Resource
{
    protected static ?string $model = OfferedServiceModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-scissors';

    protected static ?string $recordTitleAttribute = 'name.en';

    protected static ?string $slug = 'offered-services';

    public static function form(Form $form): Form
    {
        return OfferedServiceFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return OfferedServicesTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return OfferedServiceInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListOfferedServicesPage::route('/'),
            'create' => Pages\CreateOfferedServicePage::route('/create'),
            'view'   => Pages\ViewOfferedServicePage::route('/{record}'),
            'edit'   => Pages\EditOfferedServicePage::route('/{record}/edit'),
        ];
    }
}
