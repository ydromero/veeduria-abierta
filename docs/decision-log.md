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
- **Decision:** Use Basic Auth with a Key ID and Key Secret (rather than `$$app_token`). Credentials must be stored exclusively in the `.env` file; see ADR-009. ## ADR-004 — Fixed Stack Versions
- **Status:** Accepted · 2026-10-19
- **Decision:** The versions installed during the initial session are locked; updates will be handled later as a separate task.
- **Versions verified during that session:** Laravel 13.37.0 · PHP 8.5 (`sail-8.5/app` image) · Livewire 4.4.7 · `laravel/ui` 4.6.3 with Bootstrap 5.3.8 installed (constraint `^5.2.3` in `package.json`) · PostgreSQL 18 (`postgres:18-alpine`) · Vite 8.3.4 · Node 24 (verified by a human on 2026-10-20: Node v24.22.0 inside the Sail container; the host's Node version, 22.x, is not used).
- **Known risk:** Most examples and templates are based on Livewire 3. All Livewire syntax must be verified against the code in `vendor/livewire/livewire` or the v4 documentation before use.

## ADR-005 — Job Queue Using the `database` Driver
- **Status:** Accepted · 2026-10-19
- **Decision:** `QUEUE_CONNECTION=database` (the `jobs` table has already been migrated). No Redis or Horizon in Phase 1.
- **Verified by a human on 2026-10-20:** `.env` contains `QUEUE_CONNECTION=database` (the agent cannot read the `.env` file).
- **Review when:** The volume of historical data ingestion causes the queue to slow down. ## ADR-006 — Testing against the `testing` database
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
- **Rule:** No secrets—of any kind—may be included in the repository or any documentation. ## ADR-010 — Dedicated Local Ports
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

## Pending Items (open decisions)
- **P-01** — At what stage should the "red flag" engine be integrated? It has already been decided that it will be included (ADR-008); the specific timing remains to be defined.
- **P-02** — Name for the social/community layer (distinct from the "Veeduría Abierta" editorial platform).
- **P-03** — Decided 2026-10-21: agents may not perform git commits, merges, deployments, migrations, or any repository/environment-altering action without explicit human approval for that specific action. Agents propose; humans execute.
- **P-04** — Resolved via ADR-015 (2026-10-21): Laravel Boost is **not adopted at this time**; a controlled evaluation may be conducted once Phase 1 begins, under the evaluation protocol, acceptance criteria, and rollback strategy defined in ADR-015.
- **P-05** — Proposed with ADR-017: the Spanish wording and tone of the public narrative (`docs/vision.md` §6) await human approval before any citizen-facing publication. Master Plan §2 citation of `docs/vision.md` also awaits the human edit.