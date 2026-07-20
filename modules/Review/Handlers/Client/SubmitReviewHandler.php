<?php

declare(strict_types=1);

namespace Modules\Review\Handlers\Client;

use Modules\Booking\Enums\BookingStatus;
use Modules\Branch\Models\BranchModel;
use Modules\Review\CQRS\Command\Client\SubmitReviewCommand;
use Modules\Review\Eloquent\Resolvers\Client\SubmitReviewEloquentResolver;
use Modules\Review\ValuesObjects\SubmitReviewResult;

final class SubmitReviewHandler
{
    public function __construct(
        private readonly SubmitReviewEloquentResolver $resolver,
    ) {}

    public function handle(SubmitReviewCommand $command): SubmitReviewResult
    {
        $booking = $this->resolver->findBookingWithSubject($command->bookingId);

        if ($booking->client_id !== $command->authorId) {
            return SubmitReviewResult::notOwnBooking();
        }

        if ($booking->status !== BookingStatus::Completed) {
            return SubmitReviewResult::bookingNotCompleted();
        }

        if ($booking->review()->exists()) {
            return SubmitReviewResult::alreadyReviewed();
        }

        $subjectId = $booking->chair?->branch_id;

        if ($subjectId === null) {
            return SubmitReviewResult::noSubject();
        }

        $review = $this->resolver->createReview([
            'booking_id' => $booking->id,
            'author_id' => $command->authorId,
            'author_type' => $command->authorType,
            'subject_id' => $subjectId,
            'subject_type' => BranchModel::class,
            'rating' => $command->rating,
            'comment' => $command->comment,
        ]);

        return SubmitReviewResult::success($review);
    }
}
