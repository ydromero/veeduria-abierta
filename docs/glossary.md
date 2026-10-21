# Glossary — Veeduría Abierta

**Status:** Skeleton v0.1
**Language rule (ADR-013):** Internal definitions in English. Citizen-facing
definitions in Spanish, plain language, stating the source and its limitations
(PR-08). Colombian legal instrument names, institution names, and dataset names
are never translated.

## Terms
| Term | Internal definition (EN) | Citizen-facing definition (ES) |
|---|---|---|
| SECOP II | Colombia's public procurement system; this project's sole source of truth (ADR-001) | Sistema Electrónico para la Contratación Pública — fuente oficial de los datos que muestra esta plataforma |
| SODA | Socrata Open Data API; access mechanism for the SECOP II datasets (ADR-001/003) | *(pending)* |
| OCDS | Open Contracting Data Standard; normalization target per master-plan | Estándar internacional para publicar datos de contratación |
| Veeduría | Organized citizen scrutiny of public contracting | Control ciudadano organizado sobre la contratación pública |
| Risk indicator | A computed signal for review, never an accusation (ADR-008, PR-09) | Señal que sugiere revisar un contrato con más atención; **no es una acusación** |
| Reliability level | Data provenance tier (1/2/3) per master-plan | Qué tan directo es el origen de un dato |
| Raw data | Immutable, unmodified official data stored separately (ADR-007, PR-05) | Datos originales tal como los publica la fuente, sin modificaciones |
| Cursor | Pagination key; bulk loads never use offset (ADR-002) | *(pending)* |
| SECOP Integrado (SECOPI+SECOPII) | The union dataset `rpmr-utcd` combining legacy SECOP I and SECOP II records on datos.gov.co. Overlaps SECOP II contract data already covered by `jbjy-vk9h` with a reduced field model (22 columns vs. 95). Tier B — documented but deferred (ADR-001a); ingesting it together with `jbjy-vk9h` would double-count SECOP II contracts | Unión de los datos de la SECOP I y la SECOP II en un solo conjunto. No se usa en esta fase: duplicaría contratos que ya mostramos con más detalle |
| Filter view | A Socrata dataset derived by filtering another dataset; appears as a separate catalog entry but shares the parent dataset's timestamps and field model. Excluded from the primary-dataset inventory (deduplicated per ADR-001a) | Vista derivada de otro conjunto de datos; no es un conjunto nuevo, sino un filtro del mismo origen |
| Tier A dataset | Primary-truth dataset approved for Phase 1 ingestion: `jbjy-vk9h` (contract facts) and `p6dx-8zbt` (process lifecycle). The authoritative Phase 1 dataset definition (ADR-001a) | Conjuntos de datos oficiales que esta plataforma usa como fuente principal en su primera fase |
| Tier B dataset | Dataset documented and verified (row counts) but deferred from Phase 1 ingestion: `rpmr-utcd` (double-counting risk vs. `jbjy-vk9h`; SECOP I legacy) and `u8cx-r425` (amendments, separate ingestion concern). Re-evaluation requires a human decision and a documented de-duplication strategy (ADR-001a) | Conjunto de datos conocido y documentado, pero aún no integrado a la plataforma |
| Tier C dataset | Dataset inventoried and verified but out of Phase 1 scope (12 primary datasets plus all SECOP I legacy datasets), per ADR-001a | Conjunto de datos que existe en la fuente oficial pero está fuera del alcance de esta fase |
| App Token | Socrata application token. Operative rule (approved 2026-10-21, exact wording): **"App Token is optional for discovery activities and strongly recommended for bulk-ingestion workloads."** Discovery queries succeeded anonymously (2026-10-21); ADR-003's credential decisions (Key ID + Key Secret in the environment file only, per ADR-009/PR-14) are unchanged | Regla de acceso: para explorar los datos no se requiere token; para descargas masivas se recomienda usar uno para no saturar la fuente |

## Pending
- Citizen-facing wording for pending terms, drafted under PR-08 sourcing rules
  when the first citizen-facing surface exists.
