<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\ClientHistory\CQRS\Command\AttachHistoryMediaCommand;
use Modules\ClientHistory\Enums\HistoryMediaType;
use Modules\ClientHistory\Handlers\AttachHistoryMediaHandler;
use Modules\ClientHistory\Http\Requests\Client\AttachHistoryMediaRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class AttachHistoryMediaAction extends BaseApiAction
{
    public function __construct(
        private readonly AttachHistoryMediaHandler $handler,
    ) {}

    public function __invoke(AttachHistoryMediaRequest $request, string $history): JsonResponse
    {
        $photoUrl = (string) $request->input('photo_url');
        $photoType = $request->enum('photo_type', HistoryMediaType::class);

        $command = new AttachHistoryMediaCommand(
            historyId: $history,
            photoUrl: $photoUrl,
            photoType: $photoType,
        );

        $media = $this->handler->handle($command);

        return $this->created(data: [
            'id' => $media->id,
            'photo_url' => $media->photo_url,
            'photo_type' => $media->photo_type->value,
            'uploaded_at' => $media->uploaded_at->toIso8601String(),
        ]);
    }
}
