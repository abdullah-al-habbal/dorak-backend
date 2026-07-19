<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Handlers;

use Modules\ClientHistory\CQRS\Command\AttachHistoryMediaCommand;
use Modules\ClientHistory\Eloquent\Resolvers\AttachHistoryMediaEloquentResolver;
use Modules\ClientHistory\Models\ClientServiceHistoryMediaModel;

final class AttachHistoryMediaHandler
{
    public function __construct(
        private readonly AttachHistoryMediaEloquentResolver $resolver,
    ) {}

    public function handle(AttachHistoryMediaCommand $command): ClientServiceHistoryMediaModel
    {
        return $this->resolver->resolve($command);
    }
}
