<?php

declare(strict_types=1);

namespace Modules\Admin\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Models\AdminModel;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        AdminModel::factory()->create([
            'name'  => [
                'en' => 'Admin',
                'ar' => 'مدير النظام',
            ],
            'email' => 'admin@dorak.sy',
        ]);
    }
}
