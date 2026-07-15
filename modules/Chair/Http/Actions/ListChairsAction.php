<?php

declare(strict_types=1);

namespace Modules\Chair\Http\Actions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Chair\Http\Resources\ChairResource;
use Modules\Chair\Models\ChairModel;
use Modules\Core\Http\Actions\BaseApiAction;

final class ListChairsAction extends BaseApiAction
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = ChairModel::with(['branch', 'barber']);

        $branchId = $request->route('branch') ?? $request->query('branch_id');
        if ($branchId) {
            $query->where('branch_id', $branchId);
        }

        if ($request->has('status')) {
            $query->where('status', $request->query('status'));
        }

        $chairs = $query->paginate(50);

        return $this->paginated(
            paginator: $chairs,
            resourceClass: ChairResource::class,
        );
    }
}
