# Documentation Map — Veeduría Abierta

**Status:** Skeleton v0.1 · 2026-10-21
**Purpose:** Single change-impact routing table. Before completing any task, the
authoring agent checks this map: every file listed as affected must be updated,
or the update explicitly deferred with a reason (AGENTS.md — Definition of Done, item 3).

## How to use
1. Locate the file being changed (left column).
2. Review/update every file in its "Must be reviewed / updated" column.
3. If the change touches a constitutional or human-decision file, stop and
   propose — do not write (PR-15).

## Routing table

| Changed file | Must be reviewed / updated | Authority |
|---|---|---|
| `docs/principles.md` | `docs/decision-log.md` (amendment ADR), `AGENTS.md` | Human only — Tier E never weakened |
| `docs/master-plan.md` | `docs/roadmap.md`, `docs/decision-log.md` (if scope changes) | Human only |
| `docs/decision-log.md` | Files named in the accepted ADR; `CHANGELOG.md` | Human accepts; agents may draft entries |
| `AGENTS.md` | `.pi/agents/_template.md` (if the authority model changes), this file | Human only |
| `docs/data-dictionary.md` | Any migration/model touching SECOP fields; `docs/glossary.md` | Fields added only from verified API metadata (PR-06) |
| `docs/glossary.md` | Citizen-facing UI strings that use the term | Agent proposes; human approves wording |
| `docs/roadmap.md` | `docs/master-plan.md` (only if phases are redefined) | Human approves phase changes |
| `CHANGELOG.md` | — (append-only) | Author of the accepted change |
| `ATTRIBUTION.md` | `README.md` (if licenses change) | Agent proposes; human approves |
| `.pi/agents/_template.md` | Every existing agent charter | Human approves charter changes |
| `.pi/memory/docs-map.md` | — (self-maintaining) | Project Architect owns once active; human approves |

## Known stale references (tracked issues)
- **N-1 (applied 2026-10-21):** `docs/master-plan.md` references reconciled to
  English filenames (`docs/data-dictionary.md`, `docs/ingestion-runbook.md`,
  `master-plan.md`, `decision-log.md`).
- **AGENTS.md references (applied 2026-10-21):** rules 2 and 4 now cite
  `docs/data-dictionary.md`.
