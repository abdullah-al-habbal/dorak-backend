<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Requests\Client;

use Illuminate\Validation\Rule;
use Modules\Client\Enums\UniverseEnum;
use Modules\ClientInteraction\CQRS\Command\UpdateDiscoveryPreferenceCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UpdateDiscoveryPreferenceRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'preferred_universe' => ['sometimes', Rule::enum(UniverseEnum::class)],
            'default_radius' => ['sometimes', 'numeric', 'min:1', 'max:1000'],
            'hidden_brand_ids' => ['sometimes', 'array'],
            'hidden_brand_ids.*' => ['string'],
            'show_unavailable' => ['sometimes', 'boolean'],
        ];
    }

    public function toCommand(): UpdateDiscoveryPreferenceCommand
    {
        $data = $this->validated();

        return new UpdateDiscoveryPreferenceCommand(
            clientId: (string) $this->user()->id,
            preferredUniverse: isset($data['preferred_universe']) ? UniverseEnum::from($data['preferred_universe']) : null,
            defaultRadius: isset($data['default_radius']) ? (float) $data['default_radius'] : null,
            hiddenBrandIds: $data['hidden_brand_ids'] ?? null,
            showUnavailable: $data['show_unavailable'] ?? null,
        );
    }
}
