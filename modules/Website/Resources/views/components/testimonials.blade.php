@props(['content' => null, 'testimonials' => []])

@if ($content && count($testimonials))
<section class="bg-slate-50 px-4 py-20 sm:px-6 lg:px-8">
    <div class="mx-auto max-w-7xl">
        <h2 class="text-center text-3xl font-bold tracking-tight text-slate-900">
            {{ $content['heading'] ?? '' }}
        </h2>

        <div class="mt-16 grid gap-8 md:grid-cols-3">
            @foreach ($testimonials as $testimonial)
                <div class="rounded-xl border border-slate-200 bg-white p-6">
                    <div class="mb-4 flex gap-1">
                        @for ($i = 0; $i < ($testimonial['rating'] ?? 5); $i++)
                            <svg class="h-4 w-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="text-sm leading-6 text-slate-600">"{{ $testimonial['quote'] }}"</p>
                    <div class="mt-4 border-t border-slate-100 pt-4">
                        <p class="text-sm font-semibold text-slate-900">{{ $testimonial['author_name'] }}</p>
                        <p class="text-xs text-slate-500">{{ $testimonial['author_title'] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
