<?php

declare(strict_types=1);

namespace Modules\Marketing\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Database\Seeders\Traits\BulkSeedable;

final class MarketingPageSeeder extends Seeder
{
    use BulkSeedable;

    public function run(): void
    {
        $this->bulkInsertOrIgnore('marketing_pages', config('marketing.seed_pages'));
    }
}
