@props(['content' => null])

@if ($content)
<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-3xl text-center">
        <h2 class="text-3xl font-bold tracking-tight text-slate-900">
            {{ $content['heading'] ?? '' }}
        </h2>
        @isset($content['subheading'])
            <p class="mt-4 text-lg text-slate-600">
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
