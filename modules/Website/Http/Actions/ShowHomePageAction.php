<?php
declare(strict_types=1);

namespace Modules\Website\Http\Actions;

use Illuminate\Contracts\View\View;
use Modules\Marketing\Handlers\GetMarketingPageHandler;

final class ShowHomePageAction
{
    public function __construct(
        private readonly GetMarketingPageHandler $handler,
    ) {}

    public function __invoke(): View
    {
        $locale = app()->getLocale();
        $page = $this->handler->handle('home', $locale, 'all');

        return view('website::pages.home', [
            'page' => $page,
            'locale' => $locale,
        ]);
    }
}
