<?php

declare(strict_types=1);

namespace Modules\Marketing\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Modules\Core\Database\Seeders\Traits\BulkSeedable;

final class TestimonialSeeder extends Seeder
{
    use BulkSeedable;

    public function run(): void
    {
        $sectionIds = DB::table('sections')
            ->join('marketing_pages', 'sections.page_id', '=', 'marketing_pages.id')
            ->whereIn('marketing_pages.slug', ['home'])
            ->where('sections.type', 'testimonials')
            ->pluck('sections.id', 'marketing_pages.slug');

        $rows = array_map(function (array $testimonial) use ($sectionIds): array {
            $testimonial['section_id'] = $sectionIds[$testimonial['page_slug']] ?? null;
            unset($testimonial['page_slug'], $testimonial['section_type']);

            return $testimonial;
        }, config('marketing.seed_testimonials'));

        $this->bulkInsertOrIgnore('testimonials', $rows);
    }
}
