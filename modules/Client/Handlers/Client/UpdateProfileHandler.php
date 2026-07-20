<?php

declare(strict_types=1);

namespace Modules\Client\Handlers\Client;

use Modules\Client\CQRS\Command\Client\UpdateProfileCommand;
use Modules\Client\Eloquent\Resolvers\Client\UpdateProfileEloquentResolver;
use Modules\Client\Models\ClientModel;

final class UpdateProfileHandler
{
    public function __construct(
        private readonly UpdateProfileEloquentResolver $resolver,
    ) {}

    public function handle(UpdateProfileCommand $command): ClientModel
    {
        return $this->resolver->resolve($command);
    }
}
