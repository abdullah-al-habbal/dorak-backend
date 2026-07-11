<?php

declare(strict_types=1);

namespace Modules\Ban\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\AdminModel;
use Modules\Ban\Models\BanModel;
use Modules\Client\Models\ClientModel;

class BanSeeder extends Seeder
{
    public function run(): void
    {
        $admin = AdminModel::first() ?? AdminModel::factory()->create();
        $client = ClientModel::first() ?? ClientModel::factory()->create();

        BanModel::create([
            'bannable_id' => $client->id,
            'bannable_type' => 'client',
            'reason' => 'Demo ban — repeated no-shows',
            'banned_from' => now()->subDay(),
            'banned_until' => now()->addDays(7),
            'admin_id' => $admin->id,
        ]);
    }
}
