<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Modules\ClientFaceProfile\CQRS\Command\UploadFaceProfilePhotoCommand;
use Modules\ClientFaceProfile\Handlers\UploadFaceProfilePhotoHandler;
use Modules\ClientFaceProfile\Http\Requests\Client\UploadFaceProfilePhotoRequest;
use Modules\Core\Http\Actions\BaseApiAction;

final class UploadFaceProfilePhotoAction extends BaseApiAction
{
    public function __construct(
        private readonly UploadFaceProfilePhotoHandler $handler,
    ) {}

    public function __invoke(UploadFaceProfilePhotoRequest $request): JsonResponse
    {
        $client = $request->user();

        $file = $request->file('photo');
        $path = $file->store('face-profiles', 'public');
        $imageUrl = Storage::disk('public')->url($path);
        $imageHash = md5($file->get());

        $isPrimary = $request->boolean('is_primary', false);

        $command = new UploadFaceProfilePhotoCommand(
            clientId: $client->id,
            imageUrl: $imageUrl,
            imageHash: $imageHash,
            isPrimary: $isPrimary,
        );

        $profile = $this->handler->handle($command);

        return $this->created(data: [
            'id' => $profile->id,
            'image_url' => $profile->image_url,
            'is_primary' => $profile->is_primary,
            'uploaded_at' => $profile->uploaded_at->toIso8601String(),
        ]);
    }
}
