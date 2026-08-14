@props([])

<footer class="border-t border-slate-200 bg-white px-4 py-12 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <div class="flex flex-col items-center justify-between gap-6 md:flex-row">
            <p class="text-sm text-slate-500">
                &copy; {{ date('Y') }} {{ __('website::brand.name') }}. {{ __('website::footer.rights') }}
            </p>
            <div class="flex gap-6">
                <a href="/{{ app()->getLocale() }}/features" class="text-xs text-slate-500 transition hover:text-accent">
                    {{ __('website::footer.features') }}
                </a>
                <a href="/{{ app()->getLocale() }}/pricing" class="text-xs text-slate-500 transition hover:text-accent">
                    {{ __('website::footer.pricing') }}
                </a>
            </div>
        </div>
    </div>
</footer>
