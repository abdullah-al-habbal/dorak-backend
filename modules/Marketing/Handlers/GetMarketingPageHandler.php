<?php

declare(strict_types=1);

namespace Modules\Marketing\Handlers;

use Modules\Marketing\Eloquent\Resolvers\MarketingPageEloquentResolver;
use Modules\Marketing\Eloquent\Resolvers\SectionEloquentResolver;

final class GetMarketingPageHandler
{
    public function __construct(
        private readonly MarketingPageEloquentResolver $pageResolver,
        private readonly SectionEloquentResolver $sectionResolver,
    ) {}

    public function handle(string $slug, string $locale, string $universe): ?array
    {
        $page = $this->pageResolver->findBySlug($slug);

        if ($page === null) {
            return null;
        }

        $sections = $this->sectionResolver->findByPageIdAndUniverse($page->id, $universe);

        return [
            'page' => [
                'id' => $page->id,
                'slug' => $page->slug,
                'title' => $page->getTranslation('title', $locale),
                'meta_description' => $page->getTranslation('meta_description', $locale, false),
            ],
            'sections' => $sections->map(function (mixed $section) use ($locale): array {
                $data = [
                    'id' => $section->id,
                    'type' => $section->type,
                    'sort_order' => $section->sort_order,
                    'universe_visibility' => $section->universe_visibility,
                    'content' => $section->getTranslation('content', $locale),
                ];

                if ($section->type === 'testimonials') {
                    $section->load('testimonials');
                    $data['testimonials'] = $section->testimonials->map(fn (mixed $t): array => [
                        'id' => $t->id,
                        'author_name' => $t->author_name,
                        'author_title' => $t->author_title,
                        'quote' => $t->getTranslation('quote', $locale),
                        'rating' => $t->rating,
                    ])->toArray();
                }

                return $data;
            })->toArray(),
        ];
    }
}
