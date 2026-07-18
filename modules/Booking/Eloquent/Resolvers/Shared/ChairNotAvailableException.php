<?php

declare(strict_types=1);

namespace Modules\Booking\Eloquent\Resolvers\Shared;

final class ChairNotAvailableException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Chair is not available for booking.');
    }
}
