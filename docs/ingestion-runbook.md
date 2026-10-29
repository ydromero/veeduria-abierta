# Ingestion Runbook — Veeduría Abierta

**Status:** Skeleton v0.1 — created 2026-10-21 (approved skeleton; operational content fills as ingestion work proceeds)
**Purpose:** Operational procedures for discovering, verifying, and bulk-ingesting SECOP II data via the SODA API. Companion to `docs/data-dictionary.md` (field truth) and ADR-001/001a/002/003 (dataset definition, pagination, authentication).

## 1. Scope and principles
- Applies to Tier A datasets only (`jbjy-vk9h`, `p6dx-8zbt` — ADR-001a).
- Raw data is immutable and provenance-bearing (PR-05); nothing is invented (PR-06); limitations are stated (PR-08).
- All bulk loads use cursor-based pagination keyed on `:id`; `$offset` is never used for bulk loads (ADR-002).

## 2. Access and authentication
### 2.1 App Token — operative rule (exact approved wording, 2026-10-21)
> "App Token is optional for discovery activities and strongly recommended for bulk-ingestion workloads."

### 2.2 Credentials
- Key ID + Key Secret via Basic Auth for authenticated workloads (ADR-003).
- Credentials live exclusively in configuration / the environment file; never in code, docs, logs, or chat (ADR-009, PR-14). Agents never read the environment file.

## 3. Verified endpoints (2026-10-21)
- Resource: `https://www.datos.gov.co/resource/{datasetId}.json`
- Catalog search: `https://www.datos.gov.co/api/catalog/v1?q=...`
- Metadata: `https://www.datos.gov.co/api/views/{datasetId}.json`
- SoQL: `$select=count(*)`, `$limit=1` for samples; cursor pagination for bulk.

## 4. Discovery and verification procedure
1. Catalog search and deduplication of filter views (ADR-001a method).
2. Row-count verification via `count(*)`.
3. Column metadata via `/api/views/{datasetId}.json`.
4. Sample payload via `$limit=1`.
5. Record findings in `.pi/artifacts/`; propose data-dictionary updates (PR-06).

## 5. Bulk ingestion procedure
1. Pre-flight: dictionary fields verified and human-validated (PR-06 hard gate).
2. Pagination: cursor keyed on `:id` (ADR-002); throttling behavior documented here as observed.
3. Writes: append-only immutable staging (PR-05).
4. Missing critical data: log, report, stop — never a placeholder (PR-06). A null `source_row_id`
   (SODA `:id`) on a Tier A record is such an anomaly: the identifier is expected on every Tier A
   row; its nullability is a defensive safeguard only, and any null requires review before the
   batch is considered clean.
5. Provenance per record: source dataset, query/pagination window, timestamp.
6. Release construction: per source row, a substantive-state hash comparison decides whether a
   new release is created — identical content produces no release (V-1 duplicate collapse).
   `release_date` is the observation timestamp (§10, decision 4).

### 5.1 Safeguard — schema-drift baseline coupling (added 2026-10-21, human-approved)

The schema-drift baselines embedded as constants in
`app/Console/Commands/SecopPreflight.php` (`DICTIONARY_JBJY_VK9H`, `DICTIONARY_P6DX_8ZBT`)
are a **verbatim transcription** of `docs/data-dictionary.md` §2 and §3
(verified 2026-10-21). They are a *derived copy*, not an independent source of truth
(PR-06: the dictionary is field truth).

**Coupling rule (binding):** any change to the data dictionary — a field added,
removed, or retyped, **including** the acceptance of any field-validation proposal
(e.g., the Batch 1 HUMAN-flag validations) — **MUST be mirrored into
`SecopPreflight.php` in the same task**. If the dictionary changes and the command
does not, drift detection compares live metadata against a stale baseline and can
neither detect real drift nor avoid false positives, silently breaking the
charter §6 schema-change gate.

Human validation of a dictionary proposal is therefore a two-file change:
`docs/data-dictionary.md` **and** `app/Console/Commands/SecopPreflight.php`.

## 6. Error handling and reporting
- Error classes, retry policy, escalation to the human. *(To be filled during implementation.)*

## 7. Verification and quality checks
- Post-ingestion reconciliation. *(To be filled during implementation.)*

## 8. Coverage gaps and limitations
- Pointer to `docs/data-dictionary.md` §5 (PR-08).

## 9. Change log
- Append-only; every procedure change dated.
- **2026-10-21 — §5.1 added (safeguard note, human-approved):** documented the
  verbatim-transcription coupling between the `SecopPreflight.php` drift baselines and
  `docs/data-dictionary.md` §2/§3; any dictionary change (including acceptance of
  field-validation proposals) must be mirrored into the command in the same task, or
  drift detection runs against a stale baseline. No procedure change beyond the note;
  no data written.
- **2026-10-21 — Observed tooling behavior: `secop:fieldstats` census window is not
  count-ordered (Batch 1 v2 completion; read-only, no data written).** For fields whose
  distinct-value census exceeds the fetch cap (1,001 groups), the SODA `$group` window
  is returned in API-internal order, **not** by row count; the command's client-side
  top-N therefore describes only the fetched window. Observed live on
  `jbjy-vk9h.duraci_n_del_contrato`: the sentinel literal `No definido` (230,963 rows,
  4.09% of the dataset) was absent from the fieldstats census, top-values, and sentinel
  observation, and was found only via auxiliary read-only count queries (SoQL length
  histogram + unit-family `LIKE` + residual `NOT LIKE`; anonymous; §3 endpoint only).
  **Standing rule for future evidence runs:** when `census_truncated: true`, treat
  top-values and "sentinels: none" as window-dependent, and cross-check sentinels with
  dedicated read-only count queries before reporting absence. `SecopFieldStats` code is
  unchanged; its unit-test proposals are deferred to the next ingestion testing package
  (human decision, 2026-10-21). Recorded in the evidence package
  `.pi/artifacts/dictionary-validation-batch-1-jbjy-vk9h.md` §13 L6.
- **2026-10-29 — Process note: `sail up -d` allowlist deviation — disposition accepted
  (human ruling, Group 2 fiscal sitting, Q3).** A prior-session use of an environment
  command (`sail up -d`) outside the charter §5 bash allowlist was self-reported and
  reviewed. The human ruled (2026-10-29): **documented process issue with no data-plane
  impact; no further action required** — disposition is a logged process issue, closed
  with this note. Standing treatment for deviations of this class remains unchanged:
  self-report in traceability, log here, and never treat as a data-integrity event
  unless raw data or provenance were touched (they were not).
- **2026-10-29 — Group 2 row #2 partial validation applied (human Q1/Q2 rulings);
  §5.1 two-file rule verification (no baseline edit required); relationship-evidence
  run (read-only).** The human decided (2026-10-29): **Q1 APPROVED** — `valor_pendiente_de`
  (dictionary §2 row 41) confirmed as the value pending amortization to date (COP);
  field-name ambiguity resolved. **Q2 Option B** — negative values preserved exactly as
  published, never corrected/clamped/imputed, ADDITIONALLY recorded as a PR-08
  source-data-quality limitation; legitimacy vs source error undetermined at this
  stage. **Q3 NOT decided** — the `valor_amortizado` ↔ `valor_pendiente_de`
  complement relationship remains OPEN; **no V-9/FV-5 written** (drafts only, in the
  evidence package). Applied to the dictionary: row 41 wording + flag; §5
  limitation-register bullet (negative values); [HUMAN-1] checklist row and flag
  totals. Two-file rule (§5.1): semantics-only change — pre-flight re-run
  2026-10-29T05:58:56Z (**NO DRIFT**, both datasets; `valor_pendiente_de` live type
  `number`); `SecopPreflight.php` baseline already carries
  `"valor_pendiente_de" => "number"` — verified in source, no edit made. Evidence
  run: 40 anonymous read-only SoQL aggregate queries against `jbjy-vk9h` via
  `sail artisan tinker --execute` (runbook §3 endpoint only; dedicated full-dataset
  counts, no truncated census windows, no `$offset`); correlation, both-non-zero
  counts, identity tests (trivial-vs-substantive decomposition), sign-pattern grid,
  and negative-value prevalence (57 rows, 0.00101%) recorded in
  `.pi/artifacts/dq-report-2026-10-29-group2-row2-relationship-evidence.md`. No data
  written; no environment commands.
- **2026-10-29 — §5.1 two-file rule verification (V-8 application, no baseline edit
  required).** Dictionary §2 row 40 (`valor_amortizado`) was updated to its validated
  wording (V-8, FV-4 — human decision 2026-10-29). Per §5.1 the change is
  **semantics-only**: `fieldName` (`valor_amortizado`) and `dataTypeName` (`number`)
  are unchanged in the live API metadata (schema-change pre-flight re-run 2026-10-29:
  NO DRIFT, both Tier A datasets), and the `SecopPreflight.php` baseline constants
  already carry `"valor_amortizado" => "number"` verbatim. Verified in the command
  source; no edit made. Drift detection remains aligned with the dictionary.
- **2026-10-29 — Group 2 row #2 final validation applied (human Q3 ruling — relationship INDETERMINATE); §5.1 two-file rule verification (no baseline edit required).** The human rendered the final decision for `valor_pendiente_de` (dictionary §2 row 41): **Q3 option (ii)** — the complement relationship between `valor_amortizado` and `valor_pendiente_de` is recorded as **INDETERMINATE**; the evidence package does not support a validated complement relationship between `valor_amortizado` and `valor_del_contrato`, between `valor_amortizado` and `valor_pagado`, or any other published base currently evaluated; **no complement rule is adopted and no arithmetic identity is assumed**; future code, mappings, indicators, and documentation must treat the relationship as unresolved unless new evidence emerges. The human also confirmed the 0-semantics clause: `0` is a substantive published state, never treated as missing data, never converted to NULL, with the approximately 99.9% zero-dominance documented as a PR-08 limitation. Applied: dictionary §6 V-9 note (accepted), §2 row 41 final validated wording + flag, §10 evidence-log V-9 row, [HUMAN-1] checklist row closed and flag totals updated (HUMAN-1 fully closed for both its fields); decision-log FV-5 record added; CHANGELOG entry added. The §5 negative-value limitation-register entry remains as applied (unchanged). Two-file rule (§5.1): semantics-only change — `SecopPreflight.php` baseline verified in source (`"valor_pendiente_de" => "number"` already carried verbatim; `fieldName`/`dataTypeName` unchanged for row 41); no edit made; live metadata unchanged since the 2026-10-29T05:58:56Z pre-flight (NO DRIFT, both datasets). No data written; no environment commands.

## 10. Storage and ingestion decisions (human-approved 2026-10-21)
1. **NIT normalization:** all NIT values normalized as `text` in future storage models, regardless of source dataset type (`nit_entidad` is `number` in `jbjy-vk9h`, `text` in `p6dx-8zbt`). Never coerce NITs to numeric types.
2. **`urlproceso` special case:** metadata type and payload representation differ; the payload carries a nested object structure. Ingestion rules must account for this structure.
3. **Personal/sensitive data:** fields flagged HUMAN-6 (bank-account) and HUMAN-9 (personal data) require a dedicated ADR before exposure — roadmap backlog item; pending.
4. **Release construction and observed-date semantics (human clarification, 2026-10-21):** releases
   are created only when the substantive-state hash of a source row changes (storage architecture
   baseline, §8). `release_date` records when the state change was **observed at ingestion** — not
   the real-world date on which the underlying change occurred (SECOP exposes current-state
   snapshots; the real-world change date is unknowable between ingestion runs). This distinction
   is binding on all future documentation, mapping specifications, and API naming; the
   citizen-facing Spanish explanation ("la fecha indica cuándo observamos el cambio, no
   necesariamente cuándo ocurrió") is pending in `docs/citizen/` (PR-08).
