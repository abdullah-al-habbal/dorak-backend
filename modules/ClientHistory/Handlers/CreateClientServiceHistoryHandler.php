<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Handlers;

use Modules\ClientHistory\CQRS\Command\CreateClientServiceHistoryCommand;
use Modules\ClientHistory\Eloquent\Resolvers\CreateClientServiceHistoryEloquentResolver;
use Modules\ClientHistory\Models\ClientServiceHistoryModel;

final class CreateClientServiceHistoryHandler
{
    public function __construct(
        private readonly CreateClientServiceHistoryEloquentResolver $resolver,
    ) {}

    public function handle(CreateClientServiceHistoryCommand $command): ClientServiceHistoryModel
    {
        return $this->resolver->resolve($command);
    }
}
