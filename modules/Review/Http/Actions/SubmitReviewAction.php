<?php

declare(strict_types=1);

namespace Modules\Review\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Booking\Models\BookingModel;
use Modules\Branch\Models\BranchModel;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Review\Http\Requests\SubmitReviewRequest;
use Modules\Review\Http\Resources\ReviewResource;
use Modules\Review\Models\ReviewModel;

final class SubmitReviewAction extends BaseApiAction
{
    public function __invoke(SubmitReviewRequest $request, string $booking): JsonResponse
    {
        $booking = BookingModel::with('chair.branch')->findOrFail($booking);

        if ($booking->client_id !== $request->user()->id) {
            return $this->error(message: __('review::messages.not_own_booking'), statusCode: 403);
        }

        if ($booking->status !== 'completed') {
            return $this->error(message: __('review::messages.booking_not_completed'), statusCode: 422);
        }

        if ($booking->review()->exists()) {
            return $this->error(message: __('review::messages.already_reviewed'), statusCode: 409);
        }

        $subjectId = $booking->chair?->branch_id;
        $subjectType = $subjectId !== null ? BranchModel::class : null;

        if ($subjectId === null) {
            return $this->error(message: __('review::messages.no_subject'), statusCode: 422);
        }

        $review = ReviewModel::create([
            'booking_id' => $booking->id,
            'author_id' => $request->user()->id,
            'author_type' => $request->user()::class,
            'subject_id' => $subjectId,
            'subject_type' => $subjectType,
            'rating' => $request->validated('rating'),
            'comment' => $request->validated('comment'),
        ]);

        $review->load('author');

        return $this->created(
            data: new ReviewResource($review),
            message: __('review::messages.submitted'),
        );
    }
}
