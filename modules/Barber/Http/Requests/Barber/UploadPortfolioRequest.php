<?php

declare(strict_types=1);

namespace Modules\Barber\Http\Requests\Barber;

use Modules\Barber\CQRS\Command\Barber\UploadPortfolioCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UploadPortfolioRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'photo' => ['required', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
        ];
    }

    public function toCommand(): UploadPortfolioCommand
    {
        return new UploadPortfolioCommand(
            barberId: (string) $this->user()->id,
            photo: $this->file('photo'),
        );
    }
}
