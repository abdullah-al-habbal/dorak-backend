<?php

declare(strict_types=1);

namespace Modules\Booking\Handlers\Client;

use Modules\Booking\CQRS\Command\Client\CancelBookingCommand;
use Modules\Booking\Eloquent\Resolvers\Client\CancelBookingEloquentResolver;
use Modules\Booking\ValuesObjects\CancelBookingResult;

final class CancelBookingHandler
{
    public function __construct(
        private readonly CancelBookingEloquentResolver $resolver,
    ) {}

    public function handle(CancelBookingCommand $command): CancelBookingResult
    {
        return $this->resolver->resolve($command);
    }
}
