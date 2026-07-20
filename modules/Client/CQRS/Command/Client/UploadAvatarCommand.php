<?php

declare(strict_types=1);

namespace Modules\Client\CQRS\Command\Client;

final readonly class UploadAvatarCommand
{
    public function __construct(
        public string $clientId,
        public string $filePath,
        public ?string $oldAvatarPath,
    ) {}
}
