<?php

declare(strict_types=1);

namespace Modules\BarberAffiliation\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Barber\Models\BarberModel;
use Modules\BarberAffiliation\Enums\AffiliationStatus;
use Modules\BarberAffiliation\Models\BarberAffiliationModel;
use Modules\Branch\Models\BranchModel;

class BarberAffiliationSeeder extends Seeder
{
    public function run(): void
    {
        $barber = BarberModel::where('email', 'barber@dorak.sy')->first()
            ?? BarberModel::factory()->create(['email' => 'barber@dorak.sy']);

        $branch = BranchModel::where('email', 'branch@dorak.sy')->first()
            ?? BranchModel::factory()->create(['email' => 'branch@dorak.sy']);

        BarberAffiliationModel::create([
            'barber_id' => $barber->id,
            'affiliable_id' => $branch->id,
            'affiliable_type' => 'branch',
            'status' => AffiliationStatus::Accepted,
            'invited_at' => now()->subDays(7),
            'accepted_at' => now()->subDays(6),
            'terminated_at' => null,
        ]);
    }
}
