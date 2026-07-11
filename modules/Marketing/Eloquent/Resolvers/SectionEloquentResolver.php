<?php

declare(strict_types=1);

namespace Modules\Marketing\Eloquent\Resolvers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Eloquent\Resolvers\BaseEloquentResolver;
use Modules\Marketing\Models\SectionModel;

final class SectionEloquentResolver extends BaseEloquentResolver
{
    // use __construct
    public function findByPageId(string $pageId): Collection
    {
        return SectionModel::query()
            ->where('page_id', $pageId)
            ->orderBy('sort_order')
            ->get();
    }

    public function findByPageIdAndUniverse(string $pageId, string $universe): Collection
    {
        return SectionModel::query()
            ->where('page_id', $pageId)
            ->whereIn('universe_visibility', ['all', $universe])
            ->orderBy('sort_order')
            ->get();
    }

    public function findByPageIdWithTestimonials(string $pageId): Collection
    {
        return SectionModel::query()
            ->with('testimonials')
            ->where('page_id', $pageId)
            ->orderBy('sort_order')
            ->get();
    }

    public function resolve(object $payload): Model|Collection|array|null
    {
        return $this->findByPageId($payload->pageId ?? '');
    }
}
