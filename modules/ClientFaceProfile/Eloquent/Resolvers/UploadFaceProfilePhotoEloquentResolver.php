<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Eloquent\Resolvers;

use Illuminate\Support\Facades\Storage;
use Modules\ClientFaceProfile\CQRS\Command\UploadFaceProfilePhotoCommand;
use Modules\ClientFaceProfile\Models\ClientFaceProfileModel;

final class UploadFaceProfilePhotoEloquentResolver
{
    public function resolve(UploadFaceProfilePhotoCommand $command): ClientFaceProfileModel
    {
        return ClientFaceProfileModel::create([
            'client_id' => $command->clientId,
            'image_url' => $command->imageUrl,
            'image_hash' => $command->imageHash,
            'is_primary' => $command->isPrimary,
            'uploaded_at' => now(),
        ]);
    }
}
