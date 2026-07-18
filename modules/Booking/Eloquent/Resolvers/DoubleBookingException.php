<?php

declare(strict_types=1);

namespace Modules\Booking\Eloquent\Resolvers;

final class DoubleBookingException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('This seat was just taken.');
    }
}
