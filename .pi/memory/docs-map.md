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
| `docs/vision.md` | `docs/master-plan.md` §2 (if mission/scope language shifts); `docs/decision-log.md` (domain-expansion ADRs, per ADR-017) | Human only (accepted baseline via ADR-017); domain additions each require their own ADR |
| `docs/founders-intent.md` | `docs/decision-log.md` (adoption/substantive-change ADR); `CHANGELOG.md` | Founder only, in their own voice (ADR-022). Informative layer (level 7): yields to all higher norms; agents may format/transcribe/propose, never alter meaning |
| `AGENTS.md` | `.pi/agents/_template.md` (if the authority model changes), this file | Human only |
| `docs/data-dictionary.md` | Any migration/model touching SECOP fields; `docs/glossary.md` | Fields added only from verified API metadata (PR-06) |
| `docs/glossary.md` | Citizen-facing UI strings that use the term | Agent proposes; human approves wording |
| `docs/roadmap.md` | `docs/master-plan.md` (only if phases are redefined) | Human approves phase changes |
| `CHANGELOG.md` | — (append-only) | Author of the accepted change |
| `ATTRIBUTION.md` | `README.md` (if licenses change) | Agent proposes; human approves |
| `.pi/agents/_template.md` | Every existing agent charter | Human approves charter changes |
| `.pi/agents/<agent>.md` (any charter) | `AGENTS.md` (§ Agent Files, ADR-016), this file | Charter approved by human; frontmatter must match the charter (ADR-016) |
| `docs/decision-log.md` (ADR-016) | `AGENTS.md`, `.pi/agents/_template.md`, every existing agent charter, `CHANGELOG.md` | Human accepted; Architect applied the knock-on edits |
| `.pi/memory/docs-map.md` | — (self-maintaining) | Project Architect owns once active; human approves |

## Known stale references (tracked issues)
- **N-1 (applied 2026-10-21):** `docs/master-plan.md` references reconciled to
  English filenames (`docs/data-dictionary.md`, `docs/ingestion-runbook.md`,
  `master-plan.md`, `decision-log.md`).
- **AGENTS.md references (applied 2026-10-21):** rules 2 and 4 now cite
  `docs/data-dictionary.md`.
- **ADR-016 applied (2026-10-21):** frontmatter added to
  `.pi/agents/project-architect.md`; §0 added to `.pi/agents/_template.md`;
  "Run tests" rows rephrased in both (execution requires explicit
  authorization and an approved execution path); `AGENTS.md` gains the
  "Agent Files (Pikit Subagents)" section.
- **ADR-018 applied (2026-10-21):** OCDS storage strategy recorded (Hybrid); ADR numbering
  reconciled — storage = ADR-018, OCDS pin draft = ADR-019, personal-data draft = ADR-020;
  P-05 = storage (resolved), narrative-tone item renumbered to P-06; data dictionary,
  conceptual model, and roadmap cross-references updated; decision-log ADR-004/006/010
  headings repaired (were appended mid-line).
- **Master Plan v2 applied (2026-10-21):** revision 0.2 written to `docs/master-plan.md`
  per human approval; changelog entry queued in `CHANGELOG.md` [Unreleased]. P-06 now
  covers only the narrative tone. Next gates: ADR-019/ADR-020 acceptance.
- **ADR-017 applied (2026-10-21):** `docs/vision.md` adopted as baseline vision document
  (renamed from `docs/vision-draft.md`); routing row added above; domain-expansion gate
  (one ADR per domain) recorded. Master Plan v2 drafted for human review
  (`.pi/artifacts/draft-master-plan-v2.md`); P-06 covers its adoption.
- **ADR-022 applied (2026-10-21):** `docs/founders-intent.md` adopted as informative-layer
  founder testimony (v1.0); routing row added above; supremacy clause (constitution wins
  over founder testimony) recorded in ADR-022.
- **ADR-021 applied (2026-10-21):** Data Ingestor charter v1.0 added
  (`.pi/agents/data-ingestor.md`, Accepted; active upon human `/reload`). Runbook
  operational sections (§5–§9) ownership transferred to the Data Ingestor; the
  Architect charter's runbook row updated to coordination-only (cross-references,
  docs-map routing). Generic `.pi/agents/<agent>.md` routing row above applies —
  no new routing rule. First recommended task post-activation: smoke validation
  (schema-change pre-flight + read-only completeness baseline; no bulk load).
- **Open knock-on (tracked):** user-scope charters in `~/.pi/agent/agents/`
  (`gpon-architect`, `issue-reviewer`, `laravel-livewire-reviewer`,
  `livewire-bootstrap-reviewer`, `session-analyst`, `task-guide`) predate
  ADR-016. Those outside this repository are not governed by the docs-map;
  flagged to the human for an out-of-repo consistency check (their "Run tests"
  wording, if any, may carry the same unfulfillable-without-`bash` pattern).
