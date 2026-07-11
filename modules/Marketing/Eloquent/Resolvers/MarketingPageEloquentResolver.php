<?php

declare(strict_types=1);

namespace Modules\Marketing\Eloquent\Resolvers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Eloquent\Resolvers\BaseEloquentResolver;
use Modules\Marketing\Models\MarketingPageModel;

final class MarketingPageEloquentResolver extends BaseEloquentResolver
{
    // use __construct
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

    public function resolve(object $payload): Model|Collection|array|null
    {
        return $this->findBySlugWithAll($payload->slug ?? '');
    }
}
