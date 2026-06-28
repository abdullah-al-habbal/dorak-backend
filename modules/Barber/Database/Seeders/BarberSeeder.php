<?php

declare(strict_types=1);

namespace Modules\Barber\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Barber\Models\BarberModel;

class BarberSeeder extends Seeder
{
    public function run(): void
    {
        BarberModel::factory()->create([
            'name'  => [
                'en' => 'Barber',
                'ar' => 'حلاق',
            ],
            'email' => 'barber@dorak.sy',
        ]);
    }
}
