---
name: strict-backend-architecture
description: Enforces FormRequest usage, strongly-typed Command/Query objects, strict PHP imports, and Resource composition in the Dorak Laravel backend. Trigger when writing Actions, Handlers, Resources, or Requests.
---
# Strict Backend Architecture Rules

## 1. No Inline Validation

- **V1.** NEVER use `$request->validate([...])` inside an Action or Handler.
- **V2.** ALL validation MUST be handled by a dedicated `FormRequest` class located in `Http/Requests/`.
- **V3.** The Action method signature MUST type-hint the FormRequest (e.g. `public function __invoke(CreateBookingRequest $request)`).

## 2. Strongly-Typed Command/Query Objects

- **C1.** Actions MUST NOT pass raw `$validated` arrays to Handlers.
- **C2.** Create a Command (for mutations) or Query (for reads) Value Object in the `CQRS/` or `Domain/` directory.
- **C3.** Command properties MUST be PascalCase (e.g. `$command->ChairId`, `$command->ServiceIds`).
- **C4.** The Action is responsible for instantiating the Command object and passing it to the Handler.

## 3. Strict PHP Imports

- **I1.** NEVER use inline Fully Qualified Class Names (FQCN) like
```
use Modules\Booking\Http\Resources\BookingResource;
new BookingResource(...)
```
- **I2.** ALL classes MUST be imported at the top of the file using the `use` statement.

## 4. Resource Composition (No Inline Mapping)

- **R1.** API Resources MUST NOT map related models using inline arrays or closures.
- **R2.** If a relationship needs to be serialized, delegate it to a dedicated sub-resource.

**BAD:**
```php
'chair' => ['id' => $this->chair->id, 'label' => $this->chair->label]
```

**GOOD:**
```php
'chair' => ChairResource::make($this->whenLoaded('chair'))
```
