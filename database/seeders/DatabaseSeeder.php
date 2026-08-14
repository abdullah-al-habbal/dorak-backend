<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Activation\Database\Seeders\ActivationSeeder;
use Modules\Admin\Database\Seeders\AdminSeeder;
use Modules\Ban\Database\Seeders\BanSeeder;
use Modules\Barber\Database\Seeders\BarberSeeder;
use Modules\BarberAffiliation\Database\Seeders\BarberAffiliationSeeder;
use Modules\Booking\Database\Seeders\BookingSeeder;
use Modules\Branch\Database\Seeders\BranchSeeder;
use Modules\Brand\Database\Seeders\BrandSeeder;
use Modules\Chair\Database\Seeders\ChairSeeder;
use Modules\Client\Database\Seeders\ClientSeeder;
use Modules\Currency\Database\Seeders\CurrencySeeder;
use Modules\JobPosting\Database\Seeders\JobPostingSeeder;
use Modules\Language\Database\Seeders\LanguageSeeder;
use Modules\Marketing\Database\Seeders\MarketingPageSeeder;
use Modules\Marketing\Database\Seeders\SectionSeeder;
use Modules\Marketing\Database\Seeders\TestimonialSeeder;
use Modules\OfferedService\Database\Seeders\OfferedServiceSeeder;
use Modules\Onboarding\Database\Seeders\OnboardingConfigSeeder;
use Modules\Preference\Database\Seeders\PreferenceSeeder;
use Modules\Review\Database\Seeders\ReviewSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            LanguageSeeder::class,
            CurrencySeeder::class,
            OnboardingConfigSeeder::class,
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
            MarketingPageSeeder::class,
            SectionSeeder::class,
            TestimonialSeeder::class,
            FloorPlanDemoSeeder::class,
        ]);
    }
}
