<?php

declare(strict_types=1);

namespace Modules\Client\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Client\Models\ClientModel;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        ClientModel::factory()->create([
            'name' => [
                'en' => 'Admin',
                'ar' => 'مدير النظام',
            ],
            'email' => 'admin@dorak.sy',
        ]);
    }
}
