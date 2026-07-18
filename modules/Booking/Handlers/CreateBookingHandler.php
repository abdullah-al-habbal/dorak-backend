<?php

declare(strict_types=1);

namespace Modules\Booking\Handlers;

use Illuminate\Support\Facades\DB;
use Modules\Booking\CQRS\Command\CreateBookingCommand;
use Modules\Booking\Eloquent\Resolvers\CreateBookingEloquentResolver;
use Modules\Booking\Models\BookingModel;
use Modules\Booking\ValuesObjects\CreateBookingResult;

final class CreateBookingHandler
{
    public function __construct(
        private readonly CreateBookingEloquentResolver $resolver,
    ) {}

    public function handle(CreateBookingCommand $command): CreateBookingResult
    {
        $booking = DB::transaction(function () use ($command): BookingModel {
            return $this->resolver->resolve($command);
        });

        return new CreateBookingResult(booking: $booking);
    }
}
