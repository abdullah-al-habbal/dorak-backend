<?php

declare(strict_types=1);

namespace Modules\Activation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Activation\Enums\ActivationStatusEnum;
use Modules\Activation\Models\ActivationLogModel;
use Modules\Admin\Models\AdminModel;
use Modules\Barber\Models\BarberModel;
use Modules\Branch\Models\BranchModel;

class ActivationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = AdminModel::first() ?? AdminModel::factory()->create();

        $barber = BarberModel::first();
        if ($barber) {
            ActivationLogModel::create([
                'activable_id' => $barber->id,
                'activable_type' => $barber->getMorphClass(),
                'status' => ActivationStatusEnum::Enabled->value,
                'admin_id' => $admin->id,
                'activated_at' => now(),
            ]);
        }

        $branch = BranchModel::first();
        if ($branch) {
            ActivationLogModel::create([
                'activable_id' => $branch->id,
                'activable_type' => $branch->getMorphClass(),
                'status' => ActivationStatusEnum::Enabled->value,
                'admin_id' => $admin->id,
                'activated_at' => now(),
            ]);
        }
    }
}
