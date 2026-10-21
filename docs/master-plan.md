# Master Project — Open Oversight (Veeduría Abierta)

*A living document. Modified only by humans. Last revised: 2026-10-19.*

## 1. What are we building?
An open-source civic platform for the auditing, exploration, and forensic analysis of Colombian public procurement (starting with SECOP II). It distinguishes itself by standardizing information according to the global OCDS (Open Contracting Data Standard) model, maintaining the immutability of the raw official data, and—in advanced phases—applying heuristics and predictive models whose outputs are presented strictly as *risk indicators*, never as legal judgments.

## 2. Why does it exist?
To democratize technical scrutiny in the face of the high complexity, fragmentation, and opacity of the public procurement market. The underlying goal is educational: to make data accessible and verifiable for everyone, teaching the **method** of citizen verification rather than merely displaying results.
Long-term vision: to strengthen Colombian civic identity through informed participation. Guiding phrase: *"a view of Colombia that no one else shows you."*

## 3. Who uses it?
- **The average citizen:** Seeks to understand how tax money is spent in their municipality, in a simple and educational way.
- **Civic monitors, journalists, and researchers:** Require in-depth forensic tools (complex filters, network graphs, immutable timelines of OCDS *Releases*) to support their investigations.
- **Civic developers:** As this is free/open-source software (MIT-licensed), they can leverage the platform’s architecture and data model to build new civic tools.

## Principles (non-negotiable)
1. **Strengthen, don’t reinvent or confront.** Before building anything, we examine what already exists (e.g., BuscaSECOP, demoanalitica/anticorrupcion.co, Congreso Visible, MOE) and seek to act as an ally.
2. **Official data must remain immutable and distinct** from AI-generated interpretations; the latter must always be labeled and auditable.
3. **Indicators, not accusations.** We never assert the guilt of any individual or company.
4. **Transparency regarding limitations.** Every screen clearly states which data sources are used and their respective level of reliability.
5. **No inauthentic behavior.** No astroturfing or coordinated account activity.
6. **Public, open-source software** (MIT license), ensuring it remains trustworthy and open to contributions.
7. **Designed to outlast any individual contributor** and remain independent of the current administration.
8. Machine learning is used exclusively for investigative discovery—never to maximize user engagement or stir controversy.

## Data Reliability Levels
- **Level 1 (Reliable via SECOP API):** Contract value, dates, status, entity, supplier, and procurement method.
- **Level 2 (Available but with gaps):** Completion and settlement dates, BPIN field (verify completeness before use). 
- **Level 3 (not in SECOP):** physical execution (SPI/DNP), budget details (SIIF), ultimate beneficial owners (RUB/DIAN).

## 4. What is the scope?
The current scope is limited to the passive ingestion, cleaning, normalization, and exposure of SECOP II data. Algorithmic predictive detection and social features are strictly excluded from Phase 1.

**Phase 1 — SECOP II (ongoing).** Level 1 data only.
- Ingestion from the SODA API (ADR-001/002/003) into staging tables containing immutable raw data.
- Normalization into a custom model compatible with OCDS (to be defined in `diccionario-datos.md`).
- Contract explorer with pagination and a dashboard featuring basic indicators.
- Out of scope for Phase 1: red flags (P-01), user accounts, social layer, and congressional modules.

**Subsequent phases (no date set; subject to future decision).**
- **Red flag engine (committed; phase to be determined, P-01).** Risk indicators documented individually (formula, OCDS paths, thresholds, limits, and false positives)—presented strictly as indicators, never as accusations (ADR-008). Requires human validation for each rule.
- Graph layer for entities, contracts, contractors, and consortia.
- Cross-referencing with SIGEP II, Cuentas Claras, and congressional voting records.
- A reasoned, searchable record of congressional debates (addressing a gap not covered by *Congreso Visible* or the MOE).
- Citizen social layer, with one account per person (identity verification via national ID: challenging, yet open).
- Alert module for Indigenous territories: **will not be developed** without first resolving issues regarding data sovereignty, ILO Convention 169 / Law 21 of 1991, and security risks.

## Stack
See ADR-004. Laravel 13 + Livewire 4 + Bootstrap 5 + PostgreSQL 18, all running in Docker via Sail. No custom JavaScript, except for what is strictly necessary.

## Who does what
- **Human (always):** vision, priorities, decision-making, validation of legal and data interpretations, public-facing communication, partner relations, approval of ADRs (Architecture Decision Records), secret management, and merging code.
- **Agents (propose and execute reversible, verifiable tasks):** research, drafting, coding, testing, reviewing, and document maintenance.
- Detailed role and agent definitions are located in `.pi/agents/`.

## Living documents
| Document | Updated when... |
|---|---|
| `proyecto-maestro.md` | the vision or scope changes (human) |
| `registro-decisiones.md` | a significant decision is made |
| `AGENTS.md` | a rule or convention changes (human) |
| `diccionario-datos.md` (to be created) | new information about a field is discovered or a dataset is added |
| `runbook-ingesta.md` (to be created) | the ingestion process changes or a new error occurs |
| `CHANGELOG.md` (to be created) | every change is integrated |

A complete map of which changes affect which documents can be found in `.pi/memory/docs-map.md`.

## References
Open Contracting Partnership · OCDS standard · demoanalitica (CC BY 4.0) · BuscaSECOP · Decidim · MySociety · Law 1712 of 2014.