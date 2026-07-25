<?php

declare(strict_types=1);

namespace Modules\JobPosting\Http\Requests\Barber;

use Illuminate\Validation\Rule;
use Modules\Chair\Models\ChairModel;
use Modules\Core\Http\Requests\BaseApiFormRequest;
use Modules\JobPosting\CQRS\Command\Barber\UpdateApplicationStatusCommand;
use Modules\JobPosting\Enums\ApplicationStatus;
use Modules\JobPosting\Models\ApplicationModel;

final class UpdateApplicationStatusRequest extends BaseApiFormRequest
{
    public function authorize(): bool
    {
        $applicationId = $this->route('application');

        $application = ApplicationModel::with('jobPosting')->find($applicationId);

        if (! $application) {
            return true;
        }

        return ChairModel::where('branch_id', $application->jobPosting->branch_id)
            ->where('barber_id', $this->user()->id)
            ->exists();
    }

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
