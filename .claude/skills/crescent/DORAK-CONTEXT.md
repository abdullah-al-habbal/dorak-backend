# Dorak Context for CRESCENT

## Repository Structure
- **Backend:** Laravel 13 modular monolith
- **Frontend:** Flutter monorepo (3 apps + dorak_core)

## GitHub Integration
- **No Jira/GitLab.** We use GitHub Issues and PRs exclusively.
- **Backend repo:** abdullah-al-habbal/dorak-backend
- **Frontend repo:** abdullah-al-habbal/dorak-frontend-mobile
- **Branch naming:** feature/<slug>, bugfix/<slug>

## Documentation Structure
All features live in docs/features/<feature-slug>/:
- IDEA.md (Phase 1)
- research.md (Phase 2)
- prototype/ (Phase 3)
- PRD.md (Phase 4 - uses Dorak 11-file Harness format)
- kanban.md (Phase 5 - GitHub issue index)
- qa-plan.md (Phase 7)

## Dorak-Specific Rules
1. PRD Format: Use the 11-file Harness structure.
2. Feature Index: Append to docs/feature-index.md after completion.
3. Code Standards: Obey 10StrictBackendArchitecture (backend) and 10StrictTypingAndAtomicity (frontend).
