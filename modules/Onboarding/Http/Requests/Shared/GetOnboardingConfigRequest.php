<?php

declare(strict_types=1);

namespace Modules\Onboarding\Http\Requests\Shared;

use Illuminate\Validation\Rules\Enum;
use Modules\Core\Enums\Locale;
use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\Onboarding\Enums\Season;

final class GetOnboardingConfigRequest extends BaseApiFormRequest
{
    protected function prepareForValidation(): void
    {
        if (! $this->has('locale')) {
            $this->merge([
                'locale' => $this->preferredLocale(),
            ]);
        }
    }

    /**
     * @return array<string, array<int, string|object>>
     */
    public function rules(): array
    {
        return [
            'locale' => ['sometimes', 'string', new Enum(Locale::class)],
            'season' => ['sometimes', 'string', new Enum(Season::class)],
        ];
    }

    public function getLocale(): string
    {
        return $this->string('locale', $this->preferredLocale())->toString();
    }

    public function getSeason(): string
    {
        return $this->string('season', Season::fromMonth((int) now()->month)->value)->toString();
    }

    private function preferredLocale(): string
    {
        $preferred = $this->getPreferredLanguage(['en', 'ar']);

        return $preferred ?? 'en';
    }
}
