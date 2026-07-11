<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Branch\Models\BranchModel;
use Modules\Chair\Models\ChairModel;

final class FloorPlanDemoSeeder extends Seeder
{
    public function run(): void
    {
        $branch = BranchModel::query()->firstOrCreate(
            ['email' => 'demo@dorak.sy'],
            [
                'name' => ['en' => 'Demo Salon', 'ar' => 'صالون تجريبي'],
                'password' => bcrypt('password'),
                'status' => 'enabled',
            ],
        );

        $chairs = [
            ['label' => 'Chair 1', 'status' => 'available', 'position_x' => 50, 'position_y' => 100],
            ['label' => 'Chair 2', 'status' => 'available', 'position_x' => 200, 'position_y' => 100],
            ['label' => 'Chair 3', 'status' => 'occupied', 'position_x' => 50, 'position_y' => 300],
            ['label' => 'Chair 4', 'status' => 'available', 'position_x' => 200, 'position_y' => 300],
            ['label' => 'Chair 5', 'status' => 'maintenance', 'position_x' => 350, 'position_y' => 200],
        ];

        foreach ($chairs as $chair) {
            ChairModel::query()->firstOrCreate(
                [
                    'branch_id' => $branch->id,
                    'label' => $chair['label'],
                ],
                [
                    'status' => $chair['status'],
                    'ui_metadata' => [
                        'shape' => 'circle',
                        'position_x' => $chair['position_x'],
                        'position_y' => $chair['position_y'],
                        'width' => 60,
                        'height' => 60,
                        'rotation' => 0,
                    ],
                ],
            );
        }
    }
}
