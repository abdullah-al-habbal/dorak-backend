<?php

declare(strict_types=1);

namespace Modules\ClientFaceProfile\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class ClientFaceAnalysisResultResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'face_profile_id' => $this->face_profile_id,
            'analysis_version' => $this->analysis_version,
            'analysis_source' => $this->analysis_source->value,
            'detected_face_shape' => $this->detected_face_shape->value,
            'confidence_score' => $this->confidence_score,
            'detected_features' => $this->detected_features?->toArray(),
            'recommended_catalog_item_ids' => $this->recommended_catalog_item_ids?->ids(),
            'computed_at' => $this->computed_at->toIso8601String(),
            'face_profile' => $this->whenLoaded('faceProfile', fn () => [
                'id' => $this->faceProfile->id,
                'image_url' => $this->faceProfile->image_url,
            ]),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
