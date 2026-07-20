<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Requests\Barber;

use Illuminate\Validation\Rule;
use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\JobPosting\CQRS\Command\Barber\UpdateApplicationStatusCommand;
use Modules\JobPosting\Enums\ApplicationStatus;

final class UpdateApplicationStatusRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'status' => ['required', Rule::enum(ApplicationStatus::class)],
        ];
    }

    public function toCommand(string $applicationId): UpdateApplicationStatusCommand
    {
        return new UpdateApplicationStatusCommand(
            applicationId: $applicationId,
            status: ApplicationStatus::from($this->validated('status')),
        );
    }
}
