# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html)
once a public release exists.

## [Unreleased]

### Added (accepted, not yet committed)

- **docs:** Founder's Intent document adopted (`docs/founders-intent.md`, ADR-022) —
  first-person testimony of origin, motivation, convictions, founder assumptions,
  feared erosions, and aspirations. Informative layer (level 7): restates nothing as
  normative and yields to all higher layers via an explicit supremacy clause
  (constitution wins over founder testimony). Preserves the founder's emphases:
  capability over outcomes, evidence over accusation, methods as much as data,
  warning against mission capture (including founder capture), integrity over
  attention. English canonical; translation deferred (human decision). Docs-map
  routing row added.
- **agents:** Data Ingestor charter v1.0 added (`.pi/agents/data-ingestor.md`,
  ADR-021 Accepted) — first technical specialist agent, owning the Phase 1 data
  plane: SODA client, cursor pagination (ADR-002), queue ingestion (ADR-005),
  release construction and projection (ADR-018/019), ADR-020 register
  implementation, ingestion tests, and the operational ingestion runbook (§5–§9).
  Includes the human-mandated execution traceability requirement (command, exit
  status, affected files, side effects for every allowlisted bash command) and
  Data Quality Reporting (completeness, anomaly, coverage, schema-change
  detection). Scoped bash allowlist; migrations and commits remain
  human-executed (P-03). Runbook ownership supersession recorded: operational
  sections owned by the Ingestor; Project Architect retains documentation
  coordination, cross-reference management, and docs-map routing (charter
  clarification applied). Agent becomes active upon human `/reload`; acceptance
  and activation remain separate actions.
### Changed (queued for the next documentation commit — accepted, not yet committed)

- **docs:** Master Plan rewritten as Version 2 (revision 0.2) — the product-scope layer.
  Mission/vision, principles, ADR content, and data definitions now delegated by
  citation to their canonical sources (`docs/vision.md`, `docs/principles.md`,
  `docs/decision-log.md`, `docs/data-dictionary.md`); adds phase exit criteria,
  ADR register, and discovery-oriented success measures (PR-13).
- **docs:** Long-term vision document adopted (`docs/vision.md`, ADR-017) —
  three-pillar mission at the level of public action, per-domain expansion gate,
  open-network governance. Renamed from `docs/vision-draft.md`.
- **docs:** ADR-018 recorded — OCDS storage strategy: Hybrid (releases canonical +
  derived current-state projection). ADR numbering reconciled: OCDS version pin →
  ADR-019 (draft), personal/sensitive data handling → ADR-020 (draft); P-05 = storage
  strategy (resolved), narrative-tone item = P-06.
- **docs:** Data dictionary, conceptual model, and roadmap cross-references updated
  to the reconciled numbering; decision-log ADR-004/006/010 headings repaired.
