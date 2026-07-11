<?php

declare(strict_types=1);

namespace Modules\Branch\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Branch\Models\BranchModel;

class BranchSeeder extends Seeder
{
    public function run(): void
    {
        BranchModel::factory()->create([
            'name' => [
                'en' => 'Branch',
                'ar' => 'فرع',
            ],
            'email' => 'branch@dorak.sy',
        ]);
    }
}
