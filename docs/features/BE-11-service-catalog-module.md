# BE-11 — ServiceCatalog Module (PRD Phase 1)

## Status: ✅ Complete
## Frontend Consumer: ServiceCatalogBrowseScreen (pending FE-02)

## What Was Built
- 5 Models: ServiceCatalogCategoryModel, ServiceCatalogItemModel, ServiceCatalogItemTagModel, ServiceCatalogItemTagAssignmentModel, ServiceCatalogItemMediumModel
- 5 Enums: FaceShapeEnum, HairTextureEnum, MaintenanceLevelEnum, StylePeriodEnum, FormalityEnum
- 2 ValueObjects: PriceRangeValueObject, ServiceCatalogItemMetadataValueObject
- 2 Casts: PriceRangeCast, ServiceCatalogItemMetadataCast
- CQRS: Create/Update/Delete/List/Get commands+queries, handlers, resolvers
- Filament Admin CRUD: Categories, Items, Tags (list/create/edit/view)
- OfferedServiceModel.catalog_item_id FK + migration

## API Endpoints
| Method | Endpoint | Auth | Action |
|--------|----------|------|--------|
| GET | `/api/v1/service-catalog/items` | — | `ListCatalogItemsAction` |
| GET | `/api/v1/service-catalog/items/{id}` | — | `ShowCatalogItemAction` |
| POST | `/api/v1/service-catalog/items` | auth:client | `CreateCatalogItemAction` |
| PUT | `/api/v1/service-catalog/items/{id}` | auth:client | `UpdateCatalogItemAction` |
| DELETE | `/api/v1/service-catalog/items/{id}` | auth:client | `DeleteCatalogItemAction` |

## Response Schemas

### GET /api/v1/service-catalog/items → 200 (paginated)
```json
{
  "data": [
    {
      "id": "uuid",
      "category_id": "uuid",
      "name": { "en": "Fade Haircut", "ar": "قصة فaad" },
      "description": { "en": "Classic fade", "ar": "قصة كلاسيكية" },
      "slug": "fade-haircut",
      "sku": "SC-001",
      "price_range": { "min": 5000, "max": 15000, "currency": "SYP" },
      "maintenance_level": "low|medium|high",
      "style_period": "classic|modern|trendy",
      "formality": "casual|business|formal",
      "face_shapes": ["oval", "round", "square"],
      "hair_textures": ["straight", "wavy", "curly"],
      "metadata": { "productsUsed": [], "colorCodes": [] },
      "category": {
        "id": "uuid",
        "name": { "en": "Haircuts", "ar": "قصات شعر" },
        "slug": "haircuts"
      },
      "tags": [
        {
          "id": "uuid",
          "name": { "en": "Popular", "ar": "شائع" },
          "slug": "popular",
          "group": "style"
        }
      ],
      "is_active": true,
      "created_at": "2026-07-24T12:00:00Z",
      "updated_at": "2026-07-24T12:00:00Z"
    }
  ],
  "meta": { "pagination": { "..." } }
}
```

### GET /api/v1/service-catalog/items/{id} → 200
Same shape as above (single item with category + tags loaded).

## Tests: 15 tests across ServiceCatalog module
