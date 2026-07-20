<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Modules\Client\CQRS\Command\Client\UploadAvatarCommand;
use Modules\Client\Eloquent\Resolvers\Client\UploadAvatarEloquentResolver;
use Modules\Client\Models\ClientModel;

final class UploadAvatarHandler
{
    public function __construct(
        private readonly UploadAvatarEloquentResolver $resolver,
    ) {}

    public function handle(UploadAvatarCommand $command): ClientModel
    {
        return $this->resolver->resolve($command);
    }
}
