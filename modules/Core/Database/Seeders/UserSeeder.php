<?php

declare(strict_types=1);

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\UserModel;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        UserModel::factory()->create([
            'name'  => [
                'en' => 'Admin',
                'ar' => 'مدير النظام',
            ],
            'email' => 'admin@dorak.sy',
        ]);
    }
}
