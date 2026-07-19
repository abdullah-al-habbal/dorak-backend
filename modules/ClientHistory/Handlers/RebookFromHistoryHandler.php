<?php

declare(strict_types=1);

namespace Modules\ClientHistory\Handlers;

use Modules\Booking\Models\BookingModel;
use Modules\ClientHistory\CQRS\Command\RebookFromHistoryCommand;
use Modules\ClientHistory\Eloquent\Resolvers\RebookFromHistoryEloquentResolver;

final class RebookFromHistoryHandler
{
    public function __construct(
        private readonly RebookFromHistoryEloquentResolver $resolver,
    ) {}

    public function handle(RebookFromHistoryCommand $command): BookingModel
    {
        return $this->resolver->resolve($command);
    }
}
