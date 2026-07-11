<?php

declare(strict_types=1);

namespace Modules\Language\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Language\Models\LanguageModel;

class LanguageSeeder extends Seeder
{
    public function run(): void
    {
        LanguageModel::create([
            'code' => 'en',
            'name' => ['en' => 'English', 'ar' => 'الإنجليزية'],
            'direction' => 'ltr',
            'is_default' => true,
        ]);

        LanguageModel::create([
            'code' => 'ar',
            'name' => ['en' => 'Arabic', 'ar' => 'العربية'],
            'direction' => 'rtl',
            'is_default' => false,
        ]);
    }
}
