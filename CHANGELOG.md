# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html)
once a public release exists.

## [Unreleased]

### Added (accepted, not yet committed)

- **docs:** HUMAN-4 partially validated — `sector` validated and applied (human Q1–Q5 rulings, 2026-10-29, Group 3 sitting row #12 — second row of Group 3): semantics **APPROVED** as the government-sector classification of the publishing entity; all 26 published values accepted **verbatim as published** (exact spellings, including accents); the four lowercase values (`defensa`, `deportes`, `agricultura`, `interior` — 9.52% of the evidence window combined) **APPROVED** as distinct published spellings that must **NOT be normalized at the data layer** — any future display-layer normalization is a separate presentation concern, not a data concern; both sentinel-form categories **APPROVED** for verbatim preservation as distinct published categories — `No Definido` (19 rows, 0.0003%) and the compound `No aplica/No pertenece` (862,935 rows, 15.2323%) — never NULL-converted, never split, never merged, never normalized, never excluded from aggregations (V-6/`destino_gasto` precedent); codelist status ruled **CLOSED-AS-OBSERVED** — a stable 26-value published codelist with **no future exhaustiveness guarantee assumed**: any future value must be logged, must trigger dictionary review (dictionary first, code second — PR-06), and must never be normalized automatically (V-12 conditions precedent); evidence found sufficient (Q5). Evidence basis (`.pi/artifacts/dq-report-2026-10-29-group3-row2-sector.md`, run 2026-10-29, 5,665,176-row window; pre-flight NO DRIFT on both Tier A datasets): complete distinct-value census — exactly 26 values, `census_truncated: false`, group counts summing exactly to the row total (top: `Servicio Público` 1,429,174 / 25.2274%; `No aplica/No pertenece` 862,935 / 15.2323%; `Salud y Protección Social` 818,149 / 14.4417%); null/empty/whitespace rate 0% (Level 1 basis preserved); zero within-value variants — case-sensitive distinct (26) equals case-insensitive distinct (26), every casing/accent lookalike probe 0; cross-value casing inconsistency documented (four lowercase values, zero capitalized lookalikes); cross-window stability — the identical 26-value set across the 2026-10-21 Batch 1 window and the 2026-10-29 window, the +20,235 new rows introducing no new value; the `No Definido` sentinel count frozen at 19 across both windows. Data dictionary: §6 V-13 note added and accepted; §2 row 7 updated to validated wording + partially-validated flag; §5 limitation-register bullet added (codelist-boundedness — an observation over published data, not a SECOP guarantee; cross-value casing inconsistency documented, never normalized); §10 evidence-log V-13 row added; [HUMAN-4] checklist row updated (`sector` closed; `rama`, `entidad_centralizada`, `ordenentidad` open) and flag totals restated. Decision log: Field-Validation Record FV-9 added (Accepted · 2026-10-29). Runbook §9 entry added, including the §5.1 two-file-rule verification (semantics-only change, `SecopPreflight.php` baseline `"sector" => "text"` verified unchanged; no baseline edit required) and the Decision A recurrence note for the application session's self-reported bash repository reads (accepted process-issue class — read-only, no data-plane impact, no secret access; no further action required). Group 3 continues only on the human's instruction — row #13 (`rama`) is next and is HELD pending the human's review of this row #12 application. No commits (P-03).

- **docs:** HUMAN-4 partially validated — `orden` validated and applied (human Q1–Q5 rulings, 2026-10-29, Group 3 sitting row #11 — first row of Group 3): semantics **APPROVED** as the administrative-order classification of the state entity that publishes the contract; accepted categories verbatim as published (exact spellings, including accents): `Nacional`, `Territorial`, `Corporación Autónoma`, plus the published sentinel `No Definido` (19 rows, 0.0003%), which is **APPROVED** for verbatim preservation as a distinct published category — never NULL-converted, never merged, never excluded from aggregations (V-6/`destino_gasto` precedent); codelist status ruled **CLOSED-AS-OBSERVED** — bounded as observed with **no future exhaustiveness guarantee assumed**: any future value must be logged, any future value must trigger dictionary review (dictionary first, code second — PR-06), and new values must never be normalized automatically; the `orden` ↔ `ordenentidad` vocabulary comparison **DEFERRED** to `ordenentidad`'s validation (Q4); evidence found sufficient (Q5). Evidence basis (`.pi/artifacts/dq-report-2026-10-29-group3-row1-orden.md`, run 2026-10-29, 5,665,176-row window; pre-flight NO DRIFT on both Tier A datasets): complete distinct-value census — exactly 4 values, `census_truncated: false`, group counts summing exactly to the row total (`Territorial` 3,702,864 / 65.3619%; `Nacional` 1,881,881 / 33.2184%; `Corporación Autónoma` 80,412 / 1.4194%; `No Definido` 19 / 0.0003%); null/empty/whitespace rate 0% (Level 1 basis preserved); zero casing/diacritic/whitespace variants (every variant probe 0 — a consistently cased, consistently accented vocabulary, unlike sibling field `sector`); cross-window stability — the identical 4-value set across the 2026-10-21 Batch 1 window and the 2026-10-29 window, the +20,235 new rows partitioning exactly (16,277 + 3,691 + 267 + 0) and none introducing a new value; the `No Definido` sentinel count frozen at 19 across both windows. Data dictionary: §6 V-12 note added and accepted; §2 row 6 updated to validated wording + partially-validated flag; §5 limitation-register bullet added (codelist-boundedness — an observation over published data, not a SECOP guarantee); §10 evidence-log V-12 row added; [HUMAN-4] checklist row updated to partially validated (`orden` closed; `sector`, `rama`, `entidad_centralizada`, `ordenentidad` open) and flag totals restated (HUMAN 10 — 6 open; HUMAN-4 now partially validated). Decision log: Field-Validation Record FV-8 added (Accepted · 2026-10-29). Runbook §9 entry added, including the §5.1 two-file-rule verification (semantics-only change, `SecopPreflight.php` baseline `"orden" => "text"` verified unchanged; no baseline edit required) and the Decision A recurrence note for the evidence session's self-reported `sail ps` + `grep` Command 0 deviation (accepted process-issue class — read-only, no data-plane impact, no secret access; no further action required). Group 3 continues only on the human's instruction — row #12 (`sector`) is next and is HELD pending the human's review of this row #11 package. No commits (P-03).

- **docs:** Group 2 COMPLETE — `saldo_vigencia` validated and HUMAN-2 fully closed (human Q1–Q4 rulings, 2026-10-29, Group 2 fiscal sitting row #4): semantics accepted as "Current balance for the vigencia of the CDP assigned to the process and the contract" (COP, per the official ES description); the `saldo_cdp` zero rule extended — `0` is a **substantive published state** whose precise operational meaning remains **UNDECIDABLE from the available SECOP evidence** (the ruling combines the substantive-state reading with the undecidability limitation; `0` is preserved as published and never interpreted as no vigencia exists, fully consumed vigencia, not applicable, or any other specific operational state; PR-08 limitation; zero prevalence 5,573,915 rows = 98.3891% in the 5,665,176-row evidence window); published anomaly classes registered and preserved verbatim — the extreme maximum `230,400,000,000,000` (4 rows, ≈16.895% of the field's aggregate sum), values at or above 10¹³, degenerate literals `1` (3 rows) / `2` (5 rows), and the near-duplicate truncation-artifacts observation — never corrected, normalized, filtered, or silently excluded; relationship with `saldo_cdp`: **no formal relationship rule adopted** (Q3, option ii) — correlations effectively zero on all subsets, no validated additive identity (pair sum matches `valor_del_contrato` on 26.93% of rows, mostly via x alone), containment refuted (y > x on 74.34% of both-non-zero rows) — recorded as evidence observations only. Data dictionary: §6 V-11 note added and accepted; §2 row 44 updated to validated wording + fully-validated flag; §5 limitation-register bullets added (zero-meaning undecidability; anomaly classes); §10 evidence-log V-11 row added; [HUMAN-2] checklist row closed and flag totals restated (HUMAN 10 — 6 open). Decision log: Field-Validation Record FV-7 added (Accepted · 2026-10-29) plus the **Group 2 closure note** — Group 2 (HUMAN-1 + HUMAN-2, checklist rows 1–4) COMPLETE: four fields validated across V-8/V-9/V-10/V-11 and FV-4/FV-5/FV-6/FV-7, with the standing binding rules (substantive zero where ruled, undecidable zero where ruled, anomaly preservation, no-identity/no-rule relationship dispositions); Group 3 (HUMAN-4) begins only on the human's instruction. Runbook §9 entry added, including the §5.1 two-file-rule verification (semantics-only change, `SecopPreflight.php` baseline verified unchanged; no baseline edit required). Evidence package at `.pi/artifacts/dq-report-2026-10-29-group2-row4-saldo-vigencia.md`.

- **docs:** HUMAN-2 partially closed — `saldo_cdp` validated (human Q1/Q2/Q3 rulings, 2026-10-29, Group 2 fiscal sitting row #3): semantics accepted as "Remaining balance of the CDP assigned to the process and the contract"; the meaning of a value of `0` ruled **UNDECIDABLE from SECOP evidence alone** — `0` is preserved as published and must never be interpreted as "no CDP exists", "fully committed", or any other specific operational state (PR-08 limitation; zero prevalence 2,139,396 rows = 37.7635% in the 5,665,176-row evidence window); published anomaly classes registered and preserved verbatim — the extreme maximum `111111111111111111` (1 row, ≈52.44% of the field's aggregate sum), values ≥ 10¹⁵ (10 rows), and degenerate literals `1` (253 rows) / `2` (8 rows) — never corrected, normalized, filtered, or silently excluded; the field remains Level 1 (null rate 0%, no negatives); relationship analysis with `saldo_vigencia` DEFERRED to row #4. Data dictionary: §6 V-10 note added and accepted; §2 row 43 updated to validated wording + partial-validation flag; §5 limitation-register bullets added (zero-semantics ambiguity; anomaly classes); §10 evidence-log V-10 row added; [HUMAN-2] checklist row and flag totals restated (HUMAN-2 partially validated — `saldo_vigencia` still open; HUMAN 10 — 7 open). Decision log: Field-Validation Record FV-6 added (Accepted · 2026-10-29). Runbook §9 entry added, including the §5.1 two-file-rule verification (semantics-only change, `SecopPreflight.php` baseline verified unchanged; no baseline edit required). Evidence package at `.pi/artifacts/dq-report-2026-10-29-group2-row3-saldo-cdp.md`.

- **docs:** HUMAN-1 fully closed — `valor_pendiente_de` final validation (human Q3
  final decision, 2026-10-29, Group 2 fiscal sitting row #2): the complement
  relationship between `valor_amortizado` and `valor_pendiente_de` is recorded as
  **INDETERMINATE** — the relationship evidence package (identity tests vs
  `valor_del_contrato` and `valor_pagado`, plus the exploratory `valor_facturado`
  candidate; correlations; sign grid; half-value pattern) does not support any
  validated complement rule, so **no complement rule is adopted and no arithmetic
  identity is assumed**; code, mappings, indicators, and documentation must treat
  the relationship as unresolved unless new evidence emerges. The 0-semantics is
  confirmed: `0` is a substantive published state, never missing data, never
  NULL-converted, with the approximately 99.9% zero-dominance documented as a
  PR-08 limitation. Data dictionary: §6 V-9 note added and accepted; §2 row 41
  updated to final validated wording + flag; §10 evidence-log V-9 row added;
  [HUMAN-1] checklist row and flag totals restated (HUMAN-1 fully closed for both
  its fields: `valor_amortizado` V-8/FV-4 and `valor_pendiente_de` V-9/FV-5).
  Decision log: Field-Validation Record FV-5 added (Accepted · 2026-10-29).
  Runbook §9 entry added, including the §5.1 two-file-rule verification
  (semantics-only change, `SecopPreflight.php` baseline verified unchanged; no
  baseline edit required). Evidence package at
  `.pi/artifacts/dq-report-2026-10-29-group2-row2-relationship-evidence.md`.

- **docs:** HUMAN-1 further partial closure — `valor_pendiente_de` semantics +
  negative-values disposition decided (human Q1/Q2 rulings, 2026-10-29, Group 2
  fiscal sitting row #2): semantics CONFIRMED as the value pending amortization to
  date (COP); truncated-field-name ambiguity RESOLVED; negative values preserved
  verbatim — never corrected, clamped, or imputed — and ADDITIONALLY recorded as a
  PR-08 source-data-quality limitation (legitimacy vs source error UNDETERMINED at
  this stage). Data dictionary: §2 row 41 updated to partially-validated wording and
  flag; §5 limitation-register bullet added (negative values); [HUMAN-1] checklist
  row and flag totals restated. NOT applied (drafts only, pending human acceptance):
  V-9 note and FV-5 record — the `valor_amortizado` ↔ `valor_pendiente_de`
  complement relationship remains OPEN; read-only relationship evidence package at
  `.pi/artifacts/dq-report-2026-10-29-group2-row2-relationship-evidence.md`
  (identity tests against `valor_del_contrato` and `valor_pagado` do not
  empirically support the complement reading; decision reserved to the human).
  Runbook §9 entry added, including the §5.1 two-file-rule verification
  (semantics-only change, no baseline edit required).

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
- **docs:** HUMAN-1 partially closed — `valor_amortizado` validated (V-8 + FV-4,
  human-validated 2026-10-29, Group 2 fiscal sitting row #1): confirmed as the
  cumulative amortized value of the contract to date (COP); `0` is a substantive
  published state ("nothing amortized to date") — never interpreted as missing
  data, never converted to NULL; published anomalies preserved and logged
  (PR-06); the ~99.97% zero-dominance recorded as a PR-08 limitation note and
  the field remains Level 1. Data dictionary: §2 row 40 updated to validated
  wording/flag; §6 header retitled V-1…V-8 with the V-8 note; §10 evidence-log
  row added; flag-checklist HUMAN-1 row marked partially validated and flag
  totals restated; provenance-header status line updated to V-1…V-8 (completes
  the previously approved stale-line fix, which had pending V-7 wording).
  Decision log: Field-Validation Record FV-4 (after FV-3). Runbook §9: process
  note recording the accepted disposition of the `sail up -d` allowlist
  deviation (documented process issue, no data-plane impact, no further
  action — human ruling 2026-10-29) and the §5.1 two-file-rule verification
  (`SecopPreflight.php` baseline unchanged — fieldName and dataTypeName
  `number` intact for row 40; semantics-only change, no edit required).
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
- **docs:** HUMAN-3 closed (V-7 + FV-3 + AMBIG-8 resolution, human-validated 2026-10-21)
  — six component-origin fields (`presupuesto_general_de_la_nacion_pgn`,
  `sistema_general_de_participaciones`, `sistema_general_de_regal_as`,
  `recursos_de_credito`, `recursos_propios`,
  `recursos_propios_alcald_as_gobernaciones_y_resguardos_ind_genas_`) validated as
  the additive decomposition of `valor_del_contrato` (components in COP; 0 = no
  contribution recorded from that source; expected Σ components = contract value;
  additivity deviations are published anomalies — logged and preserved, never
  corrected, estimated, normalized, or silently excluded). Data dictionary: §6
  retitled V-1…V-7 with the V-7 note (V-5 interplay unchanged); §2 rows 56–61
  updated to validated wording/flags (OCDS mapping status untouched — mapping is
  a separate gate); §10 evidence-log row; flag-checklist HUMAN-3 row validated,
  AMBIG-8 row resolved, flag totals restated (AMBIG 8 open/1 resolved; HUMAN 9
  open/1 validated). Decision log: Field-Validation Record FV-3 (after FV-2).
  **AMBIG-8 resolved — name governs:** `recursos_propios_alcald_as_…` is
  territorial-entity/Indigenous-resguardo own resources specifically;
  `recursos_propios` remains the generic own-resources component; the two are
  distinct, non-redundant components, and future mappings must preserve that
  distinction. Batch 1 evidence package v5 annotation revision (application only,
  no new evidence window): §10 checklist rows #7–#10/#21/#22 DECIDED & APPLIED,
  §3.6/§3.8 resolved-answer annotations, §12 Group 1 complete.
  `SecopPreflight.php` baselines verified — all six fields present with unchanged
  names/types (`number`); semantics-only change, no baseline edit required
  (runbook §5.1). Working tree only — no commit (P-03).
