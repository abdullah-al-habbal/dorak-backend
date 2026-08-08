---
name: laravel-attributes
description: MANDATORY reference for all new Laravel 13 PHP code in this repo. Use class attributes (#[Fillable], #[Table], #[Signature], etc.) instead of class properties for Eloquent models, jobs, console commands, controllers, form requests, resources, factories, testing hooks, and container injection. READ BEFORE WRITING any new PHP code. Source: laraveldaily.com "PHP Attributes in Laravel 13: The Ultimate Guide (36 New Attributes)".
---

# Laravel 13 PHP Attributes — Ultimate Reference

Attributes are **optional** and NOT breaking changes — old property syntax still works. This repo's convention: **attributes only**. New code MUST use attributes, not class properties.

Repo state (Laravel 13.20.0, PHP 8.5):
- Already migrated: `#[Fillable]`, `#[Hidden]`, `#[Table]` on all models; `#[Translatable]` (spatie) on bilingual models; `#[Signature]`/`#[Description]` on commands.
- N/A here: controllers are banned (invokable Actions only), so `#[Middleware]`/`#[Authorize]` don't apply.

Verify attribute signatures against `vendor/laravel/framework/src/Illuminate/**/Attributes/*.php` and `vendor/spatie/laravel-translatable/src/Attributes/*.php` before relying on them.

---

## Eloquent model attributes

Replace model properties. Import from `Illuminate\Database\Eloquent\Attributes\`.

| Attribute | Replaces |
|---|---|
| `#[Fillable([...])]` | `protected $fillable` |
| `#[Guarded([...])]` | `protected $guarded` |
| `#[Unguarded]` (marker) | `protected $guarded = []` |
| `#[Hidden([...])]` | `protected $hidden` |
| `#[Visible([...])]` | `protected $visible` |
| `#[Appends([...])]` | `protected $appends` |
| `#[Table(...)]` | `$table`, `$primaryKey`, `$keyType`, `$incrementing`, `$timestamps`, `$dateFormat` |
| `#[Connection('name')]` | `protected $connection` |
| `#[Touches([...])]` | `protected $touches` |

```php
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Table;

#[Fillable(['title', 'body', 'status'])]
#[Hidden(['password', 'remember_token'])]
#[Table('blog_posts')]
class Post extends Model {}
```

`#[Table]` with all options (named args):

```php
#[Table(
    name: 'external_orders',
    key: 'uuid',
    keyType: 'string',
    incrementing: false,
    timestamps: false,
)]
class ExternalOrder extends Model {}
```

## Queue / job attributes

Import from `Illuminate\Queue\Attributes\`. Combine freely.

| Attribute | Replaces |
|---|---|
| `#[Tries(3)]` | `public $tries` |
| `#[Timeout(120)]` | `public $timeout` |
| `#[Backoff(30)]` or `#[Backoff([10, 30, 60])]` | `public $backoff` (fixed or exponential) |
| `#[MaxExceptions(3)]` | `public $maxExceptions` |
| `#[Queue('high')]` | `public $queue` |
| `#[Connection('redis')]` | `public $connection` |
| `#[UniqueFor(3600)]` | `public $uniqueFor` (needs `ShouldBeUnique`) |
| `#[FailOnTimeout]` (marker) | `public $failOnTimeout = true` |

```php
use Illuminate\Queue\Attributes\{Backoff, Connection, Queue, Timeout, Tries};

#[Connection('redis')]
#[Queue('high')]
#[Tries(3)]
#[Timeout(60)]
#[Backoff([5, 15, 30])]
class ProcessPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
}
```

## Console command attributes

Import from `Illuminate\Console\Attributes\`. Replace `$signature`, `$description`, `$help`.

| Attribute | Replaces |
|---|---|
| `#[Signature('users:prune {--days=30 : Days of inactivity}')]` | `protected $signature` |
| `#[Signature('cache:warm', aliases: ['warm-cache'])]` | `$signature` + `$aliases` |
| `#[Description('...')]` | `protected $description` |
| `#[Help('...')]` | `protected $help` |
| `#[Hidden]` (marker) | `protected $hidden = true` |
| `#[Usage('users:prune --days=60')]` (repeatable) | help-text usage lines |

```php
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;

#[Signature('users:prune {--days=30 : Days of inactivity}')]
#[Description('Prune inactive user accounts')]
class PruneUsers extends Command
{
    public function handle(): void {}
}
```

Multiline signatures: preserve newlines and indentation of `{--...}` continuation lines byte-for-byte inside the attribute string.

## Routing / controller attributes

Import from `Illuminate\Routing\Attributes\Controllers`. N/A in this repo (controllers banned) — listed for completeness.

| Attribute | Replaces |
|---|---|
| `#[Middleware('auth')]` (repeatable, `only:`/`except:`) | constructor `$this->middleware(...)` |
| `#[Authorize('update', Post::class)]` (`only:`/`except:`) | `authorizeResource()` / constructor gates |

```php
use Illuminate\Routing\Attributes\Controllers\Middleware;

#[Middleware('auth')]
#[Middleware('throttle:60,1', except: ['index', 'show'])]
class PostController extends Controller {}
```

## Form request attributes

Import from `Illuminate\Foundation\Http\Attributes`.

| Attribute | Replaces |
|---|---|
| `#[ErrorBag('createPost')]` | `protected $errorBag` |
| `#[RedirectTo('/posts/create')]` | `protected $redirect` |
| `#[RedirectToRoute('posts.create')]` | `protected $redirectRoute` |
| `#[StopOnFirstFailure]` (marker) | `protected $stopOnFirstFailure = true` |

## HTTP resource attributes

Import from `Illuminate\Http\Resources\Attributes`.

| Attribute | Replaces |
|---|---|
| `#[Collects(PostResource::class)]` | `public $collects` |
| `#[PreserveKeys]` (marker) | `public $preserveKeys = true` |

## Factory attributes

| Attribute | Replaces |
|---|---|
| `#[UseModel(Post::class)]` from `Illuminate\Database\Eloquent\Factories\Attributes\UseModel` | `protected $model` |

## Testing attributes

Import from `Illuminate\Foundation\Testing\Attributes`.

| Attribute | Replaces |
|---|---|
| `#[Seed]` (marker) | `protected $seed = true` |
| `#[Seeder(RoleAndPermissionSeeder::class)]` | `protected $seeder` |
| `#[SetUp]` on a method | `protected function setUp()` |
| `#[TearDown]` on a method | `protected function tearDown()` |

```php
use Illuminate\Foundation\Testing\Attributes\SetUp;

class OrderTest extends TestCase
{
    private User $user;

    #[SetUp]
    public function createUser(): void
    {
        $this->user = User::factory()->create();
    }
}
```

---

## Pre-existing attributes (Laravel 11-12, still valid)

### Eloquent — `Illuminate\Database\Eloquent\Attributes\`
- `#[ObservedBy(PostObserver::class)]` — attach observers
- `#[ScopedBy(ActiveScope::class)]` — global scopes
- `#[CollectedBy(PostCollection::class)]` — custom collection
- `#[UseFactory(PostFactory::class)]` — bind factory
- `#[UseEloquentBuilder(PostBuilder::class)]` — custom query builder
- `#[UsePolicy(PostPolicy::class)]` — bind policy
- `#[UseResource(PostResource::class)]` / `#[UseResourceCollection(PostCollection::class)]` — resource binding
- `#[Boot]` / `#[Initialize]` on methods — lifecycle hooks
- `#[Scope]` on methods — local scopes

```php
use Illuminate\Database\Eloquent\Attributes\Initialize;

class Post extends Model
{
    #[Initialize]
    public function setDefaults(): void
    {
        $this->attributes['status'] ??= 'draft';
    }
}
```

### Queue — `Illuminate\Queue\Attributes\`
- `#[DeleteWhenMissingModels]` — delete job when bound model is gone
- `#[WithoutRelations]` — strip relations before serializing

### Container / DI — parameter attributes, `Illuminate\Container\Attributes\`
- `#[CurrentUser]` / `#[Authenticated('web')]` — inject authenticated user
- `#[Config('services.stripe.secret')]` — config value
- `#[Auth('api')]` — auth guard
- `#[Cache('redis')]` — cache store
- `#[DB('analytics')]` / `#[Database('legacy_mysql')]` — DB connection (alias)
- `#[Log('payments')]` — log channel
- `#[Storage('s3')]` — filesystem disk
- `#[Tag('notification.channels')]` — tagged services
- `#[RouteParameter('invoice')]` — route parameter
- `#[Give(StripePaymentGateway::class)]` — specific implementation
- `#[Context('request_id')]` — context repository value

```php
use Illuminate\Container\Attributes\Config;

class PaymentService
{
    public function __construct(
        #[Config('services.stripe.secret')] private string $stripeSecret,
    ) {}
}
```

### Container / service registration — class-level, `Illuminate\Container\Attributes\`
- `#[Bind]` — new instance per resolution
- `#[Singleton]` — one shared instance
- `#[Scoped]` — one instance per request scope

---

## Rules for this repo

1. New model/job/command/request/resource/factory/test code MUST use attributes — no `$fillable`, `$table`, `$signature`, `$tries`, etc. properties.
2. Spatie translatable: `#[Translatable(['name'])]` from `Spatie\Translatable\Attributes\Translatable` (with `HasTranslations` trait), not `public array $translatable`.
3. One attribute per line, directly above the class declaration.
4. Import attribute classes in alphabetical order (pint `ordered_imports` fixes it).
5. After writing attributes, run `vendor/bin/pint` on touched files — it normalizes array indentation and import order.
6. `#[ObservedBy]` NOT used in this repo — observers are registered per-module in service providers to keep module isolation.
7. Controllers banned — no `#[Middleware]`/`#[Authorize]`.
