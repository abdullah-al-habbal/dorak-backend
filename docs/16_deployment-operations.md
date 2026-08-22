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

## TODO 47 — Migration strategy (expand/contract required)

Migrations run **before** the atomic swap:

```text
migrate  →  route:cache  →  SWAP  →  config:cache  →  view:cache  →  health
```

Between `migrate` and the swap, the **previous** release serves traffic against
the **new** schema. That is safe only for backward-compatible migrations.

Required policy:

```text
expand schema (additive, nullable, new tables/columns)
      ↓
deploy application compatible with BOTH shapes
      ↓
migrate/backfill data
      ↓
contract schema in a LATER release
```

**Do not** move migrations after the swap to avoid this — that trades one
incompatibility window (old code / new schema) for another (new code / old
schema) and is strictly worse, because the new code is the one under test.

A concrete example of the risk already occurred: `8a07a89` removed `neutral`
from `UniverseEnum`/`Universe` and changed the `create_*_table` defaults, with
no data migration. Any database holding `preferred_universe = 'neutral'` then
throws `ValueError` on **every client read**. The dev server is safe only
because its database is empty (0 clients, 0 brands).

---

## TODO 48 — Database rollback policy

```text
application rollback  ≠  database rollback
```

The deployment restores the previous release directory. It does **not** revert
migrations, and it never will automatically.

The trap warns explicitly whenever migrations already ran:

```text
⚠️  DATABASE MIGRATIONS WERE ALREADY APPLIED AND ARE NOT ROLLED BACK.
⚠️  The restored release is running against a NEWER schema.
⚠️  Verify the application manually before assuming recovery.
```

Per migration class:

| Kind | On rollback |
|---|---|
| Backward-compatible (additive, nullable) | Safe. Restored code ignores the new columns |
| Destructive (drop/rename/narrow) | **Unsafe.** Restored code queries columns that no longer exist. Manual restore from backup |
| Enum value changes | **Unsafe.** Rows holding a retired value throw on cast — the `neutral` failure mode |
| Data transformations | **Unsafe unless reversible.** Requires an explicit down-migration written in advance |

**This deployment does not have full rollback support.** It has application
rollback with a loud database warning. Claiming otherwise would be wrong.

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
