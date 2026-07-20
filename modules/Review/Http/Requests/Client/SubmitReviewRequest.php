<?php

declare(strict_types=1);

namespace Modules\Review\Http\Requests\Client;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\Review\CQRS\Command\Client\SubmitReviewCommand;

final class SubmitReviewRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function toCommand(string $bookingId): SubmitReviewCommand
    {
        return new SubmitReviewCommand(
            bookingId: $bookingId,
            authorId: (string) $this->user()->id,
            authorType: $this->user()::class,
            rating: (int) $this->validated('rating'),
            comment: $this->validated('comment'),
        );
    }
}
