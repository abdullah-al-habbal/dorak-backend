<?php

declare(strict_types=1);

namespace Modules\Review\Eloquent\Resolvers\Client;

use Modules\Booking\Models\BookingModel;
use Modules\Review\Models\ReviewModel;

final class SubmitReviewEloquentResolver
{
    public function findBookingWithSubject(string $bookingId): BookingModel
    {
        return BookingModel::with('chair.branch')->findOrFail($bookingId);
    }

    public function createReview(array $data): ReviewModel
    {
        $review = ReviewModel::create($data);
        $review->load('author');

        return $review;
    }
}
