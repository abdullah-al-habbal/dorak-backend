<?php

declare(strict_types=1);

namespace Modules\Onboarding\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Modules\Core\Database\Seeders\Traits\CopiesSourceImage;

final class OnboardingConfigSeeder extends Seeder
{
    use CopiesSourceImage;

    public function run(): void
    {
        $rows = config('onboarding.seed_onboarding_configs', []);

        if (empty($rows)) {
            return;
        }

        $records = [];

        foreach ($rows as $row) {
            $sourcePath = $row['source_path'];
            unset($row['source_path']);

            $relativePath = $this->copySourceImage(
                sourcePath: $sourcePath,
                basePath: 'onboarding',
                identifier: $row['locale'] ?? 'default',
                fileSuffix: $row['season'] ?? 'default',
                outputFormat: 'webp',
            );

            $records[] = array_merge($row, [
                'id' => (string) Str::uuid(),
                'hero_image_path' => $relativePath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        DB::table('onboarding_configurations')->delete();
        DB::table('onboarding_configurations')->insertOrIgnore($records);
    }
}
