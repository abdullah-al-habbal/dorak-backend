<?php

declare(strict_types=1);

namespace Modules\Booking\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Barber\Models\BarberModel;
use Modules\Booking\Enums\BookingStatus;
use Modules\Booking\Models\BookingModel;
use Modules\Chair\Models\ChairModel;
use Modules\Client\Models\ClientModel;
use Modules\OfferedService\Models\OfferedServiceModel;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $client = ClientModel::where('email', 'admin@dorak.sy')->first()
            ?? ClientModel::factory()->create(['email' => 'admin@dorak.sy']);

        $barber = BarberModel::where('email', 'barber@dorak.sy')->first()
            ?? BarberModel::factory()->create(['email' => 'barber@dorak.sy']);

        $chair = ChairModel::first()
            ?? ChairModel::factory()->create();

        $service = OfferedServiceModel::first()
            ?? OfferedServiceModel::factory()->create();

        $booking = BookingModel::create([
            'client_id' => $client->id,
            'chair_id' => $chair->id,
            'barber_id' => $barber->id,
            'time_slot' => now()->addDay()->setHour(10)->setMinute(0),
            'status' => BookingStatus::Completed->value,
            'at_home_location' => null,
        ]);

        $booking->services()->attach($service->id);
    }
}
