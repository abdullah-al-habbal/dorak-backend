<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\CQRS\Command;

final readonly class UploadFaceProfilePhotoCommand
{
    public function __construct(
        public string $clientId,
        public string $imageUrl,
        public string $imageHash,
        public bool $isPrimary,
    ) {}
}
