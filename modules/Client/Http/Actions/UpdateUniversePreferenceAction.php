<?php
declare(strict_types=1);

namespace Modules\Client\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;

final class UpdateUniversePreferenceAction extends BaseApiAction
{
    public function __invoke(): JsonResponse
    {
        $data = request()->validate([
            'universe' => 'required|string|in:men,women,neutral',
        ]);

        request()->user()->update([
            'preferred_universe' => $data['universe'],
        ]);

        return $this->updated(data: [
            'preferred_universe' => $data['universe'],
        ]);
    }
}
