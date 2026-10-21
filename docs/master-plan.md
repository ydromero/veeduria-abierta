# Master Plan — Open Oversight (Veeduría Abierta)

*A living document. Modified only by humans. Version 2 · Drafted 2026-10-21.*

## 0. Position and precedence

This document is the **product-scope layer** of the initiative (level 3 in the hierarchy
of norms, `principles.md` §1.1):

| Question | Canonical source |
|---|---|
| *Why do we exist? Where are we going?* | `docs/vision.md` (purpose and long-term direction — mission, theory of change, community model, governance principles) |
| *What may we never do?* | `docs/principles.md` (values and constraints, PR-01…PR-15) |
| *What have we decided, and how?* | `docs/decision-log.md` (ADRs) |
| *What are we building, for whom, in what scope, and what must each phase achieve?* | **This document** |

Conflict resolution follows `principles.md` §1.3. A lower-level norm never contradicts a
higher one; conflicts escalate to the human.

## 1. What we are building

An open-source civic platform for the auditing, exploration, and forensic analysis of
Colombian public procurement, beginning with SECOP II. It distinguishes itself by
standardizing information according to the global OCDS model, maintaining the immutability
of the raw official data, and — in advanced phases — surfacing **risk indicators, never
legal judgments**.

The platform is the **first implementation of the initiative's mission** at the level of
public spending (`docs/vision.md` §1: mission stated at the level of *public action*;
procurement is the current implementation focus; every new domain requires its own ADR —
see §5.4).

**Normalization target.** OCDS, pinned to **version 1.1.5** (draft ADR-019, pending
acceptance). Structural storage strategy: **Hybrid — immutable releases as canonical,
plus a derived current-state projection** (ADR-018, accepted).

## 2. Mission and vision

Canonical: `docs/vision.md`. In one sentence: to give Colombian citizens the data, the
method, and the community they need to verify how public power acts — so that oversight
is something citizens *do*, not something they consume. Guiding phrase: *"a view of
Colombia that no one else shows you."*

## 3. Who it serves

Product audiences (community roles and progression: `docs/vision.md` §4):

1. **The average citizen** — to understand how tax money is spent in their municipality,
   simply and educationally. Served by plain-language views that always expose the
   derivation path (PR-01) and state limitations (PR-08).
2. **Civic monitors, journalists, and researchers** — forensic depth: complex filters,
   network graphs, and immutable timelines of OCDS *Releases* (delivered structurally by
   ADR-018). They cite our provenance (PR-05) and reliability levels (PR-07).
3. **Civic developers** — MIT-licensed open source: they reuse the platform's data model
   and method to build new civic tools.

## 4. Epistemic contract (citation section)

- **Reliability levels** (Level 1/2/3, sentinel values, gap flags): canonical definitions
  and per-field classifications live in `docs/data-dictionary.md`; the duties are
  PR-07 (graded honesty) and PR-08 (limitations are part of the content).
- **Raw-data immutability and AI-content separation:** PR-05, PR-12, ADR-007.
- **Personal and sensitive data** (bank details, natural-person identifiers, sole-trader
  supplier IDs): excluded from all exposure paths until the handling ADR is accepted
  (draft ADR-020; dictionary flags HUMAN-6/HUMAN-9).

## 5. Scope and phases

Phase *status* is tracked in `docs/roadmap.md`; this document defines what each phase
**is** and what it must achieve.

### 5.1 Phase 1 — SECOP II ingestion and explorer (current)

**In scope:**
- Ingestion of the Tier A datasets (`jbjy-vk9h` — Contratos Electrónicos; `p6dx-8zbt` —
  Procesos de Contratación; authoritative inventory: ADR-001a + `docs/data-dictionary.md`
  §1) from the SODA API into staging tables of **immutable raw data** (ADR-001/002/003).
- Normalization toward OCDS under the ADR-018 structure: synthesized immutable releases
  (one per observed state change) + derived, labeled current-state projection.
- Contract explorer with pagination and a dashboard with basic indicators — every surface
  carrying method, source, reliability level, and limitations (PR-01/07/08).

**Out of scope for Phase 1:** red-flag engine (committed conceptually, ADR-008; phase
undefined — P-01), user accounts, the social layer (name undefined — P-02), congressional
modules, Tier B dataset ingestion, and any physical-execution or budget cross-source data
(Level 3).

### 5.2 Phase 1 exit criteria (proposal — for human approval)

1. All Tier A data ingested into the immutable raw layer, each record carrying provenance
   (source dataset, cursor window, retrieval timestamp) — PR-05.
2. Normalized layer implemented per ADR-018: release-construction rule documented in the
   ingestion runbook; projection derived, labeled, and traceable (projection → release → raw).
3. Data dictionary validated for everything citizens can see (PR-06): every field that
   reaches citizen-facing surfaces or is used by citizen-facing functionality is
   human-validated — no unresolved HUMAN/UNKNOWN/AMBIG flag blocks exposure of such data.
   Unresolved flags on fields that are **not** citizen-facing or exposed (internal-only,
   or Tier B/deferred) do **not** block Phase 1 completion; they remain tracked in the
   data dictionary as backlog and become blocking only when a use that would expose them
   is proposed. Candidate OCDS mappings for citizen-exposed fields are elevated or
   rejected explicitly at validation time; mappings for non-exposed fields may remain
   candidates.
4. Personal/sensitive data ADR (ADR-020) accepted and its exposure rules implemented
   before any citizen-facing surface renders affected fields.
5. Explorer and dashboard shipped, passing the Constitutional Test (`principles.md` §6)
   surface by surface; tests green against the `testing` database (ADR-006).

### 5.3 Subsequent phases (no dates; each begins by human decision)

- **Red-flag engine** (ADR-008; P-01): risk indicators documented individually (formula,
  OCDS paths, thresholds, limits, false positives), human-validated before exposure.
- Graph layer for entities, contracts, contractors, and consortia.
- Cross-referencing with SIGEP II, Cuentas Claras, and congressional voting records.
- A reasoned, searchable record of congressional debates (a gap not covered by
  *Congreso Visible* or the MOE).
- Citizen social layer, one account per person (identity verification via national ID —
  challenging, yet open; P-02).
- Alert module for Indigenous territories: **will not be developed** without first
  resolving data sovereignty, ILO Convention 169 / Law 21 of 1991, and security risks
  (PR-11 — hard stop).
- Absorption of Tier B amendments (`u8cx-r425`) into the release model (ADR-018
  non-decision; own increment).

### 5.4 Domain-expansion gate (per ADR-017)

The mission (`docs/vision.md` §1) spans public action generally; **procurement is the
current implementation focus**. Any new domain — legislation, public programs, public
services, participation, other public information — requires **its own ADR** covering at
minimum: source validation, reliability analysis, scope definition, architectural impact
assessment, and human approval; and must pass PR-05, PR-06, PR-07, and PR-09 gates.

## 6. Source of truth and data policy (citation section)

- **Dataset inventory** (Tier A/B/C, 952 catalog results → 16 primary datasets): ADR-001a
  and `docs/data-dictionary.md` §1 — never restated here.
- **Ingestion mechanics** (cursor pagination on `:id`, SODA auth, transactions, queue):
  ADR-002/003/005 and `docs/ingestion-runbook.md`.
- **Conceptual domain model** (canonical entities, hard constraints): draft — pending
  human acceptance (`.pi/artifacts/draft-conceptual-data-model.md`).

## 7. Normative decisions register

Entry point to `docs/decision-log.md` (authoritative text; one-line summaries here):

| ADR | Summary |
|---|---|
| 001 (incl. 001a) | SECOP II via SODA API as source of truth; Tier A/B/C dataset classification |
| 002 | Cursor-based pagination using `:id`; no `$offset` bulk loads |
| 003 | SODA3 authentication via Key ID + Key Secret |
| 004 | Fixed stack versions (Laravel 13 · PHP 8.5 · Livewire 4 · Bootstrap 5 · PostgreSQL 18 · Sail) |
| 005 | Queue: `database` driver; no Redis/Horizon in Phase 1 |
| 006 | Tests run against the `testing` database; `RefreshDatabase` |
| 007 | Official data kept separate from AI interpretation |
| 008 | "Red flags" are risk indicators, not accusations |
| 009 | Public repository from day one; no secrets in repo or docs |
| 010 | Dedicated local ports (coexistence with sonnar-platform) |
| 011 | Agents use mixed models, recorded per session |
| 012 | Research is reference, not truth; ADRs take precedence |
| 013 | Internal language: English |
| 014 | Adoption of the constitutional layer (`principles.md`) |
| 015 | Laravel Boost not adopted; controlled evaluation protocol defined |
| 016 | Pikit agent-file specification |
| 017 | Long-term vision document adopted; domain-expansion gate; open-network governance |
| 018 | OCDS storage strategy: Hybrid (releases canonical + derived current-state projection) |
| 019 *(draft)* | OCDS version pin 1.1.5 — pending acceptance |
| 020 *(draft)* | Personal and sensitive data handling — pending acceptance |

## 8. Governance and roles (citation section)

- **Who does what:** `AGENTS.md` ("Human Decisions", "Safe Operation") and PR-15 — humans
  own vision, priorities, ADR acceptance, legal/data interpretation, publication and tone,
  partnerships, secrets, merges. Agents propose and execute reversible, verifiable tasks.
- **Initiative governance principles** (capture resistance, ecosystem allyship,
  open-network model): `docs/vision.md` §5.

## 9. Success measures

Discovery-oriented only (PR-13 — no engagement, retention, or controversy metrics).
Full outcome logic: `docs/vision.md` §3 (Theory of Change).

1. **Verifications enabled** — citizens completing self-service verification paths
   (traced from data to source and back).
2. **Investigations supported** — practitioner use of the platform's data, provenance,
   and method in published work (counted by adoption, not by traffic).
3. **Ecosystem strengthening** (PR-03) — integrations and reuses of the platform, data
   model, or method by partner organizations.
4. **Integrity record** — unbroken compliance with the Tier E principles (the keystone
   aspiration of `docs/vision.md` §7.7).

## 10. Open decisions

Tracked in `docs/decision-log.md` (Pending Items) and `docs/roadmap.md` (Open decisions):
P-01 (red-flag phase) · P-02 (social-layer name) · P-06 (public-narrative tone) ·
ADR-019/ADR-020 acceptance · conceptual-model acceptance.

## 11. Living documents

Canonical routing (what to update when what changes): `.pi/memory/docs-map.md`. No
in-document duplication.

## 12. References

Open Contracting Partnership · OCDS 1.1.5 · demoanalitica (CC BY 4.0) · BuscaSECOP ·
Congreso Visible · MOE · Decidim · mySociety · Law 1712 of 2014.
