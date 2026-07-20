<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
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
        $media = $this->handler->handle($request->toCommand($history));

        return $this->created(data: [
            'id' => $media->id,
            'photo_url' => $media->photo_url,
            'photo_type' => $media->photo_type->value,
            'uploaded_at' => $media->uploaded_at->toIso8601String(),
        ]);
    }
}
