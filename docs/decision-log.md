# Architecture Decision Records (ADR) — Veeduría Abierta

Every significant technical or product decision is recorded here. Decisions are never deleted; if one changes, it is marked as `Replaced`, and a new record is added that references the original.

**Statuses:** Accepted · Pending · Replaced
**Decision-maker:** Humans. Agents may propose and draft records, but they do not have the authority to accept ADRs.
Every ADR must state its **Constitutional basis** (`principles.md`), cited by principle ID.

---

## ADR-001 — Source of Truth: SECOP II via SODA API
- **Status:** Accepted · 2026-10-19
- **Decision:** Procurement data is sourced from SECOP II, as published on datos.gov.co (via the SODA API). Datasets: `jbjy-vk9h` (electronic contracts) and `rpmr-utcd`.
- **Consequence:** We will not build a new SECOP viewer (as BuscaSECOP already exists). Guiding principle: *strengthen, rather than reinvent or compete*.

## ADR-001a — Amendment: Phase 1 Dataset Inventory and Tier Classification

- **Status:** Proposed · 2026-10-21
- **Amends:** ADR-001 — Source of Truth: SECOP II via SODA API (Accepted · 2026-10-19). ADR-001 remains in force; this amendment updates its dataset definition. It does **not** replace ADR-001.
- **Constitutional basis:** PR-05 (official data is inviolable — all findings from read-only queries); PR-06 (nothing is invented — datasets, row counts, and fields recorded only from verified API metadata); PR-08 (limitations are part of the content — coverage gaps documented); PR-15 (authority is human — agents draft, the human accepts).

### Context
- **Catalog discovery (2026-10-21, all read-only).** A catalog search of datos.gov.co returned **952 SECOP-related results**. Most results are derived "filter" views (Socrata datasets that share timestamps with a parent dataset and reuse its field model). After deduplication, **16 primary datasets** (type `dataset`, published by Colombia Compra Eficiente) remain — far more than the two datasets ADR-001 originally named.
- **Verified row counts and column counts** (via `count(*)` SoQL queries and `/api/views/{id}.json` metadata, all executed anonymously):
  - `jbjy-vk9h` — SECOP II - Contratos Electrónicos: **5,644,941 rows, 95 columns**
  - `p6dx-8zbt` — SECOP II - Procesos de Contratación: **9,349,940 rows, 59 columns**
  - `rpmr-utcd` — SECOP Integrado (SECOPI + SECOPII): **22,850,110 rows, 22 columns**
  - `u8cx-r425` — SECOP II - Modificaciones a contratos: **30,144,236 rows**
  - Twelve remaining primary datasets (invoices, guarantees, additions, execution, geodata, budget, bidders, supplier registry, suspensions, PAA) verified and inventoried; see `docs/data-dictionary.md` §1 once the amendment is applied.
- **Divergence from ADR-001's original wording.** ADR-001 named exactly two datasets (`jbjy-vk9h` + `rpmr-utcd`). Discovery showed that `rpmr-utcd` is an *integrated* SECOP I + SECOP II union dataset that overlaps contract data already covered — with a richer 95-column model — by `jbjy-vk9h`. Treating `rpmr-utcd` as a co-primary source, as the original ADR wording implied, would corrupt contract-level counting.

### Decision (proposed)
1. **Tier classification of the 16 primary datasets** (authoritative for Phase 1):
   - **Tier A — ingest in Phase 1; primary truth:**
     - `jbjy-vk9h` (SECOP II - Contratos Electrónicos, 5,644,941 rows, 95 cols) — authoritative contract facts; the richest contract-level source; directly matches ADR-001's original intent.
     - `p6dx-8zbt` (SECOP II - Procesos de Contratación, 9,349,940 rows, 59 cols) — authoritative pre-award process lifecycle; required for OCDS tender mapping and, later, documented risk indicators.
   - **Tier B — documented but deferred (not ingested in Phase 1):**
     - `rpmr-utcd` (SECOP Integrado, 22,850,110 rows, 22 cols) — an integrated union of legacy SECOP I and SECOP II records. It overlaps `jbjy-vk9h` for all SECOP II contracts while carrying only 22 columns (vs. 95) and mixing two field models; its unique value is legacy SECOP I contracts, which are out of scope per ADR-001's original intent.
     - `u8cx-r425` (SECOP II - Modificaciones a contratos, 30,144,236 rows) — contract amendments are a legitimate future concern but a separate ingestion concern from base contract facts; deferred to a later Phase 1 increment.
   - **Tier C — noted, out of Phase 1 scope:** the remaining 12 primary datasets (`ibyt-yi2f`, `gjp9-cutm`, `cb9c-h8sn`, `mfmm-jqmq`, `wwhe-4sq8`, `gra4-pcp2`, `skc9-met7`, `cwhv-7fnp`, `hgi6-6wh3`, `qmzu-gj57`, `u99c-7mfm`, `b6m4-qgqv`), plus all SECOP I legacy datasets. They are inventoried in the data dictionary but not ingested.
2. **Double-counting risk (explicit).** Ingesting `rpmr-utcd` **and** `jbjy-vk9h` together would count the same SECOP II contracts twice (every SECOP II contract in `rpmr-utcd` also appears in `jbjy-vk9h`) and would mix two incompatible field models (22 columns vs. 95). Any future use of `rpmr-utcd` must be preceded by a documented de-duplication strategy against `jbjy-vk9h` and a field-model reconciliation, recorded as a new ADR.
3. **Updated authoritative Phase 1 dataset definition.** For Phase 1 ingestion, the authoritative dataset set is **Tier A only: `jbjy-vk9h` and `p6dx-8zbt`**. Tier B is deferred with a documented **re-evaluation trigger**: `rpmr-utcd` is re-evaluated only if legacy SECOP I coverage becomes a human-approved requirement, or if `jbjy-vk9h` is shown to be insufficient for an approved feature, and only with the de-duplication strategy of point 2; `u8cx-r425` is re-evaluated when amendment tracking is scheduled as its own ingestion increment. SECOP I datasets remain out of scope per ADR-001's original intent.
4. **Access refinement (ADR-003 practical meaning).** All discovery queries succeeded **anonymously** (no token, no credentials). This refines the practical meaning of ADR-003: authentication is a rate-limit/robustness measure for bulk workloads, not an access gate for discovery. The operative rule to record — destined for `docs/ingestion-runbook.md` once that file is created — is, in exact approved wording:
   > "App Token is optional for discovery activities and strongly recommended for bulk-ingestion workloads."
   ADR-003's decision on credential storage (Key ID + Key Secret in `.env` only, per ADR-009/PR-14) is unchanged.

### Consequences
- **`docs/data-dictionary.md` §1** is updated from the two-dataset skeleton to the verified 16-dataset inventory with the Tier A/B/C classification; the empty fields section formerly reserved for `rpmr-utcd` is superseded (fields sections populated for Tier A datasets only; `rpmr-utcd` documented as deferred).
- **`docs/glossary.md`** gains: *SECOP Integrado (SECOPI+SECOPII union)*, *filter view*, *Tier A / Tier B / Tier C dataset classification*, and the *App Token* operative rule (exact wording above).
- **`docs/roadmap.md`** gains a Phase 1 scope note: dataset discovery complete (2026-10-21); dictionary population for Tier A in progress.
- **`docs/ingestion-runbook.md`** (not yet created; skeleton approved as Q-2) will carry the App Token operative rule verbatim in its access section.
- Tier A field work proceeds under the PR-06 hard gate: fields enter the data dictionary only from verified API metadata and remain non-normative until human validation.

## ADR-002 — Cursor-based Pagination using `:id`
- **Status:** Accepted · 2026-10-19
- **Context:** The `$offset` parameter suffers from performance degradation as the dataset approaches ~283,500 records.
- **Decision:** Data ingestion must use cursor-based pagination keyed on Socrata’s internal `:id` field; the `$offset` parameter is never to be used for bulk data loads.

## ADR-003 — SODA3 Authentication via Key ID + Key Secret
- **Status:** Accepted · 2026-10-19
- **Decision:** Use Basic Auth with a Key ID and Key Secret (rather than `$$app_token`). Credentials must be stored exclusively in the `.env` file; see ADR-009.

## ADR-004 — Fixed Stack Versions
- **Status:** Accepted · 2026-10-19
- **Decision:** The versions installed during the initial session are locked; updates will be handled later as a separate task.
- **Versions verified during that session:** Laravel 13.37.0 · PHP 8.5 (`sail-8.5/app` image) · Livewire 4.4.7 · `laravel/ui` 4.6.3 with Bootstrap 5.3.8 installed (constraint `^5.2.3` in `package.json`) · PostgreSQL 18 (`postgres:18-alpine`) · Vite 8.3.4 · Node 24 (verified by a human on 2026-10-20: Node v24.22.0 inside the Sail container; the host's Node version, 22.x, is not used).
- **Known risk:** Most examples and templates are based on Livewire 3. All Livewire syntax must be verified against the code in `vendor/livewire/livewire` or the v4 documentation before use.

## ADR-005 — Job Queue Using the `database` Driver
- **Status:** Accepted · 2026-10-19
- **Decision:** `QUEUE_CONNECTION=database` (the `jobs` table has already been migrated). No Redis or Horizon in Phase 1.
- **Verified by a human on 2026-10-20:** `.env` contains `QUEUE_CONNECTION=database` (the agent cannot read the `.env` file).
- **Review when:** The volume of historical data ingestion causes the queue to slow down.

## ADR-006 — Testing against the `testing` database
- **Status:** Accepted · 2026-10-19 (interpretation to be confirmed)
- **Decision:** Tests run against the `testing` database created by Sail—never against the development database. Default trait: `RefreshDatabase`.
- **Pending verification:** Ensure `phpunit.xml` points to `pgsql` / `testing` rather than SQLite.

## ADR-007 — Official data kept separate from AI interpretation
- **Status:** Accepted · 2026-10-19
- **Decision:** Official raw data is stored in an immutable state, separate from any text generated by an LLM. All AI-generated content must be labeled as such and remain auditable (tracking the model, version, and input used).

## ADR-008 — "Red flags" are risk indicators, not accusations
- **Status:** Accepted · 2026-10-19
- **Decision:** No screen, text, or document shall state that an individual or company has committed a crime; instead, the term "risk indicator" is used. The "red flag" engine is excluded from Phase 1 but is **committed for a later phase** (confirmed 2026-10-20). For the exact timeline, see P-01.

## ADR-009 — Public repository from day one
- **Status:** Accepted · 2026-10-19
- **Decision:** Initialize with `git init` under an MIT license. The initial commit may only be performed after verifying that `.gitignore` excludes `.env`, `vendor/`, `node_modules/`, `venv/`, and the `storage` directory (containing data).
- **Rule:** No secrets—of any kind—may be included in the repository or any documentation.

## ADR-010 — Dedicated Local Ports
- **Status:** Accepted · 2026-10-19
- **Context:** `sonnar-platform` occupies ports 80, 5173, and 5432 on the same machine.
- **Decision:** In `.env`: `APP_PORT=8080`, `VITE_PORT=5174`, `FORWARD_DB_PORT=5433`. (Manual human action required: the AI ​​cannot edit the `.env` file.)
- **Verified by a human on 2026-10-20:** The `.env` file contains the three specified values ​​(the agent cannot read the `.env` file).

## ADR-011 — Agents Using Mixed Models
- **Status:** Accepted · 2026-10-19
- **Decision:** Some agents use Claude models, while others use more economical models. The model used for each session is always recorded (`model=` in the `METRICS` line) to ensure that the effects of the agent are not conflated with those of the model.

## ADR-012 — Research serves as a reference, not as the ultimate source of truth
- **Status:** Accepted · 2026-10-19
- **Decision:** `docs/research/secop-platform-research.md` informs the design but is not prescriptive. In cases where it conflicts with an ADR, the ADR takes precedence. Known discrepancies include the use of `$offset` and `X-App-Token` (ADR-002/003), assumptions regarding the use of Livewire 3, Bootstrap 4, and Redis/Horizon, as well as rules inherited from Sonnar GPON (ADR-004/005). Market figures cited therein should not be treated as facts without verifying the original source.

## ADR-014 — Adoption of the Constitutional Layer
- **Status:** Accepted · 2026-10-21
- **Constitutional basis:** PR-15 (authority is human); PR-14 (openness of the governance layer itself).
- **Decision:** Adopt `docs/principles.md` (version 1.0) as the apex constitutional document of the project. Its 15 principles (PR-01…PR-15), entrenchment tiers (E/F), hierarchy of norms, conflict-resolution algorithm, and Constitutional Test govern all future artifacts. Principle IDs are permanent from acceptance; renumbering is forbidden (see `principles.md` §7).
- **Recorded drafting decisions:** (1) the educational mission promoted to standalone principle PR-01 — *Teach the method* (Tier F); (2) PR-06 — *Nothing is invented* entrenched (Tier E) with the derivation carve-out written into its formulation; (3) PR-14's secrecy clause hardened to admit no exception pathway; (4) Tier E membership: PR-05, PR-06, PR-09, PR-10, PR-12, PR-15.
- **Knock-on edits executed in this commit:** Master Plan §3 becomes a pointer to `principles.md`; `AGENTS.md` rule 1 adds the constitutional pre-task reading; core domain rules gain principle citations; living-documents table gains `principles.md`.

## ADR-013 — Internal Language: English
- **Status:** Accepted · 2026-10-21
- **Constitutional basis:** PR-04 (built to outlast); PR-14 (open by default — widest contributor pool); PR-15 (governance rule change, human-decided).
- **Context:** Internal artifacts are mixed-language: prose is already English (Master Plan, Decision Log, research reference) while filenames, commit messages, `CLAUDE.md`, and conventions are Spanish. `AGENTS.md` previously mandated Spanish documentation. Agents and the international contributor pool work most reliably in English; citizens encounter only the product, never working documents.
- **Decision:** (1) Internal language is English for all internal artifacts — documentation, filenames, ADRs, task specs, agent definitions, prompts, workflows, commit messages, branch names, code comments, test names, migration comments. (2) Citizen-facing language is Spanish — UI text, data explanations, public communications, educational material — confined to Blade views/UI strings, `docs/citizen/`, and public channels; that folder is the mechanical boundary of the policy. (3) Proper nouns are never translated: Colombian legal instruments (Ley 1712 de 2014, Ley 21 de 1991), institution and dataset names, SODA field values as published, and the public name *Veeduría Abierta* in citizen-facing contexts. (4) Borderline artifacts are internal by default (English with linked Spanish citizen material, not the reverse). (5) Data is not prose — this policy never justifies translating, normalizing, or altering source data (PR-05).
- **Consequences:** Spanish commit history is not rewritten; the English-imperative convention applies from the first commit after adoption. Spanish-named files are renamed in one dedicated `git mv` commit with cross-references updated in the same commit. `CLAUDE.md` is translated. New artifacts are English from birth. Citizen-facing tone review remains a human decision (PR-15).
- **Review when:** a significant contributor community with a different working language emerges, or a citizen-facing artifact class does not fit the two-folder boundary.

## ADR-015 — Controlled Evaluation of Laravel Boost

- **Status:** Accepted · 2026-10-21
- **Origin:** Pending item P-04 (raised in the session handoff of 2026-10-21).
- **Decision:** Laravel Boost is **not adopted at this time**. A controlled evaluation may be conducted once implementation work (Phase 1) begins, under the protocol below. Adoption is a separate future decision; P-04 is resolved as "not adopted now, evaluation protocol defined".

### Context
Laravel Boost (documented in this repository's README) is Laravel's official AI-agent tooling: `composer require laravel/boost --dev` followed by `php artisan boost:install`. It provides 15+ tools and skills that let AI agents build Laravel applications while following framework best practices.

### Expected benefits
1. **Agent effectiveness:** structured tooling for AI agents working on this codebase, aligned with Laravel conventions — directly useful during Phase 1 implementation (ingestion, explorer, dashboard).
2. **Versioned documentation for agents:** Boost can generate/maintain agent-facing documentation derived from the installed framework version, reducing drift between what agents assume and what Laravel 13 actually does.
3. **Convention enforcement:** its skills encode Laravel best practices, supporting PR-04 (the project outlasts individual sessions and agents).

### Governance risks identified
1. **Constitutional overwrite (highest risk):** `boost:install` may modify or overwrite `AGENTS.md` and `CLAUDE.md` — human-owned governance files. An unreviewed install could silently alter the constitutional layer, violating PR-15.
2. **Environment alteration without approval:** the install commands change `composer.json`, `composer.lock`, and the dev environment. Under P-03 these require explicit human execution and approval.
3. **Livewire 4 compatibility (unverified):** the handoff of 2026-10-21 notes a potential compatibility concern with Livewire 4. This has NOT been verified — no test has been run and no external research completed. This is a known-unknown.
4. **Documentation drift:** if Boost becomes the "source of truth" for agent docs, its output could conflict with `AGENTS.md`, `docs/principles.md`, and the docs-map routing rules. Any conflict must be resolved in favor of the human-owned constitutional layer.
5. **Mixed-model bias (ADR-011):** Boost's guidance may be tuned to particular agents (e.g., Claude Code). Adoption must not create a privileged model.

### AGENTS.md and CLAUDE.md modification risk — mandatory guardrails
- `AGENTS.md` and `CLAUDE.md` are human-owned. No tool output may be committed to them without the human reviewing every changed line.
- Before any commit after a Boost install, a full diff of every file Boost touched must be reviewed; changes to the constitutional layer are treated as proposals, never as accepted state.
- If Boost's generated instructions conflict with `docs/principles.md` or the docs-map, the constitutional layer prevails, and the conflict is recorded as a new pending item.

### Evaluation protocol (future, once Phase 1 begins)
1. **Preconditions:** clean working tree; a dated handoff exists; the human explicitly authorizes the install in writing.
2. **Baseline:** capture `composer.lock` hash and full `git status` before install.
3. **Human executes:** `composer require laravel/boost --dev` and `php artisan boost:install` — never an agent.
4. **Diff review:** every modified/created file is reviewed against the constitutional layer; the AGENTS.md/CLAUDE.md guardrails above apply.
5. **Verification:** run the test suite (`./vendor/bin/sail artisan test`); explicitly test Livewire 4 functionality to resolve the known-unknown in risk 3; record results in a dated note under `.pi/memory/`.
6. **Report:** the Architect produces an evaluation report — what changed, what worked, what conflicts arose — and proposes: adopt / adopt with restrictions / reject. The human decides.

### Acceptance criteria for future adoption
- All existing tests pass with Boost installed.
- Livewire 4 compatibility verified (risk 3 resolved — no regressions).
- Zero unreviewed changes to `AGENTS.md`, `CLAUDE.md`, `docs/principles.md`, or any human-owned file.
- Agent workflow demonstrably improved (measured against a real Phase 1 task), or at minimum not degraded.
- A rollback (below) has been rehearsed successfully.

### Rollback strategy
1. `composer remove laravel/boost --dev` (human executes).
2. `git checkout -- .` to discard any file changes from `boost:install`, or `git revert` of the evaluation commit(s) if already committed.
3. Delete any Boost-generated files not tracked by git (identified during the diff review in step 4 of the protocol).
4. Verify: test suite passes; `AGENTS.md`/`CLAUDE.md` byte-identical to the baseline commit; composer.lock restored to baseline hash.
5. Record the rollback outcome in `docs/decision-log.md` (P-04 note).

- **Consequences:** This ADR must be updated (not replaced) when the evaluation occurs, preserving the protocol and findings for institutional memory.

---

## ADR-016 — Adoption of the Pikit Agent-File Specification

- **Status:** Accepted · 2026-10-21
- **Origin:** Compliance review of `.pi/agents/project-architect.md` and `.pi/agents/_template.md` against the Pikit `subagents` extension and pi's tool-selection mechanics.
- **Decision:** All agent definition files in `.pi/agents/` must follow the Pikit agent-file specification:
  1. Every agent file starts with YAML frontmatter containing `name` and `description` (required — files without them are silently ignored by the loader).
  2. `description` states the agent's powers and limits; it is governance surface for parent-session delegation.
  3. `tools` follow least privilege: the minimum set that makes the approved charter effective. `write`/`edit` only for charter-granted write authority; `bash` only where the charter explicitly authorizes command execution. Omitted `tools` inherits the read-only default set.
  4. `model` is omitted so agents inherit the session model (ADR-011: mixed models, recorded per session).
  5. Templates must never carry parsable frontmatter; examples appear only inside fenced code blocks.
- **Context:** Verified against Pikit `subagents` extension sources (`agents.ts`, `config.ts`) and pi docs (`cli.md` §tools): absent `name`/`description` → file skipped; explicit `tools` replaces the entire selection; the subagent default set is read-only and excludes `write`, `edit`, and `bash`. The Project Architect charter grants write authority over `.pi/memory/` artifacts, so its tool list explicitly includes `write, edit` and excludes `bash`, making P-03 (no commits, no migrations, no environment changes) enforceable at tool level.
- **Consequences:** Agent charters are mirrored in frontmatter (single approval covers both). The "Run tests" charter row was rephrased: agents may propose, coordinate, and review testing activities. Test execution requires explicit authorization and an approved execution path. Template files in `.pi/agents/` are loader-visible; `AGENTS.md` gains an "Agent Files (Pikit Subagents)" section. Changes to agent files require `/reload`.

## ADR-017 — Adoption of the Long-Term Vision Document

- **Status:** Accepted · 2026-10-21
- **Origin:** Human decision to define the larger purpose Veeduría Abierta serves beyond
  SECOP, structured around three pillars: civic infrastructure, civic education, and civic
  community. Drafted by agents, refined and accepted by the human.
- **Decision:**
  1. `docs/vision.md` (formerly `docs/vision-draft.md`) is adopted as the **baseline
     vision document**. It defines the initiative's mission, vision, theory of change,
     community model, governance principles, public narrative (pending Spanish tone
     approval), and ten-year aspirations. It contains no software architecture; the
     Master Plan remains the authority on product scope and phases.
  2. **Mission scope.** The mission is stated at the level of *public action* (public
     spending, legislation, public services, citizen participation, and other forms of
     public information) so it outlives any single dataset. Public procurement (SECOP II /
     OCDS) remains the **current implementation focus** (Master Plan Phase 1).
  3. **Domain expansion is gated.** Every new domain (beyond public procurement) requires
     its **own ADR** with, at minimum: source validation, reliability analysis, scope
     definition, architectural impact assessment, and human approval. This preserves focus
     while allowing intentional growth. Domain expansion ADRs are additionally subject to
     the constitutional rules (PR-05, PR-06, PR-07, PR-09).
  4. **Governance model.** The initiative operates as an **open network**; no formal
     association, membership structure, or representative body is assumed or created
     unless future community needs justify it through an explicit human decision (PR-15).
- **Constitutional basis:** PR-01 (teach the method), PR-04 (built to outlast),
  PR-15 (authority is human where it matters). Domain expansions must additionally pass
  PR-05, PR-06, PR-07, and PR-09 gates.
- **Consequences / knock-on edits:**
  - `docs/master-plan.md` §2 should cite `docs/vision.md` as the vision layer — **human
    edit, proposed, not yet applied** (master-plan.md is modified only by humans).
  - The public narrative in `docs/vision.md` §6 is **not publishable** until the human
    approves the Spanish wording and tone (PR-15; AGENTS.md language rules).
  - `.pi/memory/docs-map.md` gains a routing row for `docs/vision.md` (applied with this ADR).

## ADR-018 — OCDS Storage Strategy for Phase 1: Hybrid (Releases Canonical + Derived Current-State Projection)

- **Status:** Accepted · 2026-10-21
- **Origin:** Pending item P-05 (Q-1 constraint of the Phase 1 plan). Drafted from the
  verified ground truths of the data dictionary v1.0 and conceptual validations V-1…V-4.
  Numbering reconciled per human decision: the draft artifacts had used "ADR-017" for the
  storage strategy; the recorded ADR-017 is the Long-Term Vision Document adoption, so the
  storage strategy is recorded here as ADR-018, shifting the OCDS-pin draft to ADR-019 and
  the personal-data draft to ADR-020.
- **Constitutional basis:** PR-01, PR-04, PR-05, PR-06, PR-07, PR-08, PR-15 (full analysis
  in the draft artifact `.pi/artifacts/draft-adr-p05-ocds-strategy.md`).
- **Context (verified ground truths):** SODA publishes current-state snapshots, not native
  releases; any release structure in the normalized layer is therefore synthesized from
  successive snapshot ingestions — a derivation permitted by PR-06's derivation carve-out
  only when computed from validated fields, with a documented rule, and labeled derived.
  The raw layer is already append-only and immutable (PR-05/ADR-007) regardless of this
  choice; this ADR governs only the normalized layer. V-1: `id_contrato` is a logical
  business identifier with source-side duplicate re-publications. V-4: authoritative
  contract↔process linkage is `proceso_de_compra` ↔ `id_del_portafolio` across three
  identifier namespaces. V-2: process → contract is 1:N.
- **Decision (Option C — Hybrid):**
  1. The normalized layer stores **immutable releases as the canonical structure** — one
     release per observed state change per process/contract, constructed by a documented
     state-hash comparison rule; source-side duplicates (V-1) produce no new release.
  2. A **derived current-state projection** (latest release per ocid) is materialized,
     labeled derived, and fully traceable (projection → release → raw). The projection
     must never become an independent source of truth (explicit human condition).
  3. Sentinel values (`No Definido`, `No Adjudicado`, …) are preserved per release exactly
     as published; history never "cleans" the past (PR-06).
  4. Records/current-state-only (Option B) is rejected: it forfeits the master-plan's
     "immutable timelines" persona commitment and loses change history bounded by
     ingestion cadence from the first ingestion day forward.
- **Explicit non-decisions (to prevent scope creep):** OCDS version pinning (resolved by
  ADR-019); normative field mapping (dictionary candidates become eligible for elevation
  only after ADR-018 + ADR-019); `ocid` format finalization; release-tag ↔
  `estado_contrato`/`fase` mapping (HUMAN-5, human-gated); Tier B amendments absorption
  (`u8cx-r425`); interaction with personal/sensitive data exposure (ADR-020).
- **Consequences:** the conceptual model gains the normalized-layer structure definition
  (conceptual only); the ingestion runbook gains a release-construction rule section
  (state-hash comparison, cadence, projection staleness = rebuild on new release); storage
  architecture (tables, migrations) remains out of scope until ADR-019 and ADR-020 close,
  per the human's sequencing decision.

## ADR-019 — OCDS Version Pin: 1.1.5

- **Status:** Accepted · 2026-10-21 (OCDS 1.1.5 pinned)
- **Origin:** ADR-018 (accepted: Option C — canonical releases + derived current-state projection) declares OCDS version pinning a condition-of-acceptance follow-up.
- **Constitutional basis:** PR-04 (built to outlast — pin a stable, mature, well-tooled version rather than a moving target); PR-06 (nothing invented — every claim below verified against official standard documentation); PR-08 (limitations stated — what was and was not verified); PR-15 (authority is human).

## Context

ADR-018 fixed the structural strategy (releases canonical + derived current-state projection) but explicitly deferred version pinning. This ADR resolves the pin so that normative mapping work can begin.

## Verification record (2026-10-21, official documentation)

| # | Fact verified | Source | Result |
|---|---|---|---|
| 1 | Latest stable OCDS version | `standard.open-contracting.org/latest/` — all pages served as **"OCDS v1.1.5"** | ✅ 1.1.5 is the current stable documented version |
| 2 | Is 1.2 stable? | Official changelog (History → Changelog): newest *versioned* entry is `[1.1.5] - 2020-08-20`; all newer entries are "iterative improvements" to non-normative content (documentation, translations) | ✅ 1.2 does **not** appear as a stable release; it remains in development. Pinning 1.1.5 does not conflict with any stable 1.2 |
| 3 | Release semantics | Getting Started → Releases and Records | ✅ Releases are **immutable**, one per change, many per contracting process; the collection of releases constitutes the change history; each release carries an `ocid` |
| 4 | Record semantics | Same page | ✅ A record is an **index of all releases** for one contracting process, optionally containing a **compiled release** (latest value per field) and a **versioned release** (field-level change history) |
| 5 | ocid construction | Schema → Identifiers | ✅ ocid = registered prefix (`ocds-` + 6-char lowercase alphanumeric, issued by OCP) + publisher's internal identifier. Case-sensitive. Publisher-namespace requirements deprecated |
| 6 | Identifier scoping | Same page | ✅ Release `id`, award `id`, and contract `id` are **unique within the scope of the contracting process** (not globally). `(ocid, id)` disambiguates |
| 7 | Framework agreements | Same page | ✅ Official: "Multiple contracts may refer to a single award, as in the case of a framework contract where multiple contracts are issued against a single award." Contracts cross-reference `awardID` | 
| 8 | Organization identifiers | Same page | ✅ Organization ID = `scheme` (register prefix, e.g., GB-COH) + `id`; primary registers strongly preferred. A **personal identifiers guidance** page exists for natural persons |

**Verification method and limits (PR-08):** verification was performed against the official documentation website (normative prose and changelog), not by validating sample data against the JSON schema artifacts. A schema-level validation exercise (constructing one synthetic release and validating with the official tooling) is listed below as a condition before normative mapping is declared complete.

## Decision

**Pinned: OCDS version 1.1.5** is the normative target for all mapping, release construction, and export work in Phase 1 (accepted 2026-10-21).

Rationale:

1. **It is the current stable standard.** No stable 1.2 exists to pin; pinning a dev version would violate PR-04 (durable technology) and create churn risk.
2. **It is mature and tooled.** Canonical codelists at stable URLs, a Data Review Tool, and a reference merge implementation exist for the 1.1 line.
3. **It matches the accepted strategy exactly.** ADR-018's Option C (canonical releases + derived current-state projection) is structurally identical to OCDS 1.1's own model: releases are canonical and immutable; the compiled release is the derived latest-values view; the versioned release provides field-level history. Our "current-state projection" is, in OCDS terms, a **compiled release** — the standard itself endorses the hybrid.
4. **Versioned releases support PR-01 directly.** OCDS 1.1's versioned release (per-field change history) is a standardized mechanism for "teach the method": citizens can see *how each field changed*, not just that it changed.

## Implications

### 1. ocid construction
- **Prefix registration is required** for published OCDS data: an `ocds-XXXXXX` prefix is issued by the Open Contracting Partnership. Registering it is **communication with an external partner — a human decision and human action (PR-15)**. The Architect cannot and must not register it.
- **Internal identifier (verified against the identifier catalog, dictionary §9):** the process-level internal identifier is `proceso_de_compra` / `id_del_portafolio` (`CO1.BDOS.*`, authoritative per V-4). Candidate process ocid: `ocds-<prefix>-CO1.BDOS.<n>`.
- Contract-level identity: OCDS scopes contract `id` uniqueness *within the contracting process* — which matches V-1 exactly: `id_contrato` (`CO1.PCCNTR.*`) is unique within its process scope, not globally. **OCDS's own scoping rule is independent confirmation of V-1's finding.**
- Until the prefix is registered, normalized-layer ocids may be constructed with an explicitly-marked provisional prefix constant, on the condition that any public OCDS export before registration is forbidden (PR-06: nothing presented as standard-conformant that isn't).
- The three namespaces (`CO1.BDOS.*`, `CO1.REQ.*`, `CO1.PCCNTR.*`) remain distinct (V-4); only `CO1.BDOS.*` feeds the ocid. `CO1.REQ.*` (`id_del_proceso`) may map to OCDS `relatedProcesses` — candidate only.

### 2. Release structure
- Our synthesized releases (one per observed state change, per ADR-018) map to OCDS releases: `ocid`, release `id` (unique within process scope — candidates: platform change-event or hash-based), release `date` (candidate: the `ultima_actualizacion` timestamp of the triggering snapshot — Level 2, flagged), and `tag` (requires the HUMAN-5 legal vocabulary mapping — remains human-gated).
- Sentinel values are preserved inside releases as published (PR-06) — OCDS permits open codelists and string fields; where a closed codelist would conflict with a sentinel, the field is either unmapped or handled per-field in mapping work (limitations stated per PR-08).

### 3. Record structure
- **The current-state projection (ADR-018) should be implemented as a compiled release** per OCDS 1.1: derived by the standard merge rules (identifier merge for suppliers — note the changelog's 1.0→1.1 supplier merge change — and object merge otherwise), labeled derived, traceable to releases.
- **Versioned release** (field-level history) is an optional future capability of the same structure: it directly serves PR-01 (citizens see how each field changed). Recommended as a Phase 2 citizen-facing feature, not Phase 1 scope — recorded here to prevent re-litigation.
- ADR-018's principle is reinforced: **the compiled release must never become an independent source of truth** — it is always derivable from releases (the human's explicit condition).

### 4. Future mapping decisions
- Normative mapping now has a fixed target: OCDS 1.1.5 codelists and schema (`procurementMethod`, `tenderStatus`, `contractStatus`, `partyRole`, `itemClassificationScheme` — which includes UNSPSC, matching `codigo_de_categoria_principal`/`codigo_principal_de_categoria`).
- The dictionary's OCDS-mapping candidates (all marked "pending P-05 ADR") become eligible for elevation to normative candidates against **1.1.5** specifically.
- Organization identifier `scheme` values (Colombian registers, e.g., RUE/NIT) must be resolved via org-id.guide during mapping — candidate task, human-approved.
- Extensions likely relevant later (recorded, not decided): `lots` (numero_de_lotes), `bids` statistics (process-side response counts), `location` (execution addresses) — all Tier C or mapping-phase concerns.

## Consequences

- Mapping work proceeds against **1.1.5** only; any 1.2 stabilization is a future re-evaluation trigger (new ADR, not silent upgrade).
- **Human actions unlocked by acceptance:** (1) ocid prefix registration with OCP (external communication — your decision and action); (2) HUMAN-5 legal vocabulary review for release tags; (3) authorization of the schema-validation exercise.
- **Architect actions unlocked:** elevate dictionary mapping candidates to normative-candidate status against 1.1.5; draft the release-construction rule for the runbook (state-hash comparison, cadence, release `id`/`date` candidates).
- The Personal and Sensitive Data ADR (next gate) can now reference concrete structures: personal data in releases is immutable history — its exposure rules must account for the fact that compiled/versioned views will surface historical values (a material input to that ADR).

## Constitutional Test checklist (principles.md §6)

| # | Test | Status | Note |
|---|---|---|---|
| 1 | Method (PR-01) | Pass | Versioned-release capability identified as the standard's mechanism for field-level method transparency |
| 2 | Verdict (PR-02) | Pass | No civic conclusions |
| 3 | Ecosystem (PR-03) | Pass | Aligns with the global standard and its tooling; strengthens interoperability |
| 4 | Permanence (PR-04) | Pass | Stable version pinned; dev-version churn avoided; re-evaluation trigger documented |
| 5 | Lineage (PR-05) | Pass | Compiled release defined as derived; never an independent source of truth |
| 6 | Invention (PR-06) | Pass | All standard claims verified against official docs; unverified items flagged (schema-level validation pending) |
| 7 | Reliability (PR-07) | Pass | Level-2 timestamps flagged as release-`date` candidates |
| 8 | Limitations (PR-08) | Pass | Verification limits stated; sentinel/closed-codelist tension flagged for mapping phase |
| 9 | Indicators (PR-09) | Pass | N/A |
| 10 | Authenticity (PR-10) | Pass | N/A |
| 11 | Vulnerability (PR-11) | Pass | N/A |
| 12 | Labeling (PR-12) | Pass | N/A |
| 13 | Purpose (PR-13) | Pass | N/A |
| 14 | Secrecy (PR-14) | Pass | No credentials; prefix registration is public and human-executed |
| 15 | Authority (PR-15) | Pass | Decision reserved to the human; prefix registration and partner communication explicitly human actions |

---

---

## ADR-020 — Personal and Sensitive Data Handling (Exposure, Restriction, and Disclosure Rules)

- **Status:** Accepted · 2026-10-21 (register binding; LQ-1…LQ-6 remain open legal questions)
- **Origin:** Data Dictionary v1.0 §6 Decision 3; roadmap backlog (2026-10-21).
- **Constitutional basis:** PR-05 (raw data inviolable — this ADR must not propose mutating it); PR-07 (graded honesty); PR-08 (limitations stated); PR-09 (indicators, not accusations — directly limits profiling features); PR-14 (secrecy clause for restricted data handling); PR-15 (authority is human — legal interpretation is explicitly a human domain); PR-02/PR-01 (evidence and method, not verdicts — shapes disclosure design).
- **Prerequisite satisfied:** ADR-018 (releases canonical) and ADR-019 (OCDS 1.1.5 pinned) — this ADR now references concrete structures: personal data exists as immutable history in releases; compiled releases surface latest values; a versioned-release feature would surface full field-level history.

## Context

Data Dictionary v1.0 flags two categories of fields whose civic value must be weighed against privacy exposure:

- **HUMAN-6 — bank/financial data** (`nombre_del_banco`, `tipo_de_cuenta`, `n_mero_de_cuenta`): payment-account details. Level 2, predominantly sentinel (`No definido`) in samples, but populated for some contracts.
- **HUMAN-9 — personal data of natural persons** (16 fields across both Tier A datasets): legal representatives (name, nationality, domicile, ID type/number, gender), supervisors, ordenadores del gasto/pago (names, ID types/numbers), and the process-side awarding officer (`nombre_del_adjudicador`).
- **Adjacent — supplier identity of natural persons:** `tipodocproveedor` includes `Cédula de Ciudadanía`; for sole-trader suppliers, `documento_proveedor` **is a personal ID number**. V-3 establishes contract-side supplier fields as authoritative for identity — so this ADR cannot simply exclude them without breaking identity resolution. This tension is a core design problem of this ADR.

**The defining structural fact (from ADR-018/019):** the raw layer is immutable and append-only (PR-05), and releases are immutable history. Personal data ingested in raw payloads **persists permanently and cannot be retroactively redacted without violating PR-05**. Therefore this ADR governs **exposure, normalization, search, and disclosure** — it cannot govern retention of raw data beyond a decision *not to ingest* (which would violate the completeness of the raw layer and is not recommended — see §1).

**The defining civic fact:** these fields are **already published by the state** on datos.gov.co and the SECOP platform (Ley 1712 de 2014 transparency regime). The project does not originate this data. The privacy question is therefore not publication per se, but **amplification**: a general-purpose search index over personal ID numbers makes data more accessible, more linkable, and more profileable than the official source — and that amplification is what this ADR must govern.

## 1. Raw-layer retention rules (proposal)

- **Retain everything; mutate nothing.** Raw payloads are stored unmodified, including all HUMAN-6/HUMAN-9 fields (PR-05 is Tier-absolute: any "sanitize-at-ingestion" proposal would mutate official data and is rejected as a matter of constitutional compliance).
- **Access control is operational, not constitutional:** raw-layer internal access should be restricted to ingestion and audit paths; no raw values surface through application APIs. This is an implementation constraint for storage architecture, recorded here as a requirement.
- **Exception pathway — none proposed.** Even HUMAN-6 fields are part of the official record; excluding them at ingestion would create incomplete provenance for payment-related verification (e.g., "which bank failed to pay this supplier" is a legitimate civic question).

## 2. Normalized-layer exposure rules (proposal)

The normalized layer is a *derivation* (ADR-018) and therefore fully governable. Proposed default posture, per field class — **every row requires your validation**:

| Field class | Fields | Proposed treatment | Rationale |
|---|---|---|---|
| Bank data (HUMAN-6) | `nombre_del_banco`, `tipo_de_cuenta`, `n_mero_de_cuenta` | **Excluded from the normalized layer** (raw-only). No mapping, no projection, no display | No civic verification value that offsets identity-theft/fraud exposure; account numbers are not procurement substance |
| Legal representatives (HUMAN-9a) | `nombre_representante_legal`, `nacionalidad_representante_legal`, `domicilio_representante_legal`, `tipo_de_identificaci_n_representante_legal`, `identificaci_n_representante_legal`, `g_nero_representante_legal` | **Name: map** (persons acting in a public contracting capacity — accountability value). **ID number/domicile/gender: exclude from normalized layer**; ID type alone is low-sensitivity but low-value — propose exclude | Names of decision-actors serve accountability; ID numbers enable identity aggregation with no verification gain |
| Supervisors / ordenadores (HUMAN-9b) | `nombre_supervisor`, `nombre_ordenador_del_gasto`, `nombre_ordenador_de_pago` (+ their document types/numbers — 6 fields) | **Names: map with the same accountability rationale.** **Document numbers: exclude** | Same logic as 9a; these are public officials acting in role |
| Awarding officer (HUMAN-9c) | `nombre_del_adjudicador` (p6dx-8zbt) | **Propose: exclude from Phase 1 normalized layer**, revisit with HUMAN-5 legal review | Sentinel-heavy, role unclear ("user who executed the award action" — possibly a platform operator, not a decision-maker; [HUMAN-9] flag open) |
| Supplier identity — legal persons | `documento_proveedor` where `tipodocproveedor` = NIT | **Map in full** | Corporate identifiers are public business data; V-3 authority rule applies; OCDS `identifier/scheme` expects it (org-id.guide scheme resolution pending) |
| Supplier identity — natural persons | `documento_proveedor` where `tipodocproveedor` = `Cédula de Ciudadanía` | **Map in the normalized layer (identity resolution requires it — V-3), but apply disclosure-layer masking and search restrictions (§3–§4)** | Breaking identity resolution for sole traders would corrupt V-3 and OCDS party structure; masking belongs at the disclosure layer, not the data layer |

## 3. Citizen-facing disclosure rules (proposal)

- **Default principle (PR-02/PR-09):** display what the citizen needs to *verify procurement substance*; do not display what enables *profiling of private persons*.
- **Names of role-actors** (representative, supervisor, ordenadores): display in contract detail views, labeled with their role (source-published, PR-08: state that the source publishes this data).
- **Personal ID numbers of natural persons:** never rendered in full on any screen. Proposed: masked display (`***********1234` style) with a provenance link to the official SECOP source record (`urlproceso`, PR-01) — the citizen who genuinely needs the full value re-verifies at the source, preserving the verification path without our platform becoming the easiest aggregation point.
- **Bank data:** never displayed; the fields' existence is stated in the limitations note (PR-08: "payment account details exist in the source record but are not shown here").
- **Masking is applied at the disclosure/template layer**, never by mutating stored values — masked rendering is a presentation rule, keeping the normalized layer honest and the traceability chain intact.

## 4. Search and indexing restrictions (proposal)

- **No full-text or exact-match search index over excluded fields** (all HUMAN-6, all ID numbers of natural persons). Search infrastructure must not build inverted indexes over them even where stored.
- **`documento_proveedor` (natural persons):** search by supplier permitted via name, not by ID number, in Phase 1 — searching by cédula is the single highest-amplification profiling feature and is **proposed as forbidden in Phase 1**. This is also PR-09-adjacent: building person-centric lookup by government ID approaches surveillance functionality the platform's principles do not authorize.
- **Names of role-actors:** indexable and searchable (accountability function; public officials in role).
- **Aggregation caution:** features that join a natural person across many contracts ("every contract where X's cédula appears") are **proposed as out of scope** pending the human's explicit decision — such features are exactly the "red flag"-adjacent territory ADR-008 reserves for later, human-validated design.

## 5. Historical release considerations

- Because releases are immutable (ADR-018), exposure rules apply **per release at render time**, not by rewriting history. A masking or exclusion rule change is applied by re-rendering disclosure views — never by mutating releases.
- **Compiled release** surfaces latest values: if a field is excluded from normalization, it is absent from the compiled view at every point in history — the exclusion is retroactive-by-derivation, without touching immutable data. This is a structural advantage of the hybrid strategy; state it explicitly in mapping rules.
- **Versioned release (Phase 2 candidate):** field-level history for personal fields would expose *every historical value* of, e.g., a representative's ID. Propose: **personal fields are excluded from versioned-release history features** by default (the accountability value of change-history is procurement-substance fields: values, dates, statuses — not a person's domicile history).
- **No deletion pathway exists for raw/releases** (PR-05). If a legal requirement to erase data ever arises (e.g., SIC order), the compliance mechanism is access/exposure restriction, and that reality must be recorded as a limitation (PR-08) — flagged for your legal review (§7, LQ-4).

## 6. Transparency vs. privacy trade-offs (the framing for your decision)

- **The platform re-publishes; it does not originate.** Ley 1712 de 2014 makes this data public; SECOP's own interface displays these fields. The project's marginal effect is **aggregation, searchability, and permanence** — the dimensions worth governing.
- **PR-01/PR-02 pull toward full disclosure with method:** hiding source-published data undermines "teach the method" (a citizen comparing our masked view with SECOP's unmasked view may lose trust).
- **PR-09/PR-10 pull toward restriction:** effortless person-level aggregation across millions of contracts creates surveillance capability the platform never justified.
- **The proposed synthesis:** substance transparent, identity protected — display role-actor names and all procurement substance; keep private identifiers out of the normalized layer and out of search; route any genuine identity need to the official source via provenance links.
- **An honest cost of this synthesis (PR-08):** sole-trader supplier identity becomes *harder* to trace on our platform than on SECOP itself. That limitation must be stated wherever supplier identity is shown.

## 7. Colombian legal considerations requiring human review (questions, not conclusions)

The Architect does not interpret law (PR-15). The following are framed for your review, ideally with legal counsel:

- **LQ-1 — Habeas data scope:** Ley 1581 de 2012 (personal data protection; Superintendencia de Industria y Comercio as authority) and its relationship to data whose publication is mandated by the transparency regime (Ley 1712 de 2014). Does the "data made public by law" condition exclude SECOP-published personal fields from habeas data duties in our context, or do duties (e.g., purpose limitation) survive re-publication by a third party?
- **LQ-2 — Role-actor data:** are legal representatives, supervisors, and ordenadores del gasto/pago acting in a public capacity such that their names/IDs fall outside private-data treatment — and does that differ for natural-person suppliers (cédula as merchant identity)?
- **LQ-3 — Amplification responsibility:** does re-publication with added search/aggregation capability create duties beyond the source's (e.g., "seamless access" standards, deletion requests forwarded to us)?
- **LQ-4 — Erasure orders:** if a regulator orders suppression of a person's data, what is the compliant mechanism given immutable raw/releases — exposure suppression with documented limitation, or something else?
- **LQ-5 — Gender field:** `g_nero_representante_legal` has no procurement-verification value; is its continued ingestion-mapping justified at all, or should it be raw-only?
- **LQ-6 — Minors / sensitive categories:** standard Ley 1581 sensitive-category rules (none observed in current fields, but the flag should exist in mapping rules for future datasets).

## 8. Fields requiring masking, redaction, restriction, or exclusion (consolidated register — accepted as binding)

| Treatment | Fields | Layer |
|---|---|---|
| **Exclusion from normalized layer** (raw-only) | All HUMAN-6 (3 bank fields); all natural-person ID numbers (`identificaci_n_representante_legal`, 3 ordenador/supervisor document-number fields); `domicilio_representante_legal`; `g_nero_representante_legal` (pending LQ-5); `nombre_del_adjudicador` (pending HUMAN-9c review) | Normalized, search, display |
| **Map, but masked display + no ID search** | `documento_proveedor` for `Cédula de Ciudadanía` suppliers | Display/search layers only |
| **Map and display with role labels** | Role-actor names (`nombre_representante_legal`, `nombre_supervisor`, `nombre_ordenador_del_gasto`, `nombre_ordenador_de_pago`) | All layers |
| **Map in full** | All procurement-substance fields; `documento_proveedor` for NIT suppliers | All layers |
| **Phase 1 forbidden features** | Search by cédula; person-across-contracts aggregation | Platform capability level |

Every row above is a **proposal requiring your validation** (PR-06/PR-15); the register is designed to become the binding rule the mapping phase consumes.

## Consequences

- **If accepted:** the mapping phase receives a binding exposure register; storage architecture inherits hard requirements (raw access restriction, no-index constraints, disclosure-layer masking); the dictionary's HUMAN-6/HUMAN-9 flags gain dispositions; PR-08 limitation text is defined for supplier-identity and bank-data gaps.
- **If edited:** the register structure survives; treatments change.
- **In all cases:** raw-layer immutability is untouched (PR-05 compliant); the final legal posture depends on your review of LQ-1…LQ-6, ideally with counsel.

## Constitutional Test checklist (principles.md §6)

| # | Test | Status | Note |
|---|---|---|---|
| 1 | Method (PR-01) | Pass | Provenance links preserve verification paths for masked values; limitations stated |
| 2 | Verdict (PR-02) | Pass | No conclusions about persons; role-actor display is factual, source-published |
| 3 | Ecosystem (PR-03) | Pass | Defers identity needs to the official source rather than duplicating surveillance-adjacent capability |
| 4 | Permanence (PR-04) | Pass | Rules documented as a durable register; retroactive-by-derivation mechanism avoids fragile exceptions |
| 5 | Lineage (PR-05) | Pass | Raw immutable; no mutation proposed anywhere |
| 6 | Invention (PR-06) | Pass | Every field disposition traces to dictionary flags; legal claims framed as questions |
| 7 | Reliability (PR-07) | Pass | Level-2/sentinel status respected in each treatment |
| 8 | Limitations (PR-08) | Pass | Sole-trader identity and bank-data gaps stated; disclosure texts specified |
| 9 | Indicators (PR-09) | Pass | Person-aggregation features proposed forbidden; profiling risk is the ADR's core concern |
| 10 | Authenticity (PR-10) | Pass | N/A |
| 11 | Vulnerability (PR-11) | Pass | N/A (no territory-locating features; noted for future datasets) |
| 12 | Labeling (PR-12) | Pass | N/A (no AI content) |
| 13 | Purpose (PR-13) | Pass | No engagement-optimizing features; restrictions reduce capability |
| 14 | Secrecy (PR-14) | Pass | Restricted-data handling requirements recorded; no secret values involved |
| 15 | Authority (PR-15) | Pass | Legal interpretation and final decision reserved to the human; all dispositions are proposals |

---

---

## Pending Items (open decisions)
- **P-01** — At what stage should the "red flag" engine be integrated? It has already been decided that it will be included (ADR-008); the specific timing remains to be defined.
- **P-02** — Name for the social/community layer (distinct from the "Veeduría Abierta" editorial platform).
- **P-03** — Decided 2026-10-21: agents may not perform git commits, merges, deployments, migrations, or any repository/environment-altering action without explicit human approval for that specific action. Agents propose; humans execute.
- **P-04** — Resolved via ADR-015 (2026-10-21): Laravel Boost is **not adopted at this time**; a controlled evaluation may be conducted once Phase 1 begins, under the evaluation protocol, acceptance criteria, and rollback strategy defined in ADR-015.
- **P-05** — Resolved via ADR-018 (2026-10-21): OCDS storage strategy is Hybrid —
  releases canonical + derived current-state projection. (P-05 was pre-established as the
  storage-strategy pending item in the data dictionary; the narrative-tone item drafted
  under this ID in ADR-017 was renumbered to P-06 per human decision.)
- **P-06** — Remaining: the Spanish wording and tone of the public narrative (`docs/vision.md` §6) await human approval before any citizen-facing publication. The Master Plan v2 citation of `docs/vision.md` was resolved on 2026-10-21: Master Plan v2 (revision 0.2) applied to `docs/master-plan.md` per human approval.