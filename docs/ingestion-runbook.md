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
