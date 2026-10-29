# Session Handoff 3 — HUMAN-3 Closure (V-7 + FV-3 + AMBIG-8)
**Date:** 2026-10-21 (session 3) · **Last commit:** `d192661` ("HUMAN-3" — human-executed) · **Working tree:** clean
**Supersedes:** `handoff-2026-10-21-2-data-ingestor-activation.md` for *current state* (handoffs 1–2 remain valid history).
**Purpose:** Seed a new session with full institutional context. Read this file first, then follow §10 (Restart Instructions).

---

## 1. Current Project State

**High-level:** Open-source civic platform for forensic analysis of Colombian public
procurement (SECOP II → OCDS 1.1.5). Governance and knowledge layers mature; Phase 1 in
progress. **The milestone of this session: HUMAN-3 (budget origin/destination) is fully
closed** — all eight HUMAN-3 checklist rows decided, applied, and verified, via
Field-Validation Records FV-1/FV-2/FV-3 and dictionary validation notes V-5/V-6/V-7,
including resolution of AMBIG-8 (name-governing scope).

**Phase:** Phase 1 — SECOP II ingestion and explorer (Master Plan §5.1). Phase 0 complete.

**Major milestones completed (cumulative):**
1. Constitutional layer (`principles.md` v1.0, ADR-014) — Tier E: PR-05/06/09/10/12/15.
2. Vision adopted (ADR-017) + Founder's Intent (ADR-022).
3. Master Plan v2 applied; dataset discovery (952→16); Dictionary v1.0; ADR-018/019/020.
4. Raw layer implemented (append-only, provenance-bearing) — 4 green feature tests.
5. Data Ingestor chartered (ADR-021), activated via `/reload`, proven in live operation.
6. Batch 1 evidence package complete (22 fields, all non-personal HUMAN flags).
7. **This session:** V-5 (`origen_de_los_recursos`), V-6 (`destino_gasto`), V-7 (six
   component fields = validated additive decomposition of `valor_del_contrato`),
   FV-1/FV-2/FV-3 records, AMBIG-8 resolved — applied by the Data Ingestor per explicit
   human approval and committed by the human as `d192661`.

**Active agents and status:**
- **Data Ingestor** — active (charter v1.0, ADR-021). Owns Phase 1 data plane, runbook
  operational sections §5–§9, `.pi/artifacts/dq-report-*.md`. Applied the HUMAN-3
  closures this session. Channel rule active: repo access via read/grep/ls; bash only
  for the §5 allowlist, with mandatory §5.1 traceability.
- **Project Architect** — active (charter v1.0). Governance/memory plane: `.pi/memory/`,
  docs-map, handoffs. Runbook ownership = coordination only (ADR-021 supersession).

**Current implementation status:** raw layer tables + 4 green tests; `SecopPreflight`
(schema-drift gate) and `SecopFieldStats` (field census) functional read-only commands;
`FetchSecopData` and `ProcessSecopContract` are empty stubs. **Bulk ingestion
(`secop:ingest`) is designed-not-built** and is gated on dictionary-validation progress.

---

## 2. Governance Layer Status

| Layer | Status |
|---|---|
| Vision (`docs/vision.md`) | Adopted baseline (ADR-017). Spanish narrative tone pending human approval (P-06). |
| Founder's Intent (`docs/founders-intent.md`) | Adopted (ADR-022). Informative layer 7; constitution wins divergences. |
| Constitutional layer (`docs/principles.md` v1.0) | Accepted (ADR-014). **Tier E: PR-05, PR-06, PR-09, PR-10, PR-12, PR-15.** Amendment human-only, explicit ADR + AGENTS.md update, never silent. |
| Master Plan (`docs/master-plan.md` v2) | Applied; product-scope layer (level 3); citation-based. |
| ADRs 001–022 | All accepted **except ADR-001a (still Proposed — open human item; all work proceeds under its classification).** |
| New FV records | **FV-1** (origen_de_los_recursos), **FV-2** (destino_gasto), **FV-3** (component additivity + AMBIG-8) — all accepted 2026-10-21, committed in `d192661`. Future field validations follow the FV-record + V-note format (human's chosen precedent). |
| Pending items | P-01 (red-flag phase) · P-02 (social-layer name) · P-06 (narrative tone) — open. P-03/04/05 resolved. |

---

## 3. SECOP Knowledge Layer Status

**Dataset discovery (ADR-001a):** 952 catalog results → **16 primary datasets**.
- **Tier A (Phase 1 truth):** `jbjy-vk9h` Contratos Electrónicos — **5,644,941 rows / 95 cols**; `p6dx-8zbt` Procesos de Contratación — **9,349,940 rows / 59 cols**.
- **Tier B (deferred):** `rpmr-utcd` (22,850,110 rows — double-counting risk vs `jbjy-vk9h`; needs de-dup ADR before any use); `u8cx-r425` (30,144,236 rows — own increment later).
- **Tier C (out of scope):** 12 datasets + all SECOP I legacy.

**Data Dictionary (`docs/data-dictionary.md` v1.0):** all fields transcribed from verified
API metadata; OCDS mappings CANDIDATES pending validation; reliability levels 1/2 with
sentinel documentation. **Status of validation flags after this session:**
- **HUMAN-3 — VALIDATED (closed).** Rows 33–34 (V-5/V-6) and 56–61 (V-7) carry
  `[HUMAN-3 → validated 2026-10-21]`; row 59 also `[AMBIG-8 → resolved, name governs]`.
- **HUMAN-6 — resolved** by ADR-020 (excluded from normalized layer; raw-only).
- **Open HUMAN flags:** HUMAN-1 (`valor_amortizado`, `valor_pendiente_de`) · HUMAN-2
  (`saldo_cdp`, `saldo_vigencia`) · HUMAN-4 (`orden`, `sector`, `rama`,
  `entidad_centralizada` + `ordenentidad` in p6dx) · HUMAN-5 (`tipo_de_contrato`,
  `modalidad_de_contratacion` — ⚖️ legal route) · HUMAN-7 (`es_grupo`, `es_pyme`) ·
  HUMAN-8 (`duraci_n_del_contrato`) · HUMAN-9 (Batch 2, deferred by design) ·
  HUMAN-10 (`dias_adicionados`).
- **AMBIG flags:** open = AMBIG-1…5, 7, 9; **AMBIG-8 RESOLVED** (name governs,
  2026-10-21); AMBIG-6 partially resolved (nested-object `urlproceso` — runbook §10
  Decision 2). Flag totals restated in the dictionary: **UNKNOWN 2 · AMBIG 9 (8 open) ·
  HUMAN 10 (9 open) · GAP 19.**

**Validation findings V-1…V-7 (all accepted 2026-10-21, binding):**
- **V-1:** `id_contrato` (`CO1.PCCNTR.*`) is a logical business identifier with
  source-side duplicate re-publications → releases must collapse duplicates via state-hash.
- **V-2:** process → contract is **1:N** (top observed 654 contracts/process).
- **V-3:** contract-side supplier fields (`jbjy-vk9h`) are **authoritative** for supplier
  identity; process-side supplier fields supplementary only.
- **V-4 (CRITICAL):** authoritative contract↔process linkage is
  `proceso_de_compra` ↔ `id_del_portafolio` (`CO1.BDOS.*` both sides);
  `id_del_proceso` (`CO1.REQ.*`) **never** joins contracts to processes.
- **V-5 (FV-1):** `origen_de_los_recursos` is a published classification attribute;
  `Recursos Propios` = funded exclusively by own resources; `Distribuido` = residual,
  breakdown NOT guaranteed; no code may assume decomposability.
- **V-6 (FV-2):** `destino_gasto` = 4-value codelist (`Inversión` 61.72%, `Funcionamiento`
  37.60%, `No Definido` 0.44%, `No aplica` 0.24%); sentinels preserved verbatim as
  distinct categories, never NULL-converted, never merged.
- **V-7 (FV-3):** the six component fields (`presupuesto_general_de_la_nacion_pgn`,
  `sistema_general_de_participaciones`, `sistema_general_de_regal_as`, `recursos_de_credito`,
  `recursos_propios`, `recursos_propios_alcald_as_…`) are the **validated additive
  decomposition** of `valor_del_contrato`: COP values; `0` = no contribution recorded
  from that source; expected Σ(components) = `valor_del_contrato`. **Deviations are
  published data-quality anomalies: logged and preserved — never corrected, estimated,
  normalized, or silently excluded.** Reconciliation tests are diagnostic, never
  corrective. The two own-resources fields are distinct and non-redundant components;
  row 59 is name-governed (territorial entities + Indigenous resguardos specifically).
- **V-5 interplay (unchanged, binding):** the ~11.38% of `Distribuido` rows with all six
  components = 0 despite real contract values, and the 512 degenerate `Recursos Propios`
  placeholder rows, remain logged anomaly classes.

**Conceptual model:** `.pi/artifacts/draft-conceptual-data-model.md` — **awaiting human
acceptance** (open item).

---

## 4. Data Ingestor Status

**Implemented and functional:**
- Charter v1.0 (`.pi/agents/data-ingestor.md`), accepted via ADR-021, active via `/reload`.
- Scoped bash allowlist (§5): `./vendor/bin/sail artisan secop:*`, `queue:*` (work/restart/
  failed/retry), `test` (ingestion paths), `tinker --execute` (read-only verification).
  Mandatory §5.1 traceability per command (verbatim command, exit status, affected
  files/tables/batch range, side effects).
- DQ reporting duties (§6): completeness/anomaly/coverage reports per data-writing run;
  schema-change pre-flight before every run with hard stop on drift.

**Smoke test (passed):** schema-drift gate + completeness baseline; no drift; delta +0.

**Existing commands** (`app/Console/Commands/`):
- `SecopPreflight.php` — read-only drift gate: live metadata vs verbatim dictionary
  baselines (95/59) + `count(*)` vs ADR-001a counts; exit 1 on drift. **Verified this
  session: all six component fields present, names/types unchanged → V-7 required NO
  baseline edit** (semantics-only change; runbook §5.1).
- `SecopFieldStats.php` — read-only census (`secop:fieldstats {dataset} {field}`).
  **Known limitation:** census fetch window is API-ordered, not count-ordered — on
  `census_truncated: true`, top values and "sentinels: none" are window-dependent;
  standing rule: cross-check sentinels with dedicated read-only count queries.

**Existing reports (all in gitignored `.pi/artifacts/` — local to this machine):**
- `dq-report-2026-10-21-smoke-validation.md`
- `dictionary-validation-batch-1-jbjy-vk9h.md` (**v5** — checklist rows #5–#10, #21, #22
  annotated DECIDED & APPLIED; §3.6/§3.8 resolved answers; §12 Group 1 COMPLETE)
- `dq-report-2026-10-21-human-3-closure.md` (this session's closure report)
- `draft-v7-fv3-component-additivity-h3.md` (superseded — the applied wording is in the
  committed docs)

**Current responsibilities:** dictionary-first field protocol; same-task mirror of any
dictionary change into `SecopPreflight.php` (§5.1); Tier A only; no indicator logic
(ADR-008); no `.env` access; no commits (P-03).

**Known limitations:** census-window truncation (above); SODA transient timeouts
(cURL 28 — retry/backoff exists in SecopPreflight, must be replicated in the bulk
client). **Self-reported process deviations this session (accepted as process issues,
not data-integrity issues):** one bash command run in error (`sail artisan test` —
failed, no side effects) and one accidental no-op (`true`); both logged in traceability.

---

## 5. Storage Architecture Status

- **Accepted:** ADR-018 Hybrid — immutable synthesized releases canonical (one per
  observed state change; state-hash rule; V-1 duplicate collapse; sentinels preserved) +
  derived current-state projection (= OCDS compiled release). The projection must NEVER
  become an independent source of truth.
- **OCDS 1.1.5 pinned** (ADR-019). Release `date` candidate = observation timestamp
  (runbook §10 decision 4). Release `tag` mapping is HUMAN-5-gated. Public OCDS export
  forbidden before OCP prefix registration (human action).
- **Raw layer:** `secop_ingestion_batches` + `secop_raw_records` (append-only,
  provenance columns, defensive nullability on `source_row_id`).
- **Tests:** 4 green feature tests — `RawLayerMigrationTest`, `RawRecordAppendOnlyTest`,
  `RawRecordForeignKeyTest`, `RawRecordIdempotencyTest`.
- **Open implementation work:** unit tests for `SecopPreflight`/`SecopFieldStats`
  (human-approved, next ingestion testing package); `secop:ingest` bulk-load design
  (cursor pagination on `:id`, `DB::transaction`, queue jobs, provenance capture,
  state-hash release construction, compiled-release projection, ADR-020 exclusions);
  `phpunit.xml` pgsql/`testing` verification (ADR-006, still unverified).

---

## 6. Dictionary Validation Progress

**Batch 1 checklist state (23 rows; §10 numbering — note: the human's HUMAN-3 decision
cites §11 summary numbering #7–#12, which maps to §10 rows #7–#10, #21, #22):**

| State | Checklist rows | Content |
|---|---|---|
| **DECIDED & APPLIED** | #5, #6, #7, #8, #9, #10, #21, #22 | All eight HUMAN-3 rows (FV-1/V-5, FV-2/V-6, FV-3/V-7) + AMBIG-8 resolved |
| Resolved (scope) | #20 | Batch 1 scope: all non-personal HUMAN flags; HUMAN-9 → Batch 2 |
| **Open** | #1–#4 | HUMAN-1 + HUMAN-2 (fiscal: amortization, CDP/vigencia) |
| **Open** | #11–#14 | HUMAN-4 (administrative taxonomy) |
| **Open ⚖️** | #15–#16 | HUMAN-5 (legal-framework vocabularies — recommended human + counsel) |
| **Open** | #17–#18 | HUMAN-7 (flags; CCE linkage deferred) |
| **Open** | #19 | HUMAN-10 (extension days) |
| **Open ⚖️** | #23 | HUMAN-8 (duration grammar/parsing — affects OCDS period mapping) |

**Validation groups (package §12):** Group 1 (HUMAN-3) **COMPLETE** · Group 2
(HUMAN-1+2) **NEXT** · Group 3 (HUMAN-4) pending · Group 4 (HUMAN-5 ⚖️ + HUMAN-8 ⚖️)
pending · Group 5 (HUMAN-7 + HUMAN-10) pending · HUMAN-9 = Batch 2.

**Recommended review order (human's choice, PR-15):** Group 2 → Group 3 → Group 4 →
Group 5. The 0-semantics reasoning accepted in Group 1 (0 as a substantive published
state with anomaly preservation) transfers directly to Group 2; row #2's negative-values
disposition is the only judgment call there.

---

## 7. Immediate Next Actions (ordered)

| # | Action | Lead | Why this order |
|---|---|---|---|
| 1 | Human works Batch 1 checklist rows #1–#4 (Group 2 fiscal sitting) | **Human** | Active PR-06 gate; Group 1 closed, Group 2 is next per §12; evidence already in package §1.1–§2.2 |
| 2 | As answers land: Data Ingestor drafts dictionary updates + mirrors into `SecopPreflight.php` same-task (check §5.1 per change; note `p6dx-8zbt` fields for HUMAN-8/10 would extend Batch-1 scope) | **Data Ingestor** | Dictionary-first protocol; prevents stale-baseline drift |
| 3 | Unit-test package: `SecopPreflight` + `SecopFieldStats` (incl. census-window behavior) against `testing` DB | **Data Ingestor** | Human-approved; tooling reliability before bulk load |
| 4 | Batch 2 evidence: HUMAN-9 fields, ADR-020-aware (rates/patterns only, never raw personal values in artifacts) | **Data Ingestor** | Completes validation evidence; privacy-safe method |
| 5 | Draft `secop:ingest` bulk-load design for human review | **Data Ingestor** | Core Phase 1 deliverable; unblocked once validation progresses further |
| 6 | Human: accept ADR-001a; register ocid prefix with OCP; LQ-1…LQ-6 legal review; conceptual-model acceptance; P-01/P-02/P-06 | **Human** | Normative gates no agent may close |
| 7 | One-line fix: dictionary provenance-header status line (still says "V-1…V-4"; should read V-1…V-7) — flagged by the Ingestor, human-approved fix, not yet applied | **Data Ingestor** (upon approval) | Stale institutional text (PR-08) |
| 8 | Architect: docs-map upkeep + session-close handoff | **Project Architect** | Institutional memory (PR-04) |

---

## 8. Risks and Watch Items

**Data-quality (published anomalies — recorded, never corrected, PR-06):**
- Extreme maxima: `recursos_propios` ≈1.29×10¹⁶; `saldo_cdp` ≈1.1×10¹⁷; PGN ≈6.5×10¹⁵;
  `dias_adicionados` 730,577. Negative `valor_pendiente_de` (min ≈−3.3×10⁹).
- **V-7 additivity deviations are now a formal anomaly class** — any reconciliation
  test must log and preserve them (never filter/repair).
- Degenerate durations (`Dia(s)` 4.09%, `0 Dia(s)`); multiple coexisting sentinels
  (`No Especificado` + `No Definido`); casing/diacritic variants = distinct published
  values — never normalized at ingestion.
- Census-window truncation can hide sentinels (standing rule: dedicated count queries).
- `rpmr-utcd` double-counting risk if ever ingested without a de-dup ADR.

**Governance:**
- **ADR-001a still Proposed** while all work proceeds under its classification — accept soon.
- Bash-channel deviations recur in small ways — self-reporting works; watch for repetition.
- User-scope charters in `~/.pi/agent/agents/` predate ADR-16 (out-of-repo consistency
  check flagged to human; unresolved).
- HUMAN-5 vocabulary review and row #23 parsing rule gate the OCDS tender/status/period
  mappings — legal interpretation is a human domain (⚖️).

**Documentation synchronization:**
- **The `SecopPreflight.php` ↔ dictionary coupling (§5.1) is a two-file rule:** any
  future dictionary change (including per-field validations and Batch 2) must be checked
  in the same task. V-7 changed semantics only — verified no edit needed — but any
  field add/remove/retype WILL require the mirror.
- Stale dictionary provenance-header status line ("V-1…V-4") — one-line fix pending
  human approval (Action #7).
- `.pi/artifacts/` is **gitignored** — evidence packages and DQ reports are local-only.
  Institutional knowledge must live in the committed docs (dictionary §6/§10, decision
  log FV records, runbook §9). New sessions must not assume artifacts are in git.

**Implementation:**
- Livewire 4 syntax risk when UI work starts (verify against vendor/docs).
- `phpunit.xml` pgsql/`testing` verification still pending (ADR-006).
- Scale: 5.6M + 9.3M rows — cursor pagination, background jobs, throttling mandatory;
  queue `database` driver throughput is the ADR-005 review trigger.
- `urlproceso` nested-object payload; NIT always `text` (runbook §10).

---

## 9. Repository Snapshot

**Git:** latest `d192661` ("HUMAN-3" — human-executed: CHANGELOG, dictionary,
decision-log); working tree **clean**. Recent: `d192661` HUMAN-3 · `d298074` handoff 2 ·
`5ea1f4b` Batch 1 v2 · `a04c52d` smoke test · `f519729` Data Ingestor charter + ADR-021/022.

**Files modified but not yet committed:** none. **Pending commits:** none.

**Recommended commit messages already used:** the human committed the HUMAN-3
application as `HUMAN-3` (their own wording); the Ingestor's proposed message is
superseded by that commit — no action needed.

**Local-only artifacts (gitignored, exist only on this machine — must not be treated as
lost institutional knowledge; their content is summarized in committed docs):**
`.pi/artifacts/dictionary-validation-batch-1-jbjy-vk9h.md` (v5),
`.pi/artifacts/dq-report-2026-10-21-human-3-closure.md`,
`.pi/artifacts/draft-v7-fv3-component-additivity-h3.md`,
`.pi/artifacts/dq-report-2026-10-21-smoke-validation.md`, plus all draft artifacts.

**Must not be forgotten before the next session:**
1. **HUMAN-3 is fully closed** — future sessions must not re-derive `origen_de_los_recursos`
   semantics, `destino_gasto` codelist handling, component additivity, or the row-59/61
   scope split (V-5/V-6/V-7 + FV-1/2/3 are binding).
2. The V-7 anomaly rule (log & preserve deviations; never correct) applies to every
   future reconciliation test.
3. The §10↔§11 numbering mapping in the evidence package (decision "#7–#12" = §10 rows
   #7–#10, #21, #22) — recorded in FV-3.
4. Docs-map pointer update to this handoff (applied with this file).

---

## 10. Restart Instructions (new session)

1. **Read, in order:** `AGENTS.md` → `docs/principles.md` (§1, §2, §6) → this file →
   `docs/master-plan.md` §5 → `docs/decision-log.md` (skim ADR register; read 018/019/020/021
   and FV-1/FV-2/FV-3 in full) → `docs/data-dictionary.md` (provenance header + §1 + §6
   validation notes + §12) → `docs/ingestion-runbook.md` → `.pi/memory/docs-map.md`.
2. **Charter reading if working with/as an agent:** the relevant `.pi/agents/*.md` in full.
3. **Current priority:** the Batch 1 checklist rows #1–#4 (Group 2 fiscal sitting) are
   the single active gate — the human validates; the Ingestor drafts dictionary updates
   and mirrors them same-task when required.
4. **Standing operational rules:** every agent bash command → §5.1 traceability log;
   schema pre-flight (`secop:preflight`) before any ingestion run; `DB::transaction` +
   append-only + provenance on every write; unknowns are errors, never placeholders;
   never read `.env`; never commit (P-03); Tier A only; census-window rule on truncated
   fields; no public OCDS export before OCP prefix registration; no search-by-cédula or
   person-aggregation features.
5. **Do NOT revisit (settled unless contradictory evidence appears):** Tier A/B/C
   classification · OCDS 1.1.5 pin · hybrid storage strategy (releases canonical) ·
   ADR-020 exposure register · ADR-021 charter decisions · stack pins · cursor
   pagination · English-internal/Spanish-citizen language split · Tier E membership ·
   "no new SECOP viewer" · **V-1…V-7 and FV-1/FV-2/FV-3 (including AMBIG-8's
   name-governing resolution and the additive-decomposition rule with anomaly
   preservation)**.
6. **Do NOT let work proceed past:** unvalidated dictionary fields into normative use ·
   raw-layer mutation of any kind · correction/normalization/silent exclusion of
   published anomalies (V-7 rule) · HUMAN-9 evidence with raw personal values in
   artifacts · a new validation group before the human has reviewed the HUMAN-3 closure
   report (`.pi/artifacts/dq-report-2026-10-21-human-3-closure.md` — already delivered;
   next group may start once the human reviews this handoff).
7. **When information is missing:** one clear specific question + recommended course —
   never an assumption (AGENTS.md; PR-06/PR-15).

*Handoff authored by the parent session per the Architect's charter duty; human-approved
content throughout.*
