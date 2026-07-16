---
name: strict-enums
description: Enforce PHP 8.1+ Backed Enums across the Dorak backend AND `json_serializable` + `@JsonKey` across the Flutter frontend. Use whenever creating a new status/type field, writing validation, casting model attributes, or defining Dart DTOs/Entities. All status/type DB columns must use Backed Enums, `Rule::enum` validation, and model casts. All Flutter DTOs/Entities must use `@JsonSerializable`, `@JsonKey`, and `@JsonEnum`.
---

# Strict Enums & JSON Serialization

## 1. Enum Creation

- Every status/type DB column gets a Backed Enum in `modules/{Module}/Enums/`
- Enum must be `string` backed with lowercase values matching DB
- Include `label(): string` and any domain helpers (e.g. `isBookable()`)

```php
enum ChairStatus: string
{
    case Available = 'available';
    case Occupied = 'occupied';
    case Maintenance = 'maintenance';

    public function label(): string
    {
        return __('chair::enums.status.'.$this->value);
    }

    public function isBookable(): bool
    {
        return $this === self::Available;
    }
}
```

## 2. Model Casts

Every `status`/`type` fillable column MUST have a `casts()` entry:

```php
protected function casts(): array
{
    return [
        'status' => ChairStatus::class,
    ];
}
```

## 3. Validation Rules

Use `Rule::enum` instead of `in:` strings:

```php
use Illuminate\Validation\Rules\Enum;

'status' => ['required', 'string', new Enum(ChairStatus::class)],
```

## 4. Comparisons

Compare model status against enum cases, never strings:

```php
// ❌ WRONG — strict comparison always fails
if ($chair->status !== 'available') { }

// ✅ CORRECT
if ($chair->status !== ChairStatus::Available) { }
```

## 5. Factories & Seeders

Use enum case references, not raw strings:

```php
'status' => ChairStatus::Available,
```

---

## 6. Flutter: `json_serializable` (`dorak_client_app`)

### 6.1 Every DTO/Entity MUST use `@JsonSerializable`

```dart
import 'package:json_annotation/json_annotation.dart';
part 'my_dto.g.dart';

@JsonSerializable()
final class MyDto {
  @JsonKey(name: 'backend_snake_case')
  final String id;

  const MyDto({required this.id});

  factory MyDto.fromJson(Map<String, dynamic> json) => _$MyDtoFromJson(json);
  Map<String, dynamic> toJson() => _$MyDtoToJson(this);
}
```

### 6.2 Every property MUST have explicit `@JsonKey(name: '...')`

Maps backend `snake_case` to frontend `camelCase`. No implicit naming.

### 6.3 Enum fields use `unknownEnumValue` for safe fallback

```dart
@JsonKey(name: 'status', unknownEnumValue: ChairStatus.available)
final ChairStatus status;
```

### 6.4 Generate code with build_runner

```bash
dart run build_runner build --delete-conflicting-outputs
```

## 7. Flutter: `@JsonEnum`

Every Dart enum that maps to a backend string MUST have `@JsonEnum()`:

```dart
import 'package:json_annotation/json_annotation.dart';

@JsonEnum()
enum ChairStatus {
  available,
  occupied,
  maintenance;
}
```

## 8. ZERO manual parsing

- NO manual `as Type` casting in DTOs
- NO manual `fromJson` switch/if-else logic
- NO `Map<String, dynamic>` in Domain Entities (use `Map<String, String>` for translatable fields)
- NO duplicate Entity files — single source of truth per concept
