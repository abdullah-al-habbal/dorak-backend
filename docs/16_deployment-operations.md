# 16 — Deployment Operations & Known Limitations

Scope: the atomic-release deployment in `.github/workflows/deploy.yml` and the
issues deliberately **not** solved by it. Written during the deployment repair
that fixed the `config:cache`-before-swap bug.

Each section below is a standing record, not a task list for that repair.

---

## Root cause this deployment repair fixed

`config:cache` ran **before** the atomic swap. Laravel serialises absolute
filesystem paths, so the cached config baked
`…/dev_release_<timestamp>/storage/…`. The next line renamed that directory to
`dev`, leaving every baked path dangling. Laravel's local driver then recreated
the release directory on first write — the "ghost release".

Confirmed on the live server, not inferred:

```text
dev/bootstrap/cache/config.php          20 distinct *_release_* paths
  logging single path   -> …/dev_release_20260820_011154/storage/logs/laravel.log
  view compiled         -> …/dev_release_20260820_011154/storage/framework/views
  public disk root      -> …/dev_release_20260820_011154/storage/app/public
  onboarding source[0]  -> …/dev_release_20260820_011154/database/seeders/assets/…jpg
                           exists? NO   (real file exists under dev/)

dev_release_20260820_011154/            NOT a git repository, 14 files:
  storage/framework/views/*.php         13 compiled Blade files
  storage/logs/laravel-2026-08-20.log   9289 bytes, actively written
dev/storage/logs/                       only .gitignore (14 bytes)
```

The ghost contains exactly the three baked paths that get *written* to. Nothing
else was ever there.

**Fix:** `config:cache` and `view:cache` moved after the swap; `route:cache`
stays before it (path-independent, release-local `bootstrap/cache`).

---

## TODO 44 — Composer security advisories (separate task)

Eight pre-existing advisories, **not addressed** by the deployment repair:

| Package | Version | Advisories |
|---|---|---|
| `guzzlehttp/guzzle` | 7.15.1 | 2 (one **high** — CVE-2026-69246, noncanonical host bypasses host-based checks; CVE-2026-69245, noncanonical cookie domain keeps subdomain scope) |
| `league/commonmark` | 2.8.3 | 6 (three **high** — DoS via colliding heading slugs, duplicate footnote definitions, deeply nested XML output) |

**Verified unchanged by this work.** `composer.lock` diff is a single line (the
`content-hash`); a package-by-package comparison against `HEAD` shows
**181 packages both sides, 0 version changes**. Both packages are transitive.

Not silently ignored. Owner action: schedule a dependency-update task.

---

## TODO 45 — Pre-existing issues, deliberately untouched

| Issue | Detail |
|---|---|
| Larastan `level: max` errors | **15**, identical count before and after `8a07a89`. Files: `ClientFaceProfile/Services/{FaceAnalysisService,FaceAnalysisResponseParser}`, `Marketing/…/GetMarketingPageRequest`, `Client/Jobs/SendEmailVerificationCodeJob`, `Client/Handlers/Client/{VerifyEmailHandler,RegisterHandler}` |
| Stale PHPStan baseline | `phpstan-baseline.neon` ignores a `GetMarketingPageRequest::getLocale()` error that no longer occurs. `ignore.unmatched` is non-ignorable, so a *fixed* bug now fails the gate |
| `.env.testing` mail config | still carries an empty `MAIL_URL=`. Inert today (`MAIL_MAILER=log`), but an empty `MAIL_URL` passes `isset()` and nulls the transport |
| Development-only OTP logging | `ForgotPasswordHandler` + `SendEmailVerificationHandler` log the generated OTP, gated on `app()->environment('local')`. Committed in `8a07a89`. **Must be stripped before anything merges toward staging** |
| `modules/Core/Enums/Universe.php` | one added blank line, unrelated to any task, left as found |
| `APP_URL` is mandatory | `modules/Core/Config/filesystems.php:20` evaluates `rtrim(env('APP_URL'), '/')` with **no default**. Any environment missing `APP_URL` dies with `TypeError: rtrim(): Argument #1 ($string) must be of type string, null given`. This is why CI must use `composer install --no-scripts` |

---

## TODO 46 — Production shared path is not isolated

The shared directory is **dev-specific**:

```text
/home/u685718414/domains/dev-dorak-backend.io/shared
├── .env
└── storage/          (created by the deployment)
```

`SHARED_DIR` is a single hardcoded value in `deploy.yml`. A `main`/production
deployment reusing this workflow would symlink the **dev** `.env` and write into
**dev** storage — production booting on development credentials.

**Blocking requirement before `main` deployment is ever enabled:** a separate,
explicitly isolated shared directory and its own `TARGET`.

Interim protection, already implemented and tested: the runtime branch guard in
`deploy.yml` rejects any ref other than `dev` (`dev` → allowed, `main` → exit 1,
`feature/x` → exit 1). Note that `workflow_dispatch:` does **not** support a
`branches:` filter — GitHub silently ignores it — so the runtime guard is the
only real protection, not the trigger configuration.

---

## Migration safety — the expand/contract policy (enforced)

### Why it matters here

Migrations run **before** the atomic swap:

```text
migrate  →  route:cache  →  SWAP  →  config:cache  →  view:cache  →  health
```

Between `migrate` and the swap, the **previous** release serves traffic against
the **new** schema. If a later step fails, the trap restores the old code and
the schema stays migrated. Only backward-compatible migrations survive that.

### What the current 56 migrations actually contain

Audited 2026-08-23 with `php scripts/check_migrations.php --all`:

| Category | Count |
|---|---|
| `CREATE`-only (new tables) | 52 |
| Additive `ALTER` | 4 |
| **Destructive operations in any `up()`** | **0** |
| Non-empty `down()` | 56 / 56 |

All four ALTERs add nullable or defaulted columns only:

```php
$table->decimal('travel_radius', 5, 2)->nullable()                  // barbers
$table->string('phone')->nullable(); $table->string('avatar')->nullable();
$table->string('status', 20)->default('enabled'); $table->softDeletes();
$table->json('requirements')->nullable(); …->string('type')->nullable()
$table->foreignId('catalog_item_id')->nullable()->constrained()->nullOnDelete()
```

**Every migration in the repository is already expand-safe.** The pre-swap
ordering is correct for the current set; the policy exists to keep it that way.

### The rule

**Expand — safe, ships in one release:**

- new tables
- new columns that are `nullable()` or have a `default()`
- new indexes
- new **nullable** foreign keys

**Contract — must be a separate, later release:**

- dropping or renaming a column or table
- narrowing a type, or `->change()` of any kind
- adding `NOT NULL` without a default to a populated table
- **removing an enum case, or narrowing a cast**
- data transformations

**Never:** edit a migration that has already shipped. Add a new one.

### Enum and cast changes are contract operations

This is the class that actually caused an incident, and it involves **no
migration at all**, so no schema review would have caught it.

`8a07a89` removed `neutral` from `UniverseEnum` and `Core\Enums\Universe`, and
edited the shipped `create_clients_table` default. Every row still holding
`preferred_universe = 'neutral'` then threw on read:

```text
ValueError: "neutral" is not a valid backing value for enum Modules\Core\Enums\Universe
```

Locally that was 3/3 clients and 2/2 brands — every client read, including the
login path. The dev server escaped only because its database was empty. The same
mistake had already happened once in the opposite direction; the note recording
it was deleted in the same commit.

Correct sequence for retiring an enum value:

```text
release N    accept the new value; keep the old case in the enum
release N+1  data migration:  UPDATE clients SET preferred_universe='men'
                              WHERE preferred_universe='neutral'
             plus an ALTER migration for the column default
release N+2  remove the old case from the enum
```

Note that changing a `create_*_table` default does **not** alter an existing
database. `migrate` never re-runs an applied migration, so the live column
default stays as it was. Defaults on existing tables need their own `ALTER`
migration.

### Enforcement

`scripts/check_migrations.php`, run by `backend-ci.yml` on every push and PR.
It fails the build on:

1. destructive operations in the `up()` of a **new** migration;
2. modification of a migration that has **already shipped**.

Deliberate contract releases opt out per file, which makes the decision visible
in review:

```php
// @contract-migration: drop legacy column, no code has referenced it since v2.3
```

Run it locally:

```bash
php scripts/check_migrations.php               # vs origin/dev
php scripts/check_migrations.php --all         # every migration
php scripts/check_migrations.php --base=<ref>
```

Verified against seven cases: `dropColumn`, `->change()`, and NOT-NULL-without-
default are rejected; nullable/defaulted additions, `CREATE` with NOT NULL
columns, and waived destructive changes are allowed; editing a shipped migration
is rejected. All 56 current migrations pass.

**What the guard does not cover:** enum-case removal and cast narrowing. Those
live in `Enums/` and `casts()`, not in migrations, and a reliable automated check
produced too many false positives on legitimate refactors. They remain a review
responsibility — the sequence above is the checklist.

---

## Database rollback policy

```text
application rollback  ≠  database rollback
```

The deployment restores the previous release directory. It does **not** revert
migrations, and it never will automatically.

The trap warns whenever migrations already ran:

```text
⚠️  DATABASE MIGRATIONS WERE ALREADY APPLIED AND ARE NOT ROLLED BACK.
⚠️  The restored release is running against a NEWER schema.
⚠️  Verify the application manually before assuming recovery.
```

Per migration class:

| Kind | On rollback |
|---|---|
| Expand (additive, nullable/defaulted) | **Safe.** Restored code ignores the new columns. This is every migration in the repo today. |
| Destructive (drop / rename / narrow) | **Unsafe.** Restored code queries columns that no longer exist. Restore from backup. |
| Enum value removal | **Unsafe.** Rows holding a retired value throw on cast — the `neutral` failure mode. |
| Data transformation | **Unsafe unless a real `down()` exists.** Most `down()` here only drop tables, which does not undo data changes. |

Because the policy keeps every migration in the expand class, code rollback is
safe in practice **today**. That guarantee holds only for as long as the guard
does. A waived contract migration removes it for that release, which is the
point of making the waiver explicit.

`down()` coverage: all 56 migrations have a non-empty `down()`, and all of them
drop. That is correct for `CREATE` migrations and lossy for the four ALTERs —
rolling those back discards the added columns' data. `down()` is a development
convenience here, not a production recovery mechanism. Production recovery is
restore-from-backup.

---

## TODO 49 — Compiled-view retention

`storage/framework/views` lives in **shared** storage, and Blade names each
compiled view by a hash of its **absolute** source path. Every release therefore
adds its own complete set of compiled views. They accumulate.

The deployment deliberately **does not** clear them:

- `view:clear` before the swap would empty the shared directory while the
  **previous** release is still serving — a recompile storm on live traffic.
- `view:clear` after the swap would destroy compiled views the rollback target
  (`${TARGET}_old`) still relies on.
- `view:cache` only ever writes, and overwrites what it needs, so clearing buys
  nothing.

Same reasoning removed `optimize:clear` from the pre-swap phase entirely: it
runs `cache:clear` and `view:clear`, and by that point `storage` is already
symlinked into `$SHARED_DIR`.

Pruning is a **separate controlled mechanism**, not a deployment step. Required
properties when built:

1. Never touch views belonging to the live release or to `${TARGET}_old`.
2. Run on a schedule, not in the deployment path.
3. Age-based, with a floor (never empty the directory).

Scale check: the growth is a few dozen small PHP files per release. Low urgency;
correctness of the live app takes priority over disk tidiness.

Release **directories** are handled separately and are bounded — `deploy.yml`
prunes to `KEEP_RELEASES` (3) after a successful health check, with three
guards: never the live release, never `${TARGET}_old`, never whatever `current`
resolves to.
