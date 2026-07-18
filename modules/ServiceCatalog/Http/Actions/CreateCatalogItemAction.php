<?php

declare(strict_types=1);

namespace Modules\ServiceCatalog\Http\Actions;

use Illuminate\Http\JsonResponse;
use Modules\Core\Http\Actions\BaseApiAction;
use Modules\ServiceCatalog\CQRS\Command\CreateCatalogItemCommand;
use Modules\ServiceCatalog\Handlers\CreateCatalogItemHandler;
use Modules\ServiceCatalog\Http\Requests\CreateCatalogItemRequest;
use Modules\ServiceCatalog\Http\Resources\ServiceCatalogItemResource;

final class CreateCatalogItemAction extends BaseApiAction
{
    public function __construct(
        private readonly CreateCatalogItemHandler $handler,
    ) {}

    public function __invoke(CreateCatalogItemRequest $request): JsonResponse
    {
        $command = new CreateCatalogItemCommand(
            categoryId: (int) $request->validated('category_id'),
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
            isActive: (bool) $request->validated('is_active', true),
        );

        $item = $this->handler->handle($command);

        $item->load(['category', 'tags']);

        return $this->created(
            data: new ServiceCatalogItemResource($item),
        );
    }
}
