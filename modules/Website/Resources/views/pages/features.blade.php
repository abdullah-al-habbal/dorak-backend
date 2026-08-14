{{-- root/modules/Website/Resources/views/pages/features.blade.php --}}
<x-website::layouts.app :page="$page" :locale="$locale">
    @php
        $sections = $page['sections'] ?? [];
        $heroSection = collect($sections)->firstWhere('type', 'hero');
        $featureSection = collect($sections)->firstWhere('type', 'feature_list');
    @endphp

    <x-website::hero :content="$heroSection['content'] ?? null" />
    <x-website::features :content="$featureSection['content'] ?? null" />
</x-website::layouts.app>
