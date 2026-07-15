<?php

declare(strict_types=1);

namespace Modules\Activation\Filament\Panels\Admin\Resources\ActivationLogResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Activation\Models\ActivationLogModel;

class ActivationLogResource extends Resource
{
    protected static ?string $model = ActivationLogModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'activation-logs';

    public static function form(Form $form): Form
    {
        return ActivationLogFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return ActivationLogsTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ActivationLogInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListActivationLogsPage::route('/'),
            'view' => Pages\ViewActivationLogPage::route('/{record}'),
            'edit' => Pages\EditActivationLogPage::route('/{record}/edit'),
        ];
    }
}
