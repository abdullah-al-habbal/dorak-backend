<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Handlers;

use Modules\ClientFaceProfile\CQRS\Command\UploadFaceProfilePhotoCommand;
use Modules\ClientFaceProfile\Eloquent\Resolvers\UploadFaceProfilePhotoEloquentResolver;
use Modules\ClientFaceProfile\Models\ClientFaceProfileModel;

final class UploadFaceProfilePhotoHandler
{
    public function __construct(
        private readonly UploadFaceProfilePhotoEloquentResolver $resolver,
    ) {}

    public function handle(UploadFaceProfilePhotoCommand $command): ClientFaceProfileModel
    {
        return $this->resolver->resolve($command);
    }
}
