{{-- dorak-backend/modules/Website/Resources/views/components/navbar.blade.php --}}
@props([])

<nav class="sticky top-0 z-50 border-b border-slate-200/80 bg-white/95 backdrop-blur-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
        <a href="/" class="text-2xl font-bold tracking-tight text-accent">
            {{ __('website::brand.name') }}
        </a>

        <div class="hidden items-center gap-8 md:flex">
            <a href="/features" class="text-sm font-medium text-slate-600 transition hover:text-accent">
                {{ __('website::navbar.features') }}
            </a>
            <a href="/pricing" class="text-sm font-medium text-slate-600 transition hover:text-accent">
                {{ __('website::navbar.pricing') }}
            </a>

            <x-website::universe-toggle />
        </div>

        <button @click="mobileOpen = !mobileOpen" class="flex items-center gap-2 md:hidden">
            <span class="text-sm font-medium text-slate-600">{{ __('website::navbar.menu') }}</span>
            <svg class="h-5 w-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
    </div>

    <div x-show="mobileOpen" x-cloak class="border-t border-slate-200 px-4 py-4 md:hidden">
        <div class="flex flex-col gap-4">
            <a href="/features" class="text-sm font-medium text-slate-600">{{ __('website::navbar.features') }}</a>
            <a href="/pricing" class="text-sm font-medium text-slate-600">{{ __('website::navbar.pricing') }}</a>
            <x-website::universe-toggle />
        </div>
    </div>
</nav>
