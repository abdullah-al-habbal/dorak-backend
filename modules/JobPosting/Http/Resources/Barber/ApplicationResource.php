<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Resources\Barber;

use Illuminate\Http\Resources\Json\JsonResource;

final class ApplicationResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'job_posting_id' => $this->job_posting_id,
            'barber_id' => $this->barber_id,
            'job_posting_title' => $this->whenLoaded('jobPosting', fn (): array => $this->jobPosting->getTranslations('title')),
            'profile_snapshot' => $this->profile_snapshot,
            'status' => $this->status,
            'created_at' => $this->created_at->toIso8601String(),
        ];
    }
}
