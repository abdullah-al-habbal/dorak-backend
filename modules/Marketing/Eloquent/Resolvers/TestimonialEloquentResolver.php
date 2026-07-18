<?php

declare(strict_types=1);

namespace Modules\Marketing\Eloquent\Resolvers;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

use Modules\Marketing\Models\TestimonialModel;

final class TestimonialEloquentResolver
{
    public function findBySectionId(string $sectionId): Collection
    {
        return TestimonialModel::query()
            ->where('section_id', $sectionId)
            ->get();
    }

    public function resolve(object $payload): Model|Collection|array|null
    {
        return $this->findBySectionId($payload->sectionId ?? '');
    }
}
