<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\ServiceCatalog\CQRS\Command\UpdateCatalogItemCommand;
use Modules\ServiceCatalog\Handlers\UpdateCatalogItemHandler;
use Modules\ServiceCatalog\Http\Requests\UpdateCatalogItemRequest;
use Modules\ServiceCatalog\Http\Resources\ServiceCatalogItemResource;

final class UpdateCatalogItemAction extends BaseApiAction
{
    public function __construct(
        private readonly UpdateCatalogItemHandler $handler,
    ) {}

    public function __invoke(int $id, UpdateCatalogItemRequest $request): JsonResponse
    {
        $command = new UpdateCatalogItemCommand(
            id: $id,
            categoryId: $request->validated('category_id'),
            name: $request->validated('name'),
            description: $request->validated('description'),
            slug: $request->validated('slug'),
            sku: $request->validated('sku'),
            priceRange: $request->validated('price_range'),
            maintenanceLevel: $request->validated('maintenance_level'),
            stylePeriod: $request->validated('style_period'),
            formality: $request->validated('formality'),
            faceShapes: $request->validated('face_shapes'),
            hairTextures: $request->validated('hair_textures'),
            metadata: $request->validated('metadata'),
            isActive: $request->validated('is_active'),
        );

        $item = $this->handler->handle($command);

        return $this->ok(
            data: new ServiceCatalogItemResource($item),
        );
    }
}
