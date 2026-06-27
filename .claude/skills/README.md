# Dorak Backend — AI Agent Skills

This directory contains focused guides for AI agents working on this codebase. Each file covers one aspect of the architecture and conventions.

| Skill | Description |
|-------|-------------|
| [architecture.md](architecture.md) | Module structure, bootstrap flow, PSR-4 mapping |
| [coding-standards.md](coding-standards.md) | PHP conventions, attributes, enums, naming |
| [how-to-add-a-module.md](how-to-add-a-module.md) | Steps to create a new domain module |
| [how-to-add-a-route.md](how-to-add-a-route.md) | Defining API and web routes per module |
| [cqrs-pattern.md](cqrs-pattern.md) | Command/Query flow: Request → Action → Handler → Resolver |
| [api-response-standard.md](api-response-standard.md) | Unified JSON response format and helpers |
| [translation-conventions.md](translation-conventions.md) | i18n with `core::` namespace and TranslatorHandler |
| [logging-and-tracing.md](logging-and-tracing.md) | LoggerService and request_uuid tracing |
| [database-conventions.md](database-conventions.md) | Migrations, factories, Eloquent patterns |
| [feature-flags.md](feature-flags.md) | Laravel Pennant for freemium gating |

Also read the project-level [AGENTS.md](../../AGENTS.md) for quick-start commands and config notes.
