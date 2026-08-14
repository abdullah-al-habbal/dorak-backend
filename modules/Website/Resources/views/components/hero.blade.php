{{-- root/modules/Website/Resources/views/components/hero.blade.php --}}
@props(['content' => null])

@if ($content)
<section class="relative overflow-hidden bg-gradient-hero px-4 py-24 sm:px-6 lg:px-8">
    <div class="absolute inset-0 bg-gradient-to-br from-accent/5 to-transparent"></div>
    <div class="relative mx-auto max-w-4xl text-center">
        <h1 class="text-4xl font-bold tracking-tight text-white sm:text-5xl lg:text-6xl">
            {{ $content['heading'] ?? '' }}
        </h1>
        @isset($content['subheading'])
            <p class="mt-6 text-lg leading-8 text-slate-300">
                {{ $content['subheading'] }}
            </p>
        @endisset
        @isset($content['cta_text'])
            <div class="mt-10">
                <a href="{{ $content['cta_url'] ?? '#' }}"
                   class="rounded-lg bg-accent px-8 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-accent-secondary">
                    {{ $content['cta_text'] }}
                </a>
            </div>
        @endisset
    </div>
</section>
@endif
