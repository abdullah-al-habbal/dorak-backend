<?php

declare(strict_types=1);

namespace Modules\Chair\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Branch\Models\BranchModel;
use Modules\Chair\Enums\ChairStatus;
use Modules\Chair\Models\ChairModel;

class ChairSeeder extends Seeder
{
    public function run(): void
    {
        $branch = BranchModel::where('email', 'branch@dorak.sy')->first()
            ?? BranchModel::factory()->create(['email' => 'branch@dorak.sy']);

        $positions = [
            ['x' => 50, 'y' => 100],
            ['x' => 200, 'y' => 100],
            ['x' => 50, 'y' => 300],
            ['x' => 200, 'y' => 300],
        ];

        foreach ($positions as $i => $pos) {
            $status = $i === 3 ? ChairStatus::Maintenance : ChairStatus::Available;
            ChairModel::create([
                'branch_id' => $branch->id,
                'barber_id' => null,
                'label' => (string) ($i + 1),
                'ui_metadata' => [
                    'shape' => 'rectangle',
                    'position_x' => $pos['x'],
                    'position_y' => $pos['y'],
                    'width' => 60,
                    'height' => 60,
                    'rotation' => 0,
                ],
                'status' => $status->value,
            ]);
        }
    }
}
