<?php

declare(strict_types=1);

namespace Modules\Onboarding\Http\Requests\Shared;

use Illuminate\Validation\Rules\Enum;
use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\Onboarding\Enums\Season;

final class GetOnboardingConfigRequest extends BaseApiFormRequest
{
    /**
     * @return array<string, array<int, string|object>>
     */
    public function rules(): array
    {
        return [
            'season' => ['sometimes', 'string', new Enum(Season::class)],
        ];
    }

    public function getLocale(): string
    {
        return $this->getPreferredLanguage(['en', 'ar']) ?? 'en';
    }

    public function getSeason(): string
    {
        return $this->string('season', Season::fromMonth((int) now()->month)->value)->toString();
    }
}
