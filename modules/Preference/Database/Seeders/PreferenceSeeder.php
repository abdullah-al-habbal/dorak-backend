<?php

declare(strict_types=1);

namespace Modules\Preference\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Preference\Models\PreferenceModel;

class PreferenceSeeder extends Seeder
{
    public function run(): void
    {
        PreferenceModel::create([
            'preferenceable_id' => null,
            'preferenceable_type' => null,
            'preferred_language' => 'ar',
            'notification_enabled' => true,
            'display_currency_id' => null,
            'theme' => 'light',
            'price_display_mode' => 'single',
        ]);
    }
}
