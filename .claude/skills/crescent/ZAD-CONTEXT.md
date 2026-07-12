# Zad Team Context for CRESCENT

The shared-team contract every CRESCENT phase obeys. Not a skill — no frontmatter. The `crescent` orchestrator loads this on every entry.

## Naming

- **Jira ID** — uppercase Jira issue key of the parent epic (e.g. `ZADCMS-1234`). Always uppercase.
- **Feature slug** — kebab-case, lowercase, hyphen-separated, max 50 chars (e.g. `prayer-times-widget`). Derived by the agent from the Jira summary and confirmed with the user before any file is written.

Never proceed past Phase 1 without both values fixed in writing in `IDEA.md`.

## Artifact paths

All CRESCENT artifacts for a single feature live under one directory:

```
docs/<JIRA-ID>/<feature-slug>/
├── IDEA.md            # Phase 1
├── research.md        # Phase 2 (omit if no externals; note the skip in IDEA.md)
├── prototype/         # Phase 3 — colocated prototype code (deleted at Phase 4 unless absorbed)
├── PRD.md             # Phase 4
├── kanban.md          # Phase 5 — index pointing at GitLab issue IDs; does NOT duplicate issue bodies
├── qa-plan.md         # Phase 7
└── NOTES.md           # Cross-phase notes, state-machine resets, decisions
```

Hard rules:

- **Never** write CRESCENT artifacts outside `docs/<JIRA-ID>/<feature-slug>/`.
- **Never** write to `.kiro/specs/` from a CRESCENT phase — that path is owned by the Kiro workflow defined in `wp-cms/CLAUDE.md`.
- **Never** rename a slug after Phase 1 without renaming the directory and recording the rename in `NOTES.md`.

## Issue tracker — split responsibility

- **Parent epic** lives in Jira under `<JIRA-ID>`, managed via `acli` per `wp-cms/CLAUDE.md`. PRD body is mirrored into the epic.
- **Per-ticket execution issues** (Phase 5 output, Phase 7 follow-ups) live in **self-hosted GitLab at `gitlab.zadapps.info`**, created via the `glab` CLI.

`glab` must be authenticated before Phase 5 runs:

```bash
glab auth status -h gitlab.zadapps.info
# if not authenticated:
glab auth login -h gitlab.zadapps.info
```

### GitLab issue conventions

- **Title prefix:** `[<JIRA-ID>] <imperative summary>`
- **Body must include:**
  - `**Parent:** <Jira URL>`
  - `**Feature slug:** <slug>`
  - `**Crescent docs:** docs/<JIRA-ID>/<feature-slug>/`
- **Triage label** on creation: `ready-for-agent` (these are CRESCENT-generated, AFK-ready by construction)

## Branches and MRs

- **Branch name:** `<type>/<JIRA-ID>-<feature-slug>` where `<type>` ∈ `feature | bugfix | refactor | chore`.
- **MR title:** `[<JIRA-ID>] <imperative summary>`.
- **MR description must include:**
  - A link back to `docs/<JIRA-ID>/<feature-slug>/`
  - The GitLab issue it closes (`Closes #N`)
  - A reference to the Jira epic

## Authority order

When two rules conflict, follow this order — top wins:

1. `wp-cms/CLAUDE.md` (project rules — never overridden by CRESCENT)
2. This file
3. The active phase's `SKILL.md`
4. Any per-session user instruction (only overrides 3, never 1 or 2 unless the user is explicit)
