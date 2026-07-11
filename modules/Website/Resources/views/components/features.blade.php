@props(['content' => null])

@if ($content)
<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <h2 class="text-center text-3xl font-bold tracking-tight text-slate-900">
            {{ $content['heading'] ?? '' }}
        </h2>

        @isset($content['features'])
            <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($content['features'] as $feature)
                    <div class="rounded-xl border border-slate-200 p-6 transition hover:border-accent/30 hover:shadow-sm">
                        <div class="mb-4 flex h-10 w-10 items-center justify-center rounded-lg bg-accent/10 text-accent">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900">{{ $feature['title'] }}</h3>
                        <p class="mt-2 text-sm leading-6 text-slate-600">{{ $feature['description'] }}</p>
                    </div>
                @endforeach
            </div>
        @endisset
    </div>
</section>
@endif
