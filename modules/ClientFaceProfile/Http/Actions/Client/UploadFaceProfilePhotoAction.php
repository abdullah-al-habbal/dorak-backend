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
        $file = $request->file('photo');
        $path = $file->store('face-profiles', 'public');
        $imageUrl = Storage::disk('public')->url($path);
        $imageHash = md5($file->get());

        $command = new UploadFaceProfilePhotoCommand(
            clientId: (string) $request->user()->id,
            imageUrl: $imageUrl,
            imageHash: $imageHash,
            isPrimary: $request->boolean('is_primary', false),
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
