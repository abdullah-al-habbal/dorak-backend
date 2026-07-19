<?php

declare(strict_types=1);

namespace Modules\ClientInteraction\Http\Requests\Client;

use Illuminate\Validation\Rule;
use Modules\ClientInteraction\CQRS\Command\AddFavoriteCommand;
use Modules\ClientInteraction\Enums\FavorableTypeEnum;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class AddFavoriteRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'favorable_id' => ['required', 'string'],
            'favorable_type' => ['required', Rule::enum(FavorableTypeEnum::class)],
        ];
    }

    public function toCommand(): AddFavoriteCommand
    {
        return new AddFavoriteCommand(
            clientId: (string) $this->user()->id,
            favorableId: $this->validated('favorable_id'),
            favorableType: FavorableTypeEnum::from($this->validated('favorable_type')),
        );
    }
}
