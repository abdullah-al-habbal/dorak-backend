@props(['content' => null])

@if ($content)
<section class="px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-5xl">
        <h2 class="text-center text-3xl font-bold tracking-tight text-slate-900">
            {{ $content['heading'] ?? '' }}
        </h2>
        @isset($content['description'])
            <p class="mt-4 text-center text-lg text-slate-600">
                {{ $content['description'] }}
            </p>
        @endisset

        <div class="mt-12" x-data="floorPlanDemo()" x-init="init()">
            <div x-show="loading" class="flex items-center justify-center py-32">
                <div class="h-8 w-8 animate-spin rounded-full border-4 border-slate-200 border-t-accent"></div>
            </div>

            <div x-show="!loading && floorPlan" x-cloak
                 class="relative mx-auto rounded-xl border border-slate-200 bg-slate-50"
                 :style="`width: ${floorPlan.canvas.width}px; height: ${floorPlan.canvas.height}px; max-width: 100%;`">

                <template x-for="chair in floorPlan.chairs" :key="chair.id">
                    <div @click="selectedChair = selectedChair?.id === chair.id ? null : chair"
                         class="absolute flex cursor-pointer items-center justify-center rounded-full border-2 text-xs font-bold shadow-sm transition hover:scale-110"
                         :style="`left: ${chair.ui_metadata.position_x}px; top: ${chair.ui_metadata.position_y}px; width: ${chair.ui_metadata.width}px; height: ${chair.ui_metadata.height}px; background: ${statusColor(chair.status)}; border-color: ${statusBorder(chair.status)}`"
                         :class="{ 'ring-2 ring-accent ring-offset-2': selectedChair?.id === chair.id }">
                        <span x-text="chair.label" class="text-white drop-shadow-sm"></span>
                    </div>
                </template>
            </div>

            <div x-show="selectedChair" x-cloak
                 class="mx-auto mt-6 max-w-md rounded-lg border border-slate-200 bg-white p-4 text-center shadow-sm">
                <p class="text-sm font-medium text-slate-900" x-text="selectedChair?.label"></p>
                <p class="text-xs text-slate-500">
                    <span x-text="selectedChair?.status"></span>
                </p>
            </div>

            <div x-show="!loading && !floorPlan" x-cloak class="py-20 text-center text-sm text-slate-400">
                {{ __('website::floor_plan.unavailable') }}
            </div>
        </div>
    </div>
</section>
@endif
