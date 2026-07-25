<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Barber\Resources\BookingResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Modules\Booking\Models\BookingModel;

class BookingResource extends Resource
{
    protected static ?string $model = BookingModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'bookings';

    protected static ?string $navigationLabel = 'Bookings';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function form(Form $form): Form
    {
        return BookingFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return BookingTable::make($table);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return BookingInfolistSchema::make($infolist);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookingsPage::route('/'),
            'edit' => Pages\EditBookingPage::route('/{record}/edit'),
            'view' => Pages\ViewBookingPage::route('/{record}'),
        ];
    }
}
