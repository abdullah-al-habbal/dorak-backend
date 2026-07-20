<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Requests\Barber;

use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\JobPosting\CQRS\Command\Barber\ApplyForJobCommand;

final class ApplyForJobRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'cover_letter' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function toCommand(string $jobPostingId): ApplyForJobCommand
    {
        return new ApplyForJobCommand(
            jobPostingId: $jobPostingId,
            barberId: (string) $this->user()->id,
            barberName: $this->user()->name,
            barberEmail: $this->user()->email,
            isFreelancer: $this->user()->is_freelancer ?? false,
        );
    }
}
