<?php

declare(strict_types=1);

namespace Modules\Marketing\Http\Requests;

use Illuminate\Validation\Rules\Enum;
use Modules\Core\Enums\Locale;
use Modules\Core\Enums\Universe;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class GetMarketingPageRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'locale' => ['sometimes', 'string', new Enum(Locale::class)],
            'universe' => ['sometimes', 'string', new Enum(Universe::class)],
        ];
    }

    public function getLocale(): string
    {
        return $this->input('locale', app()->getLocale());
    }

    public function getUniverse(): string
    {
        return $this->input('universe', 'all');
    }
}
