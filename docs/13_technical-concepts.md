# 13 — Technical Concepts: Mandatory Reference

> **Status:** Active
> **Scope:** All AI agents must read this before implementing any feature.
> **Last Updated:** 2026-07-25

---

## PART 1: CRUD CONCEPTS

### Concept 01: Optimistic vs Pessimistic Updates

**Rule:** Decide *when* the UI updates — before or after server confirmation.

| Aspect | Optimistic | Pessimistic |
|--------|------------|-------------|
| **Flow** | Click → UI updates instantly → Request sent in background → Fail? Roll back | Click → Request sent → UI waits (loader) → UI updates after confirmation |
| **User Experience** | Feels instant | Feels safe |
| **Use For** | Low-risk actions (likes, follows, reactions) | Critical actions (payments, bookings, deletions) |

**Flutter Implementation:**

Optimistic (BLoC):
```dart
// In BLoC — emit new state immediately, send request in background
on<ToggleFavorite>((event, emit) {
  final previousState = state;
  emit(state.copyWith(isFavorite: !state.isFavorite)); // instant UI update
  _repository.toggleFavorite(event.id).catchError((error) {
    add(FavoriteError(previousState: previousState)); // rollback on failure
  });
});
```

Pessimistic (BLoC):
```dart
// In BLoC — emit loading, wait for response, then emit result
on<CreateBooking>((event, emit) async {
  emit(state.copyWith(isLoading: true));
  final result = await _repository.createBooking(event.data);
  result.fold(
    (error) => emit(state.copyWith(isLoading: false, error: error)),
    (booking) => emit(state.copyWith(isLoading: false, booking: booking)),
  );
});
```

**Laravel Implementation:**

Handler-level decision — no special middleware needed. The Action/Handler pattern already supports both:
```php
// Pessimistic: validate, process, return result (current pattern)
final class CreateBookingHandler
{
    public function handle(CreateBookingCommand $command): CreateBookingResult
    {
        // validation, processing, all before response
    }
}
```

---

### Concept 02: Version Locking

**Rule:** Every record carries a version number. Server only accepts a write if the version still matches — otherwise it's rejected.

**Key Points:**
- Prevents silent data loss when two users edit the same record simultaneously
- Rejected writes indicate **stale data** → refetch and let the user retry
- Example: Record #482 (version 3) → User A updates → version becomes 4 → User B's update with version 3 is rejected

**Laravel Implementation:**

Migration:
```php
Schema::table('barbers', function (Blueprint $table) {
    $table->unsignedInteger('version')->default(1);
});
```

Handler:
```php
final class UpdateBarberProfileHandler
{
    public function handle(UpdateBarberProfileCommand $command): void
    {
        $barber = BarberModel::findOrFail($command->barberId);

        if ($barber->version !== $command->expectedVersion) {
            throw new StaleDataException('Record was modified by another user. Please refresh and retry.');
        }

        $barber->update([
            ...$command->data,
            'version' => $barber->version + 1,
        ]);
    }
}
```

**Flutter Implementation:**

```dart
// Repository sends expectedVersion with every update
Future<void> updateProfile(String id, Map<String, dynamic> data, int expectedVersion) async {
  final response = await _dio.patch(
    '/barber/profile',
    data: {...data, 'expected_version': expectedVersion},
  );
  if (response.statusCode == 409) {
    throw StaleDataException(); // trigger refetch in BLoC
  }
}
```

---

### Concept 03: Idempotency Keys

**Rule:** Attach a unique ID to every write request. If that exact request gets sent twice (from a double-click, retry, or flaky network), the server recognizes the key it already processed and returns the **same result** instead of doing it again.

**Implementation:**

```
POST /api/v1/bookings
Idempotency-Key: "7f3a-9c21-4b8e-af02"
{
  "chair_id": "chair-42",
  "start_time": "2026-07-25T10:00:00Z"
}
```

**Laravel Implementation:**

Middleware:
```php
final class IdempotencyKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $key = $request->header('Idempotency-Key');
        if ($key) {
            $cached = Cache::get("idempotency:{$key}");
            if ($cached) {
                return response($cached['body'], $cached['status']);
            }
        }

        $response = $next($request);

        if ($key && $response->getStatusCode() < 400) {
            Cache::put("idempotency:{$key}", [
                'body' => $response->getContent(),
                'status' => $response->getStatusCode(),
            ], now()->addHours(24));
        }

        return $response;
    }
}
```

**Flutter Implementation:**

```dart
// Dio interceptor — attach idempotency key to every write request
class IdempotencyInterceptor extends Interceptor {
  @override
  void onRequest(RequestOptions options, handler) {
    if (options.method != 'GET') {
      options.headers['Idempotency-Key'] = const Uuid().v4();
    }
    handler.next(options);
  }
}
```

---

### Concept 04: Soft vs Hard Delete

**Rule:** Prefer soft deletes in production.

| Aspect | Hard Delete | Soft Delete |
|--------|-------------|-------------|
| **What happens** | Row permanently removed from database | `deleted_at` timestamp set |
| **Recovery** | No history. No recovery. | Hidden from app. Recoverable. Auditable. |
| **Use When** | Rarely | Refunds, audits, "restore my account" requests, legal holds |

**Laravel Implementation:**

```php
use Illuminate\Database\Eloquent\SoftDeletes;

final class BarberModel extends Authenticatable
{
    use SoftDeletes;

    // Queries automatically exclude soft-deleted records
    // Restore: $barber->restore()
    // Force delete: $barber->forceDelete()
    // Check: $barber->trashed()
}
```

**Flutter Implementation:**

No special handling needed — the API simply doesn't return soft-deleted records. Admin panel has "trash" view with restore action.

---

### Concept 05: PATCH vs PUT

**Rule:** Both update an existing resource, but differently. **PUT** replaces the entire object — you must send every field. **PATCH** updates only the fields included in the request.

| Feature | PUT | PATCH |
|---------|-----|-------|
| **Action** | Replaces the entire object | Updates only included fields |
| **Requirement** | Must send every field | Send only changed fields |
| **Risk** | Missing a field wipes it out | Removes that risk entirely |

**Laravel Implementation:**

```php
// PATCH — partial update (preferred for mobile)
Route::patch('/barber/profile', UpdateBarberProfileAction::class);

// PUT — full replacement (admin panel)
Route::put('/admin/barbers/{barber}', AdminUpdateBarberAction::class);
```

Handler for PATCH:
```php
final class UpdateBarberProfileHandler
{
    public function handle(UpdateBarberProfileCommand $command): void
    {
        $barber = BarberModel::findOrFail($command->barberId);

        // Only update fields that were sent
        $updates = array_filter($command->data, fn ($v) => $v !== null);
        $barber->update($updates);
    }
}
```

**Flutter Implementation:**

```dart
// Repository — PATCH with only changed fields
Future<void> updateProfile({String? name, String? email}) async {
  final data = <String, dynamic>{};
  if (name != null) data['name'] = name;
  if (email != null) data['email'] = email;
  if (data.isEmpty) return; // nothing to update

  await _dio.patch('/barber/profile', data: data);
}
```

---

### Concept 06: Race Conditions in Read

**Rule:** When a user types fast, your app fires off multiple requests. An **older request can arrive after a newer one** — silently overwriting the correct result with stale data.

**Problem:**
- User types "la" → sent → arrives 900ms
- User types "laptop" → sent → arrives 250ms
- Result: "laptop" shows first, then gets overwritten by slower "la" response

**Flutter Implementation:**

```dart
// BLoC — request counter to ignore stale responses
int _searchRequestId = 0;

on<SearchBarbers>((event, emit) async {
  final requestId = ++_searchRequestId;
  emit(state.copyWith(isSearching: true));

  final results = await _repository.search(event.query);

  // Only update if this is still the latest request
  if (requestId == _searchRequestId) {
    emit(state.copyWith(isSearching: false, results: results));
  }
  // Otherwise, discard — a newer request is in flight
});
```

---

### Concept 07: Batch Operations

**Rule:** Instead of firing one network request per item, batching combines many Create/Update/Delete actions into a **single request**.

**Comparison:**
```
ONE-BY-ONE (N+1)            BATCH REQUEST
for (item of items) {        POST /items/batch-update
  await api.update(item)     {
}                              updates: [ ...100 items ]
                             }
100 items = 100 requests     100 items = 1 request
```

**Laravel Implementation:**

```php
final class BatchUpdateChairsHandler
{
    public function handle(BatchUpdateChairsCommand $command): void
    {
        DB::transaction(function () use ($command) {
            foreach ($command->updates as $update) {
                ChairModel::where('id', $update['id'])->update($update['data']);
            }
        });
    }
}
```

**Flutter Implementation:**

```dart
// Repository — single batch call
Future<void> batchUpdateChairs(List<ChairUpdate> updates) async {
  await _dio.post('/branch/chairs/batch', data: {
    'updates': updates.map((u) => u.toJson()).toList(),
  });
}
```

---

### Concept 08: Retry with Exponential Backoff

**Rule:** When a request fails, retrying instantly can hammer a struggling server. Exponential backoff waits longer before each retry — 1s, 2s, 4s, 8s.

**Flutter Implementation:**

```dart
// Dio interceptor with retry package
import 'package:retry/retry.dart';

class RetryInterceptor extends Interceptor {
  final _retry = Retry(
    maxAttempts: 5,
    delayFactor: const Duration(seconds: 1),
    maxDelay: const Duration(seconds: 30),
    retryIf: (e) => e is TimeoutException || (e is DioException && e.type == DioExceptionType.connectionTimeout),
  );

  @override
  void onError(DioException err, ErrorInterceptorHandler handler) async {
    if (err.response?.statusCode == 429 || err.response?.statusCode == 503) {
      // Rate limited or server overloaded — retry with backoff
      try {
        final response = await _retry.retry(
          () => _dio.fetch(err.requestOptions),
        );
        handler.resolve(response);
        return;
      } catch (_) {}
    }
    handler.next(err);
  }
}
```

**Laravel Implementation (Queue Jobs):**

```php
final class ProcessFaceAnalysisJob implements ShouldQueue
{
    public int $tries = 5;
    public int $backoff = 8; // seconds: 2, 4, 8, 16, 32

    public function backoff(): array
    {
        return [2, 4, 8, 16, 32];
    }
}
```

---

### Concept 09: Read Consistency Models

**Rule:** Large apps copy data across multiple servers. Consistency defines how quickly a write becomes visible everywhere.

| Model | Behavior | Trade-off | Best For |
|-------|----------|-----------|----------|
| **Strict Consistency** | Every read is guaranteed up to date | Writes are slower | Payments, balances, booking confirmations |
| **Eventual Consistency** | Brief delay before all servers catch up | Speed over instant correctness | Social feeds, view counts, chair status |

**Dorak Context:**

| Feature | Consistency Model | Why |
|---------|------------------|-----|
| Booking creation | **Strict** | Double-booking prevention requires immediate consistency |
| Chair status toggle | **Eventual** (Reverb broadcast) | Brief delay acceptable, speed matters for UX |
| Barber profile update | **Eventual** | Profile changes can propagate slightly delayed |
| Payment/billing | **Strict** | Financial accuracy required |

---

### Concept 10: Field Projection

**Rule:** When listing resources, allow the client to request only the fields it needs — reducing payload size and improving performance.

**Implementation:**

```
GET /api/v1/explore/branches?fields=id,name,rating,latitude,longitude
```

**Laravel Implementation:**

```php
// Using Laravel API Resources
final class BranchResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $fields = $request->input('fields');
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'rating' => $this->rating,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            // ... all fields
        ];

        if ($fields) {
            return array_intersect_key($data, array_flip(explode(',', $fields)));
        }
        return $data;
    }
}
```

**Flutter Implementation:**

```dart
// Repository — request specific fields for list screens
Future<List<Branch>> getBranches({String? fields}) async {
  final response = await _dio.get('/explore/branches', queryParameters: {
    if (fields != null) 'fields': fields,
  });
  // ...
}
```

---

## PART 2: FLUTTER FRONTEND PROBLEMS

### Concept 01: TextFormField with Controller vs without

**Rule:** A `TextFormField` can be **controlled** (with `TextEditingController`) or **uncontrolled** (with `initialValue` only). **Mixing both on one field causes assertion errors.**

| Aspect | With Controller (Controlled) | With initialValue (Uncontrolled) |
|--------|------------------------------|----------------------------------|
| **State Management** | Value lives in `TextEditingController` | Widget manages its own state |
| **Updates** | Controller updates on every keystroke | React reads only when form is submitted |
| **Pattern** | `TextFormField(controller: _nameController)` | `TextFormField(initialValue: 'default')` |
| **Use When** | You need to read/display the value live (e.g., character count, conditional UI) | Simple forms where you only need the value on submit |

**Critical Rule:** Pick one pattern per field and stick to it. Never pass both `controller` and `initialValue`.

```dart
// CORRECT — controlled
final _nameController = TextEditingController();
TextFormField(controller: _nameController)

// CORRECT — uncontrolled
TextFormField(initialValue: widget.user.name)

// WRONG — assertion error
TextFormField(controller: _nameController, initialValue: 'default')
```

---

### Concept 02: Debouncing vs Throttling

**Rule:** Both limit how often a function runs — but differently. **Debounce** waits until the user stops triggering, then runs once. **Throttle** runs at most once per fixed interval.

| Aspect | Debounce | Throttle |
|--------|----------|----------|
| **Behavior** | Waits until user *stops* triggering → runs once | Runs *at most once* per fixed interval |
| **Use For** | Search-as-you-type (wait for pause) | Scroll/resize handlers (run steadily) |

**Flutter Implementation:**

```dart
// Debounce — search as you type
Timer? _debounce;

void _onSearchChanged(String query) {
  _debounce?.cancel();
  _debounce = Timer(const Duration(milliseconds: 300), () {
    context.read<SearchBloc>().add(SearchQueryChanged(query));
  });
}

@override
void dispose() {
  _debounce?.cancel();
  super.dispose();
}
```

```dart
// Throttle — scroll position updates
DateTime? _lastScrollUpdate;

void _onScroll(ScrollNotification notification) {
  final now = DateTime.now();
  if (_lastScrollUpdate != null && now.difference(_lastScrollUpdate!) < const Duration(milliseconds: 100)) {
    return; // throttle: skip if too soon
  }
  _lastScrollUpdate = now;
  // handle scroll...
}
```

---

### Concept 03: Handling Errors by Status Code

**Rule:** A generic "Something went wrong" toast for every failed request confuses users. Different status codes need **different UI responses**.

| Code | Meaning | Frontend Action |
|------|---------|-----------------|
| **401** | Not logged in | **Redirect** to login screen |
| **403** | Logged in, not allowed | Show "no permission" snackbar |
| **422** | Validation error | Show field-level errors in form |
| **429** | Too many requests | "Please wait" + retry timer |
| **500** | Server broke | Show retry button |

**Flutter Implementation:**

```dart
// ApiException carries status code
class ApiException implements Exception {
  final int statusCode;
  final String message;
  ApiException(this.statusCode, this.message);
}

// BLoC error handler
on<SubmitForm>((event, emit) async {
  emit(state.copyWith(isLoading: true));
  try {
    await _repository.submit(event.data);
    emit(state.copyWith(isLoading: false, isSuccess: true));
  } on ApiException catch (e) {
    switch (e.statusCode) {
      case 401:
        emit(state.copyWith(redirectToLogin: true));
      case 422:
        emit(state.copyWith(validationErrors: e.errors));
      case 429:
        emit(state.copyWith(retryAfter: e.retryAfter));
      default:
        emit(state.copyWith(error: e.message));
    }
  }
});
```

---

### Concept 04: Local State vs Server State

**Rule:** Local state is UI-only — a modal being open, a form draft. Server state is data from the API that can go **stale**. Treating server data like local state means you rebuild caching/refetching manually.

| Local State (Widget/BLoC UI) | Server State (Repository/BLoC data) |
|------------------------------|-------------------------------------|
| Modal open/closed | User profile data |
| Form input draft | List of branches |
| Selected tab | Booking status |
| Loading indicator | Barber services list |

**Rule of Thumb in Flutter:**
- **Local state:** `setState`, `ValueNotifier`, or BLoC fields like `isModalOpen`
- **Server state:** BLoC loaded state + Repository with caching

```dart
// CORRECT — server state in BLoC, local state in widget
class BranchCard extends StatefulWidget {
  @override
  State<BranchCard> createState() => _BranchCardState();
}

class _BranchCardState extends State<BranchCard> {
  bool _isExpanded = false; // local state — UI only

  @override
  Widget build(BuildContext context) {
    return BlocBuilder<ExploreBloc, ExploreState>(
      builder: (context, state) {
        // state.branches — server state from BLoC
        return ExpansionTile(
          initiallyExpanded: _isExpanded, // local state
          children: state.branches.map(...).toList(),
        );
      },
    );
  }
}
```

---

### Concept 05: Protected Routes & Auth Guards

**Rule:** Auth check must resolve **before** the real content renders. Never render protected content optimistically before the check completes.

**Flutter Implementation:**

```dart
// GoRouter with auth redirect
final router = GoRouter(
  initialLocation: '/splash',
  redirect: (context, state) {
    final authState = context.read<AuthBloc>().state;
    final isProtected = !['/login', '/register', '/splash'].contains(state.matchedLocation);

    if (authState is AuthInitial) {
      return '/splash'; // still checking — show splash
    }
    if (isProtected && authState is! AuthAuthenticated) {
      return '/login'; // not logged in — redirect
    }
    if (state.matchedLocation == '/login' && authState is AuthAuthenticated) {
      return '/explore'; // already logged in — redirect away
    }
    return null; // no redirect
  },
  routes: [
    GoRoute(path: '/splash', builder: (_, __) => const SplashScreen()),
    GoRoute(path: '/login', builder: (_, __) => const LoginScreen()),
    ShellRoute(
      builder: (_, __, child) => MainShell(child: child),
      routes: [
        GoRoute(path: '/explore', builder: (_, __) => const ExploreScreen()),
        GoRoute(path: '/bookings', builder: (_, __) => const BookingsScreen()),
        // ... protected routes
      ],
    ),
  ],
);
```

---

### Concept 06: CORS from the Frontend Side

**Rule:** CORS is a browser security feature. **Flutter mobile apps are NOT affected** — CORS only applies to web browsers.

**Relevant For:**
- Filament admin panel (runs in browser) → CORS headers needed on API
- Flutter mobile apps → No CORS concern

**Laravel Implementation (for Filament/web):**

```php
// config/cors.php — already handled by Laravel
'paths' => ['api/*', 'sanctum/csrf-cookie'],
'allowed_origins' => ['https://admin.dorak.com'],
'supports_credentials' => true,
```

---

### Concept 07: Public vs Secret Env Variables

**Rule:** Secrets must never be bundled into the app binary or exposed to the client.

**Flutter Implementation:**

```bash
# Build-time secrets (baked into binary — use only for public config)
flutter run --dart-define=API_BASE_URL=https://api.dorak.com

# Runtime secrets (read from secure storage — for sensitive data)
# Use flutter_secure_storage for tokens, API keys on device
```

```dart
// CORRECT — secrets from secure storage
final token = await FlutterSecureStorage().read(key: 'auth_token');

// WRONG — hardcoded secrets
const apiKey = 'sk_live_abc123'; // NEVER do this
```

---

### Concept 08: List Virtualization

**Rule:** Rendering thousands of widgets at once freezes the app. `ListView.builder` only renders visible items.

**Flutter Implementation:**

```dart
// CORRECT — virtualized (lazy) list
ListView.builder(
  itemCount: branches.length,
  itemBuilder: (context, index) => BranchCard(branch: branches[index]),
)

// WRONG — renders ALL items at once
ListView(
  children: branches.map((b) => BranchCard(branch: b)).toList(),
)
```

**Rule:** Always use `ListView.builder` for API data. `ListView(children: [...])` is only acceptable for small, fixed lists (like 3-5 filter chips).

---

### Concept 09: Multi-Device State Sync

**Rule:** When the same user is logged in on multiple devices, a state change on one device must eventually reflect on the other.

**Flutter Implementation:**

```dart
// BLoC — periodic refresh for critical data
on<StartBookingPolling>((event, emit) async {
  await for (final _ in Stream.periodic(const Duration(seconds: 30))) {
    final bookings = await _repository.fetchBookings();
    emit(state.copyWith(bookings: bookings));
  }
});
```

**Laravel Implementation:**
- Reverb WebSocket for real-time (chair status, booking updates)
- Pull-to-refresh in Flutter for eventually-consistent data
- Optimistic updates with server confirmation for critical actions

---

### Concept 10: Client-Side Cache Invalidation

**Rule:** After a mutation (create/update/delete), you must explicitly refresh the cached data — otherwise stale data persists.

**Flutter Implementation:**

```dart
// BLoC — after successful mutation, refetch the list
on<DeleteService>((event, emit) async {
  final result = await _repository.deleteService(event.serviceId);
  result.fold(
    (error) => emit(state.copyWith(error: error)),
    (_) {
      // Invalidate cache — refetch the list
      add(LoadServices());
    },
  );
});

// Repository — caching layer
class ServiceRepository {
  List<ServiceCatalogItem>? _cache;

  Future<List<ServiceCatalogItem>> getServices() async {
    if (_cache != null) return _cache!;
    final data = await _fetchFromApi();
    _cache = data;
    return data;
  }

  void invalidateCache() {
    _cache = null;
  }
}
```

---

## PART 3: LARAVEL/FLUTTER INTEGRATION PATTERNS

### Pattern 01: API Response Format

All Dorak API responses follow `ApiResponseBodyValueObject`:

```json
// Success (paginated)
{
  "status": "success",
  "code": "FETCHED",
  "data": { "items": [...], "meta": { "current_page": 1, "last_page": 5 } }
}

// Success (single)
{
  "status": "success",
  "code": "FETCHED",
  "data": { "id": "...", "name": "..." }
}

// Error
{
  "status": "error",
  "code": "VALIDATION_ERROR",
  "message": "The name field is required."
}
```

### Pattern 02: Auth Flow

**Client App (Sanctum tokens):**
1. Login → `POST /api/v1/client/login` → returns `plainTextToken`
2. Store token in `FlutterSecureStorage`
3. Attach `Authorization: Bearer {token}` to all requests via Dio interceptor
4. On 401 → clear token → redirect to login

**Barber App (Sanctum tokens):**
1. Login → `POST /api/v1/barber/login` → returns `plainTextToken`
2. Same flow as client app

**Business App (Sanctum tokens via `branch_api` guard):**
1. Login → `POST /api/v1/branch/login` → returns `plainTextToken`
2. Same flow as client app

**Filament Panels (Session):**
1. Login via Filament form → session cookie
2. No token management needed

### Pattern 03: Feature Module Structure

**Backend (per module):**
```
modules/{Module}/
├── Config/
├── Database/Migrations/
├── Enums/
├── Filament/Panels/{Admin|Barber|Branch}/Resources/
├── Http/Actions/{Client|Barber|Shared}/
├── Http/Resources/
├── Models/
├── Routes/Api/V1/
└── Providers/
```

**Flutter (per feature):**
```
lib/Features/{Feature}/
├── Data/
│   ├── {Entity}Dto.dart
│   ├── {Entity}Dto.g.dart
│   └── {Feature}Repository.dart
├── Domain/
│   └── {Entity}Entity.dart
└── Presentation/
    ├── {Feature}Bloc.dart
    ├── Events/
    ├── States/
    ├── {Feature}Screen.dart
    └── Widgets/
```

---

## COMPLETION STATUS

### CRUD Concepts (Part 1)
- ✅ 01. Optimistic vs Pessimistic Updates
- ✅ 02. Version Locking
- ✅ 03. Idempotency Keys
- ✅ 04. Soft vs Hard Delete
- ✅ 05. PATCH vs PUT
- ✅ 06. Race Conditions in Read
- ✅ 07. Batch Operations
- ✅ 08. Retry with Exponential Backoff
- ✅ 09. Read Consistency Models
- ✅ 10. Field Projection

### Flutter Frontend Problems (Part 2)
- ✅ 01. TextFormField with Controller vs without
- ✅ 02. Debouncing vs Throttling
- ✅ 03. Handling Errors by Status Code
- ✅ 04. Local State vs Server State
- ✅ 05. Protected Routes & Auth Guards
- ✅ 06. CORS from Frontend Side
- ✅ 07. Public vs Secret Env Variables
- ✅ 08. List Virtualization
- ✅ 09. Multi-Device State Sync
- ✅ 10. Client-Side Cache Invalidation

### Integration Patterns (Part 3)
- ✅ 01. API Response Format
- ✅ 02. Auth Flow
- ✅ 03. Feature Module Structure
