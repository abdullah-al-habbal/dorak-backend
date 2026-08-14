<?php

declare(strict_types=1);

namespace Modules\Marketing\Http\Requests\Shared;

use Illuminate\Validation\Rules\Enum;
use Modules\Core\Enums\Universe;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class GetMarketingPageRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'universe' => ['sometimes', 'string', new Enum(Universe::class)],
        ];
    }

    public function getLocale(): string
    {
        return $this->getPreferredLanguage(['en', 'ar']) ?? (string) app()->getLocale();
    }

    public function getUniverse(): string
    {
        return $this->input('universe', 'all');
    }
}
