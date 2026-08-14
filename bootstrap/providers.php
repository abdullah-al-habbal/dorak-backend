<?php

// bootstrap/providers.php
declare(strict_types=1);

use Illuminate\Filesystem\FilesystemServiceProvider;
use Illuminate\Hashing\HashServiceProvider;
use Illuminate\View\ViewServiceProvider;
use Modules\Activation\Providers\ActivationServiceProvider;
use Modules\Admin\Providers\AdminServiceProvider;
use Modules\Ban\Providers\BanServiceProvider;
use Modules\Barber\Providers\BarberServiceProvider;
use Modules\BarberAffiliation\Providers\BarberAffiliationServiceProvider;
use Modules\Booking\Providers\BookingServiceProvider;
use Modules\Branch\Providers\BranchServiceProvider;
use Modules\Brand\Providers\BrandServiceProvider;
use Modules\Chair\Providers\ChairServiceProvider;
use Modules\Client\Providers\ClientServiceProvider;
use Modules\ClientFaceProfile\Providers\ClientFaceProfileServiceProvider;
use Modules\ClientHistory\Providers\ClientHistoryServiceProvider;
use Modules\ClientInteraction\Providers\ClientInteractionServiceProvider;
use Modules\ClientRecommendation\Providers\ClientRecommendationServiceProvider;
use Modules\Core\Providers\ApplicationServiceProvider;
use Modules\Core\Providers\Filament\AdminPanelProvider;
use Modules\Core\Providers\Filament\BarberPanelProvider;
use Modules\Core\Providers\Filament\BranchPanelProvider;
use Modules\Currency\Providers\CurrencyServiceProvider;
use Modules\Explore\Providers\ExploreServiceProvider;
use Modules\JobPosting\Providers\JobPostingServiceProvider;
use Modules\Language\Providers\LanguageServiceProvider;
use Modules\Marketing\Providers\MarketingServiceProvider;
use Modules\OfferedService\Providers\OfferedServiceServiceProvider;
use Modules\Onboarding\Providers\OnboardingServiceProvider;
use Modules\Preference\Providers\PreferenceServiceProvider;
use Modules\Review\Providers\ReviewServiceProvider;
use Modules\ServiceCatalog\Providers\ServiceCatalogServiceProvider;
use Modules\Website\Providers\WebsiteServiceProvider;

return [
    FilesystemServiceProvider::class,
    ViewServiceProvider::class,
    HashServiceProvider::class,
    LanguageServiceProvider::class,
    CurrencyServiceProvider::class,
    ActivationServiceProvider::class,
    BanServiceProvider::class,
    ApplicationServiceProvider::class,
    AdminPanelProvider::class,
    BarberPanelProvider::class,
    BranchPanelProvider::class,
    AdminServiceProvider::class,
    BarberServiceProvider::class,
    BranchServiceProvider::class,
    ClientServiceProvider::class,
    ClientFaceProfileServiceProvider::class,
    ClientHistoryServiceProvider::class,
    ClientInteractionServiceProvider::class,
    ClientRecommendationServiceProvider::class,
    BrandServiceProvider::class,
    PreferenceServiceProvider::class,
    BarberAffiliationServiceProvider::class,
    OfferedServiceServiceProvider::class,
    OnboardingServiceProvider::class,
    ChairServiceProvider::class,
    BookingServiceProvider::class,
    ReviewServiceProvider::class,
    ServiceCatalogServiceProvider::class,
    JobPostingServiceProvider::class,
    MarketingServiceProvider::class,
    WebsiteServiceProvider::class,
    ExploreServiceProvider::class,
];
