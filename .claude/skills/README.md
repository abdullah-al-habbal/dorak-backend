# Dorak Backend — AI Agent Skills

This directory contains focused guides for AI agents working on this codebase. Each file covers one aspect of the architecture and conventions.

| Skill | Description |
|-------|-------------|
| [architecture](architecture/SKILL.md) | Module structure, bootstrap flow, PSR-4 mapping |
| [coding-standards](coding-standards/SKILL.md) | PHP conventions, attributes, enums, naming |
| [cqrs-pattern](cqrs-pattern/SKILL.md) | Command/Query flow: Request → Action → Handler → Resolver |
| [api-response-standard](api-response-standard/SKILL.md) | Unified JSON response format and helpers |
| [translation-conventions](translation-conventions/SKILL.md) | i18n with `core::` namespace and TranslatorHandlerService |
| [logging-and-tracing](logging-and-tracing/SKILL.md) | LoggerService and request_uuid tracing |
| [database-conventions](database-conventions/SKILL.md) | Migrations, factories, Eloquent patterns |
| [feature-flags](feature-flags/SKILL.md) | Laravel Pennant for freemium gating |
| [how-to-add-a-module](how-to-add-a-module/SKILL.md) | Steps to create a new domain module |
| [how-to-add-a-route](how-to-add-a-route/SKILL.md) | Defining API and web routes per module |
| [testing](testing/SKILL.md) | Test structure, conventions, and running tests |

Also read the project-level [AGENTS.md](../../AGENTS.md) for quick-start commands and config notes.
