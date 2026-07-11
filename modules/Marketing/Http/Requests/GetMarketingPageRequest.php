<?php
declare(strict_types=1);

namespace Modules\Marketing\Http\Requests;

use Modules\Core\Http\Requests\BaseApiFormRequest;

final class GetMarketingPageRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'locale' => ['sometimes', 'string', 'in:ar,en'],
            'universe' => ['sometimes', 'string', 'in:all,men,women'],
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
