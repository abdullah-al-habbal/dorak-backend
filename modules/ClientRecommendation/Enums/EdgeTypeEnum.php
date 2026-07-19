<?php

declare(strict_types=1);

namespace Modules\ClientRecommendation\Enums;

enum EdgeTypeEnum: string
{
    case Favorite = 'favorite';
    case Visited = 'visited';
    case History = 'history';
    case FaceMatched = 'face_matched';
    case SimilarClient = 'similar_client';

    public function label(): string
    {
        return $this->value;
    }
}
