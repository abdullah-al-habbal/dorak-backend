<?php

declare(strict_types=1);

namespace Modules\Review\Http\Actions\Client;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\Review\Handlers\Client\SubmitReviewHandler;
use Modules\Review\Http\Requests\Client\SubmitReviewRequest;
use Modules\Review\Http\Resources\Shared\ReviewResource;

final class SubmitReviewAction extends BaseApiAction
{
    public function __construct(
        private readonly SubmitReviewHandler $handler,
    ) {}

    public function __invoke(SubmitReviewRequest $request, string $booking): JsonResponse
    {
        $result = $this->handler->handle($request->toCommand($booking));

        if (! $result->success) {
            $status = match ($result->errorCode) {
                'not_own_booking' => 403,
                'already_reviewed' => 409,
                default => 422,
            };

            return $this->error(
                code: 'REVIEW_VALIDATION_FAILED',
                message: __("review::messages.{$result->errorCode}"),
                status: $status,
            );
        }

        return $this->created(
            data: new ReviewResource($result->review),
            message: __('review::messages.submitted'),
        );
    }
}
