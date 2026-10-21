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
4. Missing critical data: log, report, stop — never a placeholder (PR-06).
5. Provenance per record: source dataset, query/pagination window, timestamp.

## 6. Error handling and reporting
- Error classes, retry policy, escalation to the human. *(To be filled during implementation.)*

## 7. Verification and quality checks
- Post-ingestion reconciliation. *(To be filled during implementation.)*

## 8. Coverage gaps and limitations
- Pointer to `docs/data-dictionary.md` §5 (PR-08).

## 9. Change log
- Append-only; every procedure change dated.

## 10. Storage and ingestion decisions (human-approved 2026-10-21)
1. **NIT normalization:** all NIT values normalized as `text` in future storage models, regardless of source dataset type (`nit_entidad` is `number` in `jbjy-vk9h`, `text` in `p6dx-8zbt`). Never coerce NITs to numeric types.
2. **`urlproceso` special case:** metadata type and payload representation differ; the payload carries a nested object structure. Ingestion rules must account for this structure.
3. **Personal/sensitive data:** fields flagged HUMAN-6 (bank-account) and HUMAN-9 (personal data) require a dedicated ADR before exposure — roadmap backlog item; pending.
