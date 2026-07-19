<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Eloquent\Resolvers;

use Modules\ClientFaceProfile\CQRS\Command\UploadFaceProfilePhotoCommand;
use Modules\ClientFaceProfile\Models\ClientFaceProfileModel;

final class UploadFaceProfilePhotoEloquentResolver
{
    public function resolve(UploadFaceProfilePhotoCommand $command): ClientFaceProfileModel
    {
        if ($command->isPrimary) {
            ClientFaceProfileModel::where('client_id', $command->clientId)
                ->where('is_primary', true)
                ->update(['is_primary' => false]);
        }

        return ClientFaceProfileModel::create([
            'client_id' => $command->clientId,
            'image_url' => $command->imageUrl,
            'image_hash' => $command->imageHash,
            'is_primary' => $command->isPrimary,
            'uploaded_at' => now(),
        ]);
    }
}
