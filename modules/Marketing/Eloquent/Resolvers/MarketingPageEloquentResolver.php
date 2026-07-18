<?php

declare(strict_types=1);

namespace Modules\Marketing\Eloquent\Resolvers;

use Modules\Marketing\Models\MarketingPageModel;

final class MarketingPageEloquentResolver
{
    public function findBySlug(string $slug): ?MarketingPageModel
    {
        /** @var MarketingPageModel|null */
        return MarketingPageModel::query()->where('slug', $slug)->first();
    }

    public function findBySlugWithSections(string $slug): ?MarketingPageModel
    {
        /** @var MarketingPageModel|null */
        return MarketingPageModel::query()
            ->with('sections')
            ->where('slug', $slug)
            ->first();
    }

    public function findBySlugWithAll(string $slug): ?MarketingPageModel
    {
        /** @var MarketingPageModel|null */
        return MarketingPageModel::query()
            ->with(['sections.testimonials'])
            ->where('slug', $slug)
            ->first();
    }
}
