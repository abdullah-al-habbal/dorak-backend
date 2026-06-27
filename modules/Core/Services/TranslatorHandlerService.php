<?php
declare(strict_types=1);

namespace Modules\Core\Services;

use Illuminate\Contracts\Translation\Translator;

final class TranslatorHandlerService
{
    public function __construct(
        private readonly Translator $translator
    ) {}

    public function translate(string $key, array $replace = [], ?string $locale = null): string
    {
        return $this->translator->get($key, $replace, $locale) ?: $key;
    }
}
