# SECOP II Data Dictionary — Veeduría Abierta

**Status:** Skeleton v0.1 — no fields documented yet
**Hard gate (PR-06):** No SECOP field may be referenced in code, migrations, or
documentation before it is verified against the real API metadata and recorded
here. Do not invent field names, endpoints, or semantics.

## 1. Datasets (per ADR-001)
| Dataset ID | Content | Endpoint | Verification status |
|---|---|---|---|
| `jbjy-vk9h` | To be confirmed against API metadata | — | Pending |
| `rpmr-utcd` | To be confirmed against API metadata | — | Pending |

## 2. Fields — dataset `jbjy-vk9h`
*(Empty by design. Populate only from verified SODA API metadata.)*
| Field | Type | Description | Nullable | Level | Verified on |
|---|---|---|---|---|---|

## 3. Fields — dataset `rpmr-utcd`
*(Empty by design.)*
| Field | Type | Description | Nullable | Level | Verified on |
|---|---|---|---|---|---|

## 4. Verification procedure
1. Query the dataset's SODA metadata endpoint (procedure to be documented in
   `docs/ingestion-runbook.md` once that file exists).
2. Record field, type, nullability, and the verification date.
3. A human validates the entries before they are used in code (PR-06, PR-15).

## 5. Known coverage gaps (PR-08)
*(Empty. Filled as gaps are discovered during verification.)*
