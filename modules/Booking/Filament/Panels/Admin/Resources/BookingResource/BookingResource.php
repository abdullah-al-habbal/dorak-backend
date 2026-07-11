<?php

declare(strict_types=1);

namespace Modules\Booking\Filament\Panels\Admin\Resources\BookingResource;

use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Modules\Booking\Models\BookingModel;

class BookingResource extends Resource
{
    protected static ?string $model = BookingModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $slug = 'bookings';

    public static function form(Form $form): Form
    {
        return BookingFormSchema::make($form);
    }

    public static function table(Table $table): Table
    {
        return BookingsTable::make($table);
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
            'view' => Pages\ViewBookingPage::route('/{record}'),
            'edit' => Pages\EditBookingPage::route('/{record}/edit'),
        ];
    }
}
