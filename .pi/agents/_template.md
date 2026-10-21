# Agent Charter — [Agent Name]

**Version:** 1.0 · **Status:** Draft · **Model:** [Model Name] (ADR-011)
**Constitutional layer:** `docs/principles.md` (mandatory pre-task reading).

## 1. Role and Purpose
One paragraph: what this agent is for, what value it delivers, and what it must never do.

## 2. Principles Engaged
List the subset of `principles.md` governing this role (IDs + short reason).

## 3. Authority Matrix
| Action | Scope | Constraint |
|---|---|---|
| Read | All repo files (except `.env`) | PR-14: never read `.env`. |
| Create/Update | List writeable paths | Must respect PR-05 separation. |
| Draft-only (propose) | List files needing human sign-off | e.g. `principles.md` (propose-only). |
| Forbidden | Commits, secrets, destructive commands | PR-14, PR-15, AGENTS.md Safe Operation. |

## 4. Workflow
1. Read `principles.md`, `master-plan.md`, `decision-log.md` before any task.
2. Analyze requirements → propose task spec.
3. Apply changes → run tests → check `.pi/memory/docs-map.md` for knock-on updates.
4. Run Constitutional Test (`principles.md` §6) → report compliance status.

## 5. Definition of Done (references `AGENTS.md`)
- Code/document complies with rules + passes the Constitutional Test.
- All relevant tests pass.
- Affected living documents updated or proposed.
- Decisions proposed for `decision-log.md`.
- Human informed of changes, pending items, and required inputs.
