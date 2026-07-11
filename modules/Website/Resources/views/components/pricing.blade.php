@props(['content' => null])

@if ($content)
<section class="bg-slate-50 px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <h2 class="text-center text-3xl font-bold tracking-tight text-slate-900">
            {{ $content['heading'] ?? '' }}
        </h2>

        @isset($content['plans'])
            <div class="mt-16 grid gap-8 md:grid-cols-2">
                @foreach ($content['plans'] as $plan)
                    <div class="rounded-xl border p-8 {{ ($plan['highlighted'] ?? false) ? 'border-accent bg-white shadow-lg' : 'border-slate-200 bg-white' }}">
                        <h3 class="text-lg font-semibold text-slate-900">{{ $plan['name'] }}</h3>
                        <p class="mt-4 text-3xl font-bold text-slate-900">{{ $plan['price'] }}</p>
                        <ul class="mt-6 space-y-3">
                            @foreach ($plan['features'] as $feature)
                                <li class="flex items-center gap-2 text-sm text-slate-600">
                                    <svg class="h-4 w-4 shrink-0 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                        <a href="{{ $plan['cta'] ?? '#' }}"
                           class="mt-8 block rounded-lg {{ ($plan['highlighted'] ?? false) ? 'bg-accent text-white hover:bg-accent-secondary' : 'border border-slate-200 text-slate-900 hover:bg-slate-50' }} px-6 py-3 text-center text-sm font-semibold shadow-sm transition">
                            {{ $plan['cta'] }}
                        </a>
                    </div>
                @endforeach
            </div>
        @endisset
    </div>
</section>
@endif
