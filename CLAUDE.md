# Dorak Backend — AI Agent Orchestra (.CLAUDE.md)

## 📚 Source of Truth
Before building, read `docs/02_prd.md` then `docs/feature-index.md` to see what exists.

## 🛑 Rules
- `declare(strict_types=1)`, `final` classes. Controllers banned — use invokable Actions.
- Models suffix `Model`, Actions suffix `Action`. Spatie Translatable for bilingual fields.
- `BaseModuleServiceProvider` extends all providers; it auto-mounts `Routes/Api/V1/*.php` at `/api/v1`.
- Api response shape via `ApiResponseBodyValueObject` + `ApiResponseTrait` (paginated/ok/error).

## 🗺 Feature Index
After adding a feature, append entry to `docs/feature-index.md`.

## 🛠️ AI Agent Skills
Key skills under `.claude/skills/`:
| Skill | Trigger |
|---|---|
| **Strict Backend Architecture** (`10StrictBackendArchitecture`) | Writing Actions, Resources, Requests, or Handlers. Enforces FormRequests, Command objects, strict imports, Resource composition. |

## 🚀 Protocol
1. Read `docs/02_prd.md` + `docs/feature-index.md`
2. Read relevant `.claude/skills/*/SKILL.md`
3. Implement → `composer analyse` (0 new phpstan errors)
