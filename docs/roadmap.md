# Roadmap — Veeduría Abierta

**Status:** Skeleton v0.1 — mirrors `docs/master-plan.md` (vision and scope are human-owned)
**Rule:** Agents propose roadmap updates; the human approves phase boundaries (PR-15).

## Phase 0 — Foundation and governance
**Status:** Completed (2026-10-21)
- Stack installed and verified (ADR-004): Laravel 13, PHP 8.5, Livewire 4,
  Bootstrap 5, PostgreSQL 18, Sail.
- Dedicated ports configured (ADR-010).
- Constitutional layer adopted (`docs/principles.md`, ADR-014).
- Internal language policy adopted (ADR-013).
- Governance skeletons created (this package).

## Phase 1 — SECOP ingestion and explorer
**Status:** In progress (2026-10-21). Conceptual phase **complete** — storage architecture design authorized.
Dataset discovery complete (ADR-001a);
data dictionary populated from verified API metadata and accepted as the
**Version 1 baseline** (Tier A: `jbjy-vk9h` + `p6dx-8zbt`). Conceptual domain
modeling underway; database tables and migrations remain blocked until the
domain model is approved.
Planned (per master-plan): SODA ingestion into immutable staging tables
(ADR-001/002/003), normalization toward OCDS, contract explorer with
pagination, dashboard with basic indicators.

## Deferred phases
- Risk indicator engine — committed conceptually (ADR-008); phase undefined (P-01).
- Social/community layer — name undefined (P-02).
- Indigenous-territory alerts — gated on data-sovereignty preconditions (PR-11).

## Backlog (future ADRs)
- **Personal and sensitive data exposure ADR** — added 2026-10-21 (Decision 3).
  Driven by data-dictionary flags HUMAN-6 (bank-account data) and HUMAN-9
  (personal data). Required before any ingestion, storage, or display of those
  fields; they must not be exposed until this ADR is accepted.
  **Accepted** as **ADR-020** (binding exposure register; LQ-1…LQ-6 open legal
  review questions). OCDS storage strategy resolved as ADR-018 (Hybrid);
  OCDS 1.1.5 pinned via ADR-019.

## Open decisions
| ID | Decision | Status |
|---|---|---|
| P-01 | Phase for the risk indicator engine | Undefined |
| P-02 | Name for the social/community layer | Undefined |
| P-05 | OCDS storage strategy (Releases vs. Records vs. Hybrid — Q-1) | Resolved via ADR-018 (2026-10-21): Hybrid — releases canonical + derived current-state projection |
| P-06 | Spanish wording and tone of the public narrative (`docs/vision.md` §6) | Open — narrative pending human approval; Master Plan v2 adopted 2026-10-21 (citations resolved) |
| Q-1 | Releases vs. Records vs. Hybrid (constraint behind P-05) | Closed by ADR-018 |
| P-04 | Adopt Laravel Boost as versioned-docs source | Resolved via ADR-015 (2026-10-21): not adopted now; controlled evaluation protocol defined |
