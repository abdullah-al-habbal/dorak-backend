@props([])

<div class="flex overflow-hidden rounded-lg border border-slate-200 text-xs font-medium">
    <button @click="toggleUniverse('neutral')"
            :class="universe === 'neutral' ? 'bg-accent text-white' : 'bg-white text-slate-600 hover:bg-slate-50'"
            class="px-3 py-1.5 transition">
        {{ __('general') }}
    </button>
    <button @click="toggleUniverse('men')"
            :class="universe === 'men' ? 'bg-accent text-white' : 'bg-white text-slate-600 hover:bg-slate-50'"
            class="border-x border-slate-200 px-3 py-1.5 transition">
        {{ __('men') }}
    </button>
    <button @click="toggleUniverse('women')"
            :class="universe === 'women' ? 'bg-accent text-white' : 'bg-white text-slate-600 hover:bg-slate-50'"
            class="px-3 py-1.5 transition">
        {{ __('women') }}
    </button>
</div>
