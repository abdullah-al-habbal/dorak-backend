<?php

declare(strict_types=1);

namespace Modules\Review\CQRS\Command\Client;

final readonly class SubmitReviewCommand
{
    public function __construct(
        public string $bookingId,
        public string $authorId,
        public string $authorType,
        public int $rating,
        public ?string $comment,
    ) {}
}
