<?php

declare(strict_types=1);

namespace Modules\Branch\Repositories;

use Modules\Branch\Models\BranchModel;

final class BranchLoginEloquentResolver
{
    public function findByEmail(string $email): ?BranchModel
    {
        return BranchModel::where('email', $email)->first();
    }
}
