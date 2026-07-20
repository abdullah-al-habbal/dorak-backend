<?php

declare(strict_types=1);

namespace Modules\Client\Http\Requests\Client;

use Modules\Client\CQRS\Command\Client\UploadAvatarCommand;
use Modules\Core\Http\Requests\BaseApiFormRequest;

final class UploadAvatarRequest extends BaseApiFormRequest
{
    public function rules(): array
    {
        return [
            'avatar' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ];
    }

    public function toCommand(): UploadAvatarCommand
    {
        $client = $this->user();
        $path = $this->file('avatar')->store('avatars', 'public');

        return new UploadAvatarCommand(
            clientId: (string) $client->id,
            filePath: $path,
            oldAvatarPath: $client->avatar,
        );
    }
}
