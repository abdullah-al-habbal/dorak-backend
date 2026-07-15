<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

final class JobPostingResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->getTranslations('title'),
            'description' => $this->getTranslations('description'),
            'status' => $this->status,
            'branch_id' => $this->branch_id,
            'applications_count' => $this->whenCounted('applications'),
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
