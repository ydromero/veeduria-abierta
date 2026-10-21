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

## Pending
- Citizen-facing wording for pending terms, drafted under PR-08 sourcing rules
  when the first citizen-facing surface exists.
