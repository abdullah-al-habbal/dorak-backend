<?php

declare(strict_types=1);

namespace Modules\Client\ValuesObjects;

final readonly class DeleteAccountResult
{
    public function __construct(
        public bool $success,
        public ?string $failureReason = null,
    ) {}

    public static function success(): self
    {
        return new self(true);
    }

    public static function invalidCredentials(): self
    {
        return new self(false, 'invalid_credentials');
    }

    public static function activeBookings(): self
    {
        return new self(false, 'active_bookings');
    }

    public function isInvalidCredentials(): bool
    {
        return $this->failureReason === 'invalid_credentials';
    }

    public function hasActiveBookings(): bool
    {
        return $this->failureReason === 'active_bookings';
    }
}
