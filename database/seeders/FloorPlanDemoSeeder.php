<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Branch\Enums\BranchStatusEnum;
use Modules\Branch\Models\BranchModel;
use Modules\Chair\Enums\ChairStatus;
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
                'status' => BranchStatusEnum::Enabled->value,
            ],
        );

        $chairs = [
            ['label' => 'Chair 1', 'status' => ChairStatus::Available, 'position_x' => 50, 'position_y' => 100],
            ['label' => 'Chair 2', 'status' => ChairStatus::Available, 'position_x' => 200, 'position_y' => 100],
            ['label' => 'Chair 3', 'status' => ChairStatus::Occupied, 'position_x' => 50, 'position_y' => 300],
            ['label' => 'Chair 4', 'status' => ChairStatus::Available, 'position_x' => 200, 'position_y' => 300],
            ['label' => 'Chair 5', 'status' => ChairStatus::Maintenance, 'position_x' => 350, 'position_y' => 200],
        ];

        $chairData = array_map(function ($chair) use ($branch) {
            return [
                'branch_id' => $branch->id,
                'label' => $chair['label'],
                'status' => $chair['status']->value,
                'ui_metadata' => [
                    'shape' => 'circle',
                    'position_x' => $chair['position_x'],
                    'position_y' => $chair['position_y'],
                    'width' => 60,
                    'height' => 60,
                    'rotation' => 0,
                ],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }, $chairs);

        ChairModel::query()->upsert(
            $chairData,
            ['branch_id', 'label'],
            ['status', 'ui_metadata', 'updated_at'],
        );
    }
}
