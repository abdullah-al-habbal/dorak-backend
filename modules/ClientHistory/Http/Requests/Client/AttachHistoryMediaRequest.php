<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Requests\Client;

use Illuminate\Validation\Rule;
use Modules\ClientHistory\CQRS\Command\AttachHistoryMediaCommand;
use Modules\ClientHistory\Enums\HistoryMediaType;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class AttachHistoryMediaRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'photo_url' => ['required', 'string', 'max:2048'],
            'photo_type' => ['required', Rule::enum(HistoryMediaType::class)],
        ];
    }

    public function toCommand(string $historyId): AttachHistoryMediaCommand
    {
        return new AttachHistoryMediaCommand(
            historyId: $historyId,
            photoUrl: (string) $this->validated('photo_url'),
            photoType: HistoryMediaType::from($this->validated('photo_type')),
        );
    }
}
