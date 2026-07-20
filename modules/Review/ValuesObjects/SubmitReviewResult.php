<?php

declare(strict_types=1);

namespace Modules\Review\ValuesObjects;

use Modules\Review\Models\ReviewModel;

final readonly class SubmitReviewResult
{
    private function __construct(
        public bool $success,
        public ?ReviewModel $review,
        public ?string $errorCode,
    ) {}

    public static function success(ReviewModel $review): self
    {
        return new self(true, $review, null);
    }

    public static function notOwnBooking(): self
    {
        return new self(false, null, 'not_own_booking');
    }

    public static function bookingNotCompleted(): self
    {
        return new self(false, null, 'booking_not_completed');
    }

    public static function alreadyReviewed(): self
    {
        return new self(false, null, 'already_reviewed');
    }

    public static function noSubject(): self
    {
        return new self(false, null, 'no_subject');
    }
}
