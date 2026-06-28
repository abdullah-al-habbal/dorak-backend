<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Admin\Database\Seeders\AdminSeeder;
use Modules\Barber\Database\Seeders\BarberSeeder;
use Modules\Activation\Database\Seeders\ActivationSeeder;
use Modules\Ban\Database\Seeders\BanSeeder;
use Modules\Language\Database\Seeders\LanguageSeeder;
use Modules\Currency\Database\Seeders\CurrencySeeder;
use Modules\Branch\Database\Seeders\BranchSeeder;
use Modules\Client\Database\Seeders\ClientSeeder;
use Modules\Brand\Database\Seeders\BrandSeeder;
use Modules\Preference\Database\Seeders\PreferenceSeeder;
use Modules\BarberAffiliation\Database\Seeders\BarberAffiliationSeeder;
use Modules\OfferedService\Database\Seeders\OfferedServiceSeeder;
use Modules\Chair\Database\Seeders\ChairSeeder;
use Modules\Booking\Database\Seeders\BookingSeeder;
use Modules\Review\Database\Seeders\ReviewSeeder;
use Modules\JobPosting\Database\Seeders\JobPostingSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            CurrencySeeder::class,
            AdminSeeder::class,
            BarberSeeder::class,
            BranchSeeder::class,
            ActivationSeeder::class,
            ClientSeeder::class,
            BanSeeder::class,
            BrandSeeder::class,
            PreferenceSeeder::class,
            BarberAffiliationSeeder::class,
            OfferedServiceSeeder::class,
            ChairSeeder::class,
            BookingSeeder::class,
            ReviewSeeder::class,
            JobPostingSeeder::class,
        ]);
    }
}
