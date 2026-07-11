<?php

declare(strict_types=1);

namespace Modules\Branch\Filament\Panels\Branch\Resources\ProfileResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Branch\Models\BranchModel;

class ProfileResource extends Resource
{
    protected static ?string $model = BranchModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';

    protected static ?string $recordTitleAttribute = 'name.en';

    protected static ?string $slug = 'profile';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('id', filament()->auth()->id());
    }

    public static function form(Form $form): Form
    {
        return ProfileFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return ProfilesTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return ProfileInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProfilesPage::route('/'),
            'edit' => Pages\EditProfilePage::route('/{record}/edit'),
            'view' => Pages\ViewProfilePage::route('/{record}'),
        ];
    }
}
