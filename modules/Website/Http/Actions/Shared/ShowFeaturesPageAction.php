<?php

declare(strict_types=1);

namespace Modules\Website\Http\Actions\Shared;

use Illuminate\Contracts\View\View;
use Modules\Marketing\Handlers\Shared\GetMarketingPageHandler;

final class ShowFeaturesPageAction
{
    public function __construct(
        private readonly GetMarketingPageHandler $handler,
    ) {}

    public function __invoke(): View
    {
        $locale = app()->getLocale();
        $page = $this->handler->handle('features', $locale, 'all');

        return view('website::pages.features', [
            'page' => $page,
            'locale' => $locale,
        ]);
    }
}
