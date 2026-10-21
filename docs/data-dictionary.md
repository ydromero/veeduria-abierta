## Provenance header
- **Metadata retrieved:** 2026-10-21, via the Socrata metadata endpoint `/api/views/{id}.json` (field names, `dataTypeName`, official descriptions) and `$limit=1` sample queries (sample values) on `https://www.datos.gov.co`.
- **Verification method:** every field row below was transcribed directly from the verified metadata artifact `.pi/artifacts/step2-verified-metadata.md`. No field, type, or description was invented; `docs/research/secop-platform-research.md` was treated as candidate reference only, never as truth (ADR-12, human instruction). Sample values come from the single-row payloads in that artifact.
- **Language:** descriptions are English translations of the official API descriptions (ADR-13). **The original Spanish description is the authoritative one**; Spanish proper nouns and SODA field values as published are never translated.
- **OCDS mappings are CANDIDATES ONLY**, clearly marked, awaiting the P-05 storage ADR (Releases vs. Records vs. hybrid — Q-1 constraint: not decided yet). "Unknown" means no plausible mapping was proposed.
- **Human validation pending — no field is normative until validated (PR-06).**
**Status:** Version 1.0 — consolidated with conceptual validation findings V-1…V-4 (accepted 2026-10-21). This is the first fully validated institutional knowledge artifact for Phase 1.

## Reliability levels (per master-plan; PR-07)
- **Level 1** — API-published field with a clear official description and no observed population gap. Proposed for all Tier A fields by default.
- **Level 2** — nullability or population gaps suspected, from the sample payload or the description. Justification is given per row. SODA omits null fields in JSON responses: **absence from the sample row is evidence of practical nullability**, not proof.
- **Sentinel values:** the platform publishes literal strings such as `No Definido`, `No definido`, `Sin Descripcion`, `No Adjudicado`, `No aplica` instead of nulls (PR-06: these are published values, never placeholders substituted by us; flagged per row).

## Flag reference
- **[UNKNOWN-n]** — field with unknown meaning: no API description, or semantics unclear from the description.
- **[AMBIG-n]** — ambiguous: a description exists but the meaning is unclear, the field name contradicts the description, or the sample value contradicts the description.
- **[HUMAN-n]** — requires human validation: business/legal meaning needs domain confirmation before code relies on it.
- **[GAP-n]** — population gap suspected from the sample (missing row or sentinel value).

---

## 1. Datasets (per ADR-001 as amended — see draft-adr-001-amendment.md)
Verified inventory 2026-10-21 (952 catalog results → 16 primary datasets after dedup; all verified read-only).

### Tier A — ingest in Phase 1 (primary truth)
| Dataset ID | Content | Rows | Columns | Endpoint | Verification status |
|---|---|---|---|---|---|
| `jbjy-vk9h` | SECOP II - Contratos Electrónicos (contract facts) | 5,644,941 | 95 | `https://www.datos.gov.co/resource/jbjy-vk9h.json` | Verified 2026-10-21 (metadata + sample) |
| `p6dx-8zbt` | SECOP II - Procesos de Contratación (pre-award process lifecycle) | 9,349,940 | 59 | `https://www.datos.gov.co/resource/p6dx-8zbt.json` | Verified 2026-10-21 (metadata + sample) |

### Tier B — documented but deferred (NOT ingested in Phase 1)
| Dataset ID | Content | Rows | Columns | Verification status |
|---|---|---|---|---|
| `rpmr-utcd` | SECOP Integrado (SECOPI + SECOPII union) | 22,850,110 | 22 | Row count verified 2026-10-21; fields NOT documented (deferred; double-counting risk vs. `jbjy-vk9h` — see ADR-001a) |
| `u8cx-r425` | SECOP II - Modificaciones a contratos (amendments) | 30,144,236 | — | Row count verified 2026-10-21; fields NOT documented (deferred; separate ingestion concern) |

### Tier C — noted, out of Phase 1 scope
| Dataset ID | Content | Rows | Verification status |
|---|---|---|---|
| `ibyt-yi2f` | SECOP II - Facturas (invoices) | 22,906,572 | Count verified 2026-10-21; fields not documented (out of scope) |
| `gjp9-cutm` | SECOP II - Garantías (guarantees) | 7,118,182 | Count verified 2026-10-21; out of scope |
| `cb9c-h8sn` | SECOP II - Adiciones (contract additions) | 8,368,566 | Count verified 2026-10-21; out of scope |
| `mfmm-jqmq` | SECOP II - Ejecución Contratos (execution) | 4,840,473 | Count verified 2026-10-21; out of scope |
| `wwhe-4sq8` | SECOP II - Ubicaciones Adicionales (geodata) | 6,158,353 | Count verified 2026-10-21; out of scope |
| `gra4-pcp2` | SECOP II - Ubicaciones ejecución contratos (geodata) | 6,413,958 | Count verified 2026-10-21; out of scope |
| `skc9-met7` | SECOP II - Compromisos Presupuestales (budget commitments) | 5,866,580 | Count verified 2026-10-21; out of scope |
| `cwhv-7fnp` | SECOP II - Rubros Presupuestales (budget lines) | 5,891,594 | Count verified 2026-10-21; out of scope |
| `hgi6-6wh3` | Proponentes por Proceso SECOP II (bidders per process) | 2,345,797 | Count verified 2026-10-21; out of scope |
| `qmzu-gj57` | SECOP II - Proveedores Registrados (supplier registry) | 1,625,078 | Count verified 2026-10-21; out of scope |
| `u99c-7mfm` | SECOP II - Suspensiones de Contratos (suspensions) | 882,431 | Count verified 2026-10-21; out of scope |
| `b6m4-qgqv` | SECOP II - PAA - Encabezado (annual acquisition plans) | 53,101 | Count verified 2026-10-21; out of scope |

All SECOP I legacy datasets (e.g., `f789-7hwg`, `4n4q-k399`) are out of scope per ADR-001's original intent.

---

## 2. Fields — dataset `jbjy-vk9h` (SECOP II - Contratos Electrónicos, 95 fields)
Source dataset: `jbjy-vk9h`. Types as published by the API (`dataTypeName`). "OCDS mapping" values are candidates pending the P-05 ADR.

| # | Field (API fieldName) | Type | Description (EN; ES original authoritative) | Sample value (2026-10-21 payload) | Proposed business meaning *(interpretation)* | OCDS mapping candidate | Level | Flags |
|---|---|---|---|---|---|---|---|---|
| 1 | `nombre_entidad` | text | Name of the state entity that publishes the contract | `AGENCIA LOGISTICA DE LAS FUERZAS MILITARES` | Official name of the buying entity | candidate — `buyer/name` — pending P-05 ADR | 1 | — |
| 2 | `nit_entidad` | number | NIT of the state entity that publishes the contract | `899999162` | Colombian tax ID (NIT) of the buying entity | candidate — `buyer/identifier/id` — pending P-05 ADR | 1 | — |
| 3 | `departamento` | text | Department in which the state entity that publishes the contract is registered | `Distrito Capital de Bogotá` | Department of the entity's registration | candidate — `buyer/address/region` — pending P-05 ADR | 1 | — |
| 4 | `ciudad` | text | City in which the state entity that publishes the contract is registered | `Bogotá` | City of the entity's registration | candidate — `buyer/address/locality` — pending P-05 ADR | 1 | — |
| 5 | `localizaci_n` | text | Full location of the state entity that publishes the contract | `Colombia, Bogotá,  Bogotá` | Composite geolocation string (country, city…) | candidate — `buyer/address/streetAddress` — pending P-05 ADR | 1 | — |
| 6 | `orden` | text | Order (entidad) of the state entity that publishes the contract *(SODA value as published)* | `Nacional` | Administrative order of government (Nacional / Territorial…) | unknown | 1 | [HUMAN-4] |
| 7 | `sector` | text | Sector (entidad) of the state entity that publishes the contract | `defensa` | Government sector classification of the entity | unknown | 1 | [HUMAN-4] |
| 8 | `rama` | text | Branch of the state (of the entity that publishes the contract) | `Ejecutivo` | Branch of state power (Ejecutivo, Legislativo…) | unknown | 1 | [HUMAN-4] |
| 9 | `entidad_centralizada` | text | Defines whether the entity is decentralized or centralized | `Descentralizada` | Centralization status of the entity | unknown | 1 | [HUMAN-4] |
| 10 | `proceso_de_compra` | text | Identifier of the published procurement process | `CO1.BDOS.8931120` | Platform identifier linking the contract to its procurement process | candidate — `tender/id` (or `relatedProcess`) — pending P-05 ADR | 1 | — |
| 11 | `id_contrato` | text | Identifier of the signed contract, generated by the platform | `CO1.PCCNTR.8541219` | Platform-generated contract identifier (candidate primary key; uniqueness not yet proven) | candidate — `contracts/0/id` (ocid basis) — pending P-05 ADR | 1 | — |
| 12 | `referencia_del_contrato` | text | Identifier of the signed contract, generated by the state entity | `007-044-2025` | Entity-generated contract reference number | candidate — award/contract supplementary identifier — pending P-05 ADR | 1 | — |
| 13 | `estado_contrato` | text | Status of the contract, with respect to its execution, signature, or liquidation | `En ejecución` | Contract lifecycle status | candidate — `contracts/0/status` — pending P-05 ADR | 1 | — |
| 14 | `codigo_de_categoria_principal` | text | UNSPSC code of the main category for the contract | `V1.43211802` | Main UNSPSC item classification | candidate — `items/0/classification/id` — pending P-05 ADR | 1 | — |
| 15 | `descripcion_del_proceso` | text | Description of the object of the procurement process | `SUMINISTRO DE PAPELERIA; UTILES DE ESCRITORIO…` | Free-text description of what is being procured | candidate — `tender/title` or `tender/description` — pending P-05 ADR | 1 | — |
| 16 | `tipo_de_contrato` | text | Type of contract according to its legal framework | `Suministros` | Contract type per the Colombian legal framework | unknown | 1 | [HUMAN-5] |
| 17 | `modalidad_de_contratacion` | text | Contracting modality according to the selection model | `Mínima cuantía` | Procurement method category | candidate — `tender/procurementMethod` (+ method rationale) — pending P-05 ADR | 1 | [HUMAN-5] |
| 18 | `justificacion_modalidad_de` | text | Justification of the modality: the scenario under which the decision to define one or another contracting modality is taken | `Presupuesto inferior al 10% de la menor cuantía` | Rationale for choosing the modality | candidate — `tender/procurementMethodRationale` — pending P-05 ADR | 1 | — |
| 19 | `fecha_de_firma` | calendar_date | Date of signature of the electronic contract | `2025-11-07T00:00:00.000` | Contract signing date | candidate — `contracts/0/dateSigned` — pending P-05 ADR | 1 | — |
| 20 | `fecha_de_inicio_del_contrato` | calendar_date | Start date of the contractual responsibilities | `2025-11-07T00:00:00.000` | Contract period start | candidate — `contracts/0/period/startDate` — pending P-05 ADR | 1 | — |
| 21 | `fecha_de_fin_del_contrato` | calendar_date | End date of the contractual responsibilities | `2025-12-20T00:00:00.000` | Contract period end | candidate — `contracts/0/period/endDate` — pending P-05 ADR | 1 | — |
| 22 | `condiciones_de_entrega` | text | Conditions under which the product or service is delivered | `NXTWY.DLVY.3` | Delivery-conditions reference; sample is an opaque platform code whose format the description does not explain | unknown | 1 | [UNKNOWN-2] |
| 23 | `tipodocproveedor` | text | Type of document of the awarded supplier | `NIT` | Supplier ID document type | candidate — `awards/0/suppliers/0/identifier/scheme` — pending P-05 ADR | 1 | — |
| 24 | `documento_proveedor` | text | Document number of the awarded supplier | `860053274` | Supplier tax/ID number | candidate — `awards/0/suppliers/0/identifier/id` — pending P-05 ADR | 1 | — |
| 25 | `proveedor_adjudicado` | text | Name of the awarded supplier | `GRUPO LOS LAGOS SAS` | Supplier legal name | candidate — `awards/0/suppliers/0/name` — pending P-05 ADR | 1 | — |
| 26 | `es_grupo` | text | Determines whether the supplier is a group of entities; a CCE dataset contains the composition of the groups | `No` | Consortium/group-of-entities flag | unknown | 1 | [HUMAN-7] |
| 27 | `es_pyme` | text | Determines whether the company is a Pyme | `Si` | SME (Pyme) status flag | unknown | 1 | [HUMAN-7] |
| 28 | `habilita_pago_adelantado` | text | Determines whether the contract has the advance-payment option enabled | `No` | Advance payment authorization flag | unknown | 1 | — |
| 29 | `liquidaci_n` | text | Determines whether the contract has been liquidated | `Si` | Liquidation completion flag | unknown | 1 | — |
| 30 | `obligaci_n_ambiental` | text | Determines whether the contract has commitments to environmental obligations | `No` | Environmental obligations flag | unknown | 1 | — |
| 31 | `obligaciones_postconsumo` | text | Determines whether the contract has commitments to obligations after delivery of the product or provision of the service | `No` | Post-consumption obligations flag | unknown | 1 | [AMBIG-7] |
| 32 | `reversion` | text | Determines whether the contract has been reversed (reversado) | `No` | Contract reversal flag | unknown | 1 | — |
| 33 | `origen_de_los_recursos` | text | Origin of the resources, at the budgetary level | `Recursos Propios` | Budget-resource origin category | unknown | 1 | [HUMAN-3] |
| 34 | `destino_gasto` | text | Destination of the expenditure, at the budgetary level | `Funcionamiento` | Budget expenditure destination category | unknown | 1 | [HUMAN-3] |
| 35 | `valor_del_contrato` | number | Total value of the contract | `31000000` | Total contract value (COP) | candidate — `contracts/0/value/amount` — pending P-05 ADR | 1 | — |
| 36 | `valor_de_pago_adelantado` | number | Value of the advance payment | `0` | Advance payment amount (COP) | unknown | 1 | — |
| 37 | `valor_facturado` | number | Value invoiced to date | `29152720` | Invoiced-to-date amount (COP) | unknown | 1 | — |
| 38 | `valor_pendiente_de_pago` | number | Value pending payment to date | `1847280` | Outstanding payment amount (COP) | unknown | 1 | — |
| 39 | `valor_pagado` | number | Value paid to date | `29152720` | Paid-to-date amount (COP) | candidate — `contracts/0/implementation/transactions/value` — pending P-05 ADR | 1 | — |
| 40 | `valor_amortizado` | number | Value amortized to date | `0` | Amortized amount (COP); amortization semantics need domain confirmation | unknown | 1 | [HUMAN-1] |
| 41 | `valor_pendiente_de` | number | Value pending amortization to date | `0` | Pending-amortization amount (COP); truncated field name; relation to `valor_amortizado` needs confirmation | unknown | 1 | [HUMAN-1] |
| 42 | `valor_pendiente_de_ejecucion` | number | Value pending execution to date | `1847280` | Amount pending execution (COP) | unknown | 1 | — |
| 43 | `saldo_cdp` | number | Balance of the CDP assigned to the process and to the contract | `31000000` | Remaining balance of the Certificado de Disponibilidad Presupuestal (CDP) | unknown | 1 | [HUMAN-2] |
| 44 | `saldo_vigencia` | number | Current balance for the vigencia of the CDP assigned to the process and contract | `0` | Remaining fiscal-year (vigencia) balance of the CDP | unknown | 1 | [HUMAN-2] |
| 45 | `espostconflicto` | text | Determines whether the process is associated with any event of the peace agreement | `No` | Post-conflict (peace agreement) flag | unknown | 1 | — |
| 46 | `dias_adicionados` | number | Days added to the electronic contract | `0` | Extension days added to the contract duration | candidate — `contracts/0/amendments` (period change) — pending P-05 ADR | 1 | [HUMAN-10] |
| 47 | `puntos_del_acuerdo` | text | Pillars of the peace agreement, if the contract is post-conflict | `No aplica` | Peace agreement pillar reference | unknown | 1 | [AMBIG-2] |
| 48 | `pilares_del_acuerdo` | text | Pillars of the peace agreement | `No aplica` | Peace agreement pillar reference — near-duplicate of #47 | unknown | 1 | [AMBIG-2] |
| 49 | `urlproceso` | url | URL of the procurement process on the SECOP II platform | nested object: `{"url":"https://community.secop.gov.co/Public/Tendering/OpportunityDetail/Index?noticeUID=CO1.NTC.8965813…"}` | Link to the source process on SECOP II (citizen re-verification path, PR-01) | candidate — `tender/url` — pending P-05 ADR | 1 | [AMBIG-6] |
| 50 | `nombre_representante_legal` | text | Name of the legal representative of the entity | `HECTOR CORREA GIRALDO` | Legal representative name (of the buying entity) | unknown | 1 | [HUMAN-9] |
| 51 | `nacionalidad_representante_legal` | text | Nationality of the legal representative of the entity | `CO` | Legal representative nationality | unknown | 1 | [HUMAN-9] |
| 52 | `domicilio_representante_legal` | text | Home address of the legal representative of the entity | `No Definido` | Legal representative address | unknown | 2 | [HUMAN-9] [GAP-1] — sentinel `No Definido` in sample; population gap suspected |
| 53 | `tipo_de_identificaci_n_representante_legal` | text | Type of identification of the legal representative of the entity | `Sin Descripcion` | ID document type of the legal representative | unknown | 2 | [HUMAN-9] [GAP-2] — sentinel `Sin Descripcion` in sample |
| 54 | `identificaci_n_representante_legal` | text | Identification number of the legal representative of the entity | `Sin Descripcion` | ID number of the legal representative | unknown | 2 | [HUMAN-9] [GAP-3] — sentinel `Sin Descripcion` in sample |
| 55 | `g_nero_representante_legal` | text | Gender of the legal representative of the entity | `Hombre` | Legal representative gender | unknown | 1 | [HUMAN-9] |
| 56 | `presupuesto_general_de_la_nacion_pgn` | number | Value of the resource origin corresponding to the Presupuesto General de la Nación – PGN | `0` | National budget (PGN) contribution to the contract | unknown | 1 | [HUMAN-3] |
| 57 | `sistema_general_de_participaciones` | number | Value of the resource origin corresponding to the Sistema General de Participaciones | `0` | SGP transfer contribution to the contract | unknown | 1 | [HUMAN-3] |
| 58 | `sistema_general_de_regal_as` | number | Value of the resource origin corresponding to the Sistema General de Regalías | `0` | Royalties-system contribution to the contract | unknown | 1 | [HUMAN-3] |
| 59 | `recursos_propios_alcald_as_gobernaciones_y_resguardos_ind_genas_` | number | Indicates the value of the entity's own resources in the electronic contract | `0` | Own resources of mayoralties, governorates, and Indigenous resguardos — field name enumerates specific sources while the description is generic | unknown | 1 | [HUMAN-3] [AMBIG-8] |
| 60 | `recursos_de_credito` | number | Value of the resource origin corresponding to Recursos de Crédito | `0` | Credit-resources contribution to the contract | unknown | 1 | [HUMAN-3] |
| 61 | `recursos_propios` | number | Value of the resource origin corresponding to Recursos Propios | `31000000` | Own-resources contribution to the contract | unknown | 1 | [HUMAN-3] |
| 62 | `ultima_actualizacion` | calendar_date | Date of the last update of the electronic contract | *(absent in sample row)* | Last-update timestamp of the contract record | candidate — release date / amendment date — pending P-05 ADR | 2 | [GAP-4] — absent from sample row; nullability suspected |
| 63 | `codigo_entidad` | number | Code of the entity on the SECOPII platform | `700985252` | Internal platform entity code | candidate — `buyer/additionalIdentifiers` — pending P-05 ADR | 1 | — |
| 64 | `codigo_proveedor` | text | Code of the supplier on the SECOPII platform *(original description contains typo "edn")* | `700648058` | Internal platform supplier code | candidate — `awards/0/suppliers/0/additionalIdentifiers` — pending P-05 ADR | 1 | — |
| 65 | `fecha_inicio_liquidacion` | calendar_date | Start date of liquidation of the electronic contract | `2025-12-21T00:00:00.000` | Liquidation period start | unknown | 2 | [GAP-5] — settlement/liquidation dates are Level 2 per master-plan (available but with gaps) |
| 66 | `fecha_fin_liquidacion` | calendar_date | End date of liquidation of the electronic contract | `2026-04-20T00:00:00.000` | Liquidation period end | unknown | 2 | [GAP-5] — same basis as #65 |
| 67 | `objeto_del_contrato` | text | Object of the electronic contract | `SUMINISTRO DE PAPELERIA; UTILES DE ESCRITORIO…` | Contract purpose text (overlaps #15) | candidate — `contracts/0/description` — pending P-05 ADR | 1 | — |
| 68 | `duraci_n_del_contrato` | text | Describes the duration of the contract in time | `50 Dia(s)` | Contract duration as text with embedded unit | candidate — `contracts/0/period` (duration) — pending P-05 ADR | 1 | [HUMAN-8] |
| 69 | `nombre_del_banco` | text | Indicates the name of the bank where contract payments will be deposited | `No definido` | Paying bank name — financial data | unknown | 2 | [HUMAN-6] [GAP-6] — sentinel `No definido`; sensitive |
| 70 | `tipo_de_cuenta` | text | Type of account where contract payments will be deposited | `No Definido` | Bank account type — financial data | unknown | 2 | [HUMAN-6] [GAP-7] |
| 71 | `n_mero_de_cuenta` | text | Account number where contract payments will be made | `No definido` | Bank account number — sensitive financial data | unknown | 2 | [HUMAN-6] [GAP-8] |
| 72 | `el_contrato_puede_ser_prorrogado` | text | Indicates whether the contract can be extended (prorrogado) or not | `No` | Contract extendability flag | unknown | 1 | — |
| 73 | `fecha_de_notificaci_n_de_prorrogaci_n` | calendar_date | Indicates the date of notification of the extension of the contract *(original description has typo "Infica")* | *(absent in sample row)* | Extension notification date | unknown | 2 | [GAP-9] — absent from sample row |
| 74 | `nombre_ordenador_del_gasto` | text | Name of the expenditure authorizer (ordenador del gasto) of the buying entity | `DIEGO OMAR GORDILLO ROJAS` | Expenditure authorizer (budget commitment authority) name | unknown | 1 | [HUMAN-9] |
| 75 | `tipo_de_documento_ordenador_del_gasto` | text | Indicates the type of document of the expenditure authorizer of the buying entity | `Cédula de Ciudadanía` | ID document type of the expenditure authorizer | unknown | 1 | [HUMAN-9] |
| 76 | `n_mero_de_documento_ordenador_del_gasto` | text | Indicates the number of the document of the expenditure authorizer | `79651997` | ID number of the expenditure authorizer | unknown | 1 | [HUMAN-9] |
| 77 | `nombre_supervisor` | text | Name of the supervisor of the buying entity | `Juan Esteban Arrieta Giraldo` | Contract supervisor name | unknown | 1 | [HUMAN-9] |
| 78 | `tipo_de_documento_supervisor` | text | Indicates the type of document of the supervisor of the buying entity | `Cédula de Ciudadanía` | ID document type of the supervisor | unknown | 1 | [HUMAN-9] |
| 79 | `n_mero_de_documento_supervisor` | text | Indicates the number of the document of the supervisor | `71319321` | ID number of the supervisor | unknown | 1 | [HUMAN-9] |
| 80 | `nombre_ordenador_de_pago` | text | Name of the payment authorizer (Ordenador de Pago) of the buying entity | `DIEGO OMAR GORDILLO ROJAS` | Payment authorizer name | unknown | 1 | [HUMAN-9] |
| 81 | `tipo_de_documento_ordenador_de_pago` | text | Indicates the type of document of the payment authorizer of the buying entity | `Cédula de Ciudadanía` | ID document type of the payment authorizer | unknown | 1 | [HUMAN-9] |
| 82 | `n_mero_de_documento_ordenador_de_pago` | text | Indicates the number of the document of the payment authorizer | `79651997` | ID number of the payment authorizer | unknown | 1 | [HUMAN-9] |
| 83 | `documentos_tipo` | text | Indicates whether type documents (documentos tipo) were used in the contract | `No` | Standard/template document usage flag | unknown | 1 | — |
| 84 | `descripcion_documentos_tipo` | text | Description of the type documents used | `No definido` | Description of the standard documents used | unknown | 2 | [GAP-10] — sentinel `No definido` in sample |
| 85 | `direcci_n_de_ejecuci_n_del_contrato` | text | Address where the contract was executed during its validity | `Carrera 50 N° 79 Sur 101 Bodegas Stock Sur; La Estrella, Antioquia, COLOMBIA` *(multi-line in payload)* | Physical execution address of the contract | candidate — `items/0/deliveryAddress` — pending P-05 ADR | 1 | — |
| 86 | `criterios_de_sostenibilidad_ambiental` | text | Indicates whether environmental sustainability criteria were defined | `No definido` | Environmental sustainability criteria flag | unknown | 2 | [GAP-11] — sentinel `No definido` in sample |
| 87 | `requisitos_ambientales_en_las_especificaciones_t_cnicas` | text | Indicates whether environmental requirements were defined in the technical specifications | `No definido` | Environmental requirements in specifications flag | unknown | 2 | [GAP-11] — sentinel `No definido` |
| 88 | `criterios_ambientales_en_la_evaluaci_n_de_las_ofertas` | text | Indicates whether environmental criteria were defined in the evaluation of the offers | `No definido` | Environmental criteria in bid evaluation flag | unknown | 2 | [GAP-11] — sentinel `No definido` |
| 89 | `obligaciones_contractuales_o_poscontractuales_a_nivel_ambiental` | text | Indicates whether contractual or post-contractual obligations at the environmental level were defined | `No definido` | Environmental (post-)contractual obligations flag | unknown | 2 | [GAP-11] — sentinel `No definido` |
| 90 | `uso_de_etiquetado_ecol_gico` | text | Indicates whether the use of ecological labeling was defined | `No definido` | Eco-labelling usage flag | unknown | 2 | [GAP-11] — sentinel `No definido` |
| 91 | `obligaciones_posconsumo` | text | Indicates whether post-consumption obligations were defined | `No` | Post-consumption obligations flag — near-duplicate of #31 | unknown | 1 | [AMBIG-7] |
| 92 | `fecha_inicio_obligaciones_posconsumo` | calendar_date | Start date of post-consumption obligations | *(absent in sample row)* | Post-consumption obligations start date | unknown | 2 | [GAP-12] — absent from sample row |
| 93 | `fecha_fin_obligaciones_posconsumo` | calendar_date | End date of post-consumption obligations | *(absent in sample row)* | Post-consumption obligations end date | unknown | 2 | [GAP-12] — absent from sample row |
| 94 | `fecha_inicio_reversi_n` | calendar_date | Start date of reversal | *(absent in sample row)* | Reversal period start date | unknown | 2 | [GAP-13] — absent from sample row |
| 95 | `fecha_fin_reversi_n` | calendar_date | End date of reversal | *(absent in sample row)* | Reversal period end date | unknown | 2 | [GAP-13] — absent from sample row |

**Row count check: 95 fields present (1–95). ✔**

---

## 3. Fields — dataset `p6dx-8zbt` (SECOP II - Procesos de Contratación, 59 fields)
Source dataset: `p6dx-8zbt`. Types as published by the API (`dataTypeName`). "OCDS mapping" values are candidates pending the P-05 ADR.
*(The original skeleton's §3 was reserved for `rpmr-utcd`; per the approved Tier classification that dataset is Tier B/deferred with no fields documented, so §3 is repurposed for the Tier A dataset `p6dx-8zbt`.)*

| # | Field (API fieldName) | Type | Description (EN; ES original authoritative) | Sample value (2026-10-21 payload) | Proposed business meaning *(interpretation)* | OCDS mapping candidate | Level | Flags |
|---|---|---|---|---|---|---|---|---|
| 1 | `entidad` | text | Name of the entity that publishes the public procurement process | `DEPARTAMENTO ADMINISTRATIVO NACIONAL DE ESTADISTICA (DANE)` | Buying entity official name | candidate — `buyer/name` — pending P-05 ADR | 1 | — |
| 2 | `nit_entidad` | text | NIT of the entity that published the process | `899999027` | Entity NIT *(note: type `text` here, `number` in `jbjy-vk9h`)* | candidate — `buyer/identifier/id` — pending P-05 ADR | 1 | — |
| 3 | `departamento_entidad` | text | Department in which the entity is registered | `Distrito Capital de Bogotá` | Entity registration department | candidate — `buyer/address/region` — pending P-05 ADR | 1 | — |
| 4 | `ciudad_entidad` | text | City in which the entity is registered | `Bogotá` | Entity registration city | candidate — `buyer/address/locality` — pending P-05 ADR | 1 | — |
| 5 | `ordenentidad` | text | Order of the entity (Nacional, Regional) *(SODA value as published)* | `Nacional` | Administrative order classification | unknown | 1 | [HUMAN-4] |
| 6 | `codigo_pci` | text | Identifies whether the entity is or is not centralized | `Centralizada` | Centralization status — field name (`codigo_pci`) contradicts the description | unknown | 1 | [AMBIG-3] |
| 7 | `id_del_proceso` | text | Unique identifier of the process, value generated by the platform | `CO1.REQ.2577563` | Platform process identifier (candidate primary key; uniqueness not yet proven) | candidate — `tender/id` (ocid basis) — pending P-05 ADR | 1 | — |
| 8 | `referencia_del_proceso` | text | Identifier of the process, value generated by the entity | `EDP-545-2022` | Entity-generated process reference | candidate — `tender/id` (secondary) — pending P-05 ADR | 1 | — |
| 9 | `ppi` | text | Code of Unit – Sub Unit Contracting | `700474109` | Unclear: sample value is identical to `codigo_entidad` (#58), suggesting it is an entity/platform code rather than a unit code | unknown | 1 | [AMBIG-1] |
| 10 | `id_del_portafolio` | text | Identifier of the portfolio to which the procurement process corresponds | `CO1.BDOS.2503732` | Portfolio (aggregation of processes) identifier | unknown | 1 | — |
| 11 | `nombre_del_procedimiento` | text | Name given to the procurement process by the entity | `TS_MERCADO_2022_GEIH_TH Prestación de servicios profesionales para procesar la información de la GEIH…` | Process title | candidate — `tender/title` — pending P-05 ADR | 1 | — |
| 12 | `descripci_n_del_procedimiento` | text | First definition of the main characteristics of the process | `TS_MERCADO_2022_GEIH_TH Prestación de servicios profesionales para procesar la información de la GEIH para el análisis de resultados…` | Process description | candidate — `tender/description` — pending P-05 ADR | 1 | — |
| 13 | `fase` | text | Phase in which the process currently is | `Presentación de oferta` | Current lifecycle phase of the process | candidate — `tender/status` — pending P-05 ADR | 1 | — |
| 14 | `fecha_de_publicacion_del` | calendar_date | Date of the initial publication of the procurement process | `2022-01-18T00:00:00.000` | First publication date | candidate — `tender/datePublished` — pending P-05 ADR | 1 | — |
| 15 | `fecha_de_ultima_publicaci` | calendar_date | Date of the last publication made for the process | `2022-01-18T00:00:00.000` | Last publication date (truncated field name) | candidate — release date — pending P-05 ADR | 1 | — |
| 16 | `fecha_de_publicacion_fase` | calendar_date | Date of publication, within the process, of the Planning phase in Prequalification | *(absent in sample row)* | Phase-specific publication date | unknown | 2 | [GAP-14] — absent from sample row |
| 17 | `fecha_de_publicacion_fase_1` | calendar_date | Date of publication, within the process, of the Selection phase in Prequalification | *(absent in sample row)* | Phase-specific publication date | unknown | 2 | [GAP-14] — absent from sample row |
| 18 | `fecha_de_publicacion` | calendar_date | Date of publication, within the process, of the Manifestation of Interest phase | *(absent in sample row)* | Phase-specific publication date — name collides with #14 (`fecha_de_publicacion_del`, "initial publication") | unknown | 2 | [GAP-14] [AMBIG-5] |
| 19 | `fecha_de_publicacion_fase_2` | calendar_date | Date of publication, within the process, of the Draft (Borrador) phase | *(absent in sample row)* | Phase-specific publication date | unknown | 2 | [GAP-14] — absent from sample row |
| 20 | `fecha_de_publicacion_fase_3` | calendar_date | Date of publication, within the process, of the Selection phase | `2022-01-18T00:00:00.000` | Selection-phase publication date | unknown | 1 | — |
| 21 | `precio_base` | number | Base, projected price of the procurement process | `57333333` | Projected budget for the process (COP) | candidate — `tender/value/amount` — pending P-05 ADR | 1 | — |
| 22 | `modalidad_de_contratacion` | text | Selection modality under which the procurement process is developed | `Contratación directa` | Procurement method category | candidate — `tender/procurementMethod` — pending P-05 ADR | 1 | [HUMAN-5] |
| 23 | `justificaci_n_modalidad_de` | text | If required, justification for the selection modality chosen for the procurement process | `Servicios profesionales y apoyo a la gestión` | Modality rationale | candidate — `tender/procurementMethodRationale` — pending P-05 ADR | 1 | — |
| 24 | `duracion` | number | Value of the estimated duration of the public procurement process | `344` | Estimated duration value (unit in #25) | unknown | 1 | [HUMAN-8] |
| 25 | `unidad_de_duracion` | text | Unit that applies to the estimated duration of the procurement process | `día(s)` | Duration unit | unknown | 1 | [HUMAN-8] |
| 26 | `fecha_de_recepcion_de` | calendar_date | Date assigned for the reception of responses by suppliers within the process | *(absent in sample row)* | Submission deadline | candidate — `tender/tenderPeriod/endDate` — pending P-05 ADR | 2 | [GAP-15] — absent from sample row |
| 27 | `fecha_de_apertura_de_respuesta` | calendar_date | Estimated date for the opening of the responses | *(absent in sample row)* | Estimated bid-opening date | unknown | 2 | [GAP-15] — absent from sample row |
| 28 | `fecha_de_apertura_efectiva` | calendar_date | Real date for the opening of the responses | *(absent in sample row)* | Actual bid-opening date | unknown | 2 | [GAP-15] — absent from sample row |
| 29 | `ciudad_de_la_unidad_de` | text | City in which the contracting unit of the entity is registered *(original has typo "Cuidad")* | `Bogotá` | Contracting unit city | candidate — `buyer/address/locality` (unit level) — pending P-05 ADR | 1 | — |
| 30 | `nombre_de_la_unidad_de` | text | Name of the contracting unit of the entity | `SECRETARIA GENERAL - DANE CENTRAL` | Contracting unit name | candidate — `buyer/name` (unit level) — pending P-05 ADR | 1 | — |
| 31 | `proveedores_invitados` | number | Number of suppliers invited to participate in the process, in total | `0` | Total invited suppliers | candidate — `tender/numberOfTenderers` — pending P-05 ADR | 1 | — |
| 32 | `proveedores_con_invitacion` | number | Suppliers with an invitation to participate made directly | `0` | Directly invited suppliers count | unknown | 1 | — |
| 33 | `visualizaciones_del` | number | Number of views of the procurement process made through the tool | `0` | Platform view count (telemetry; not procurement substance) | unknown | 1 | — |
| 34 | `proveedores_que_manifestaron` | number | Suppliers that manifested interest in the process through the platform | `0` | Interested-suppliers count | unknown | 1 | — |
| 35 | `respuestas_al_procedimiento` | number | Responses made to the procedure, by suppliers as well as by the entity itself | `0` | Total responses received | unknown | 1 | — |
| 36 | `respuestas_externas` | number | Number of responses made by external entities | `0` | External-entities responses count | unknown | 1 | — |
| 37 | `conteo_de_respuestas_a_ofertas` | number | Number of responses made directly in the offers | `0` | Offer responses count — semantics of "directly in the offers" unclear | unknown | 1 | [AMBIG-9] |
| 38 | `proveedores_unicos_con` | number | Unique suppliers that have written responses in the process | `0` | Unique responding-suppliers count | unknown | 1 | — |
| 39 | `numero_de_lotes` | number | Number of lots of items requested within the process | `0` | Number of lots | candidate — `tender/items` (per lot) — pending P-05 ADR | 1 | — |
| 40 | `estado_del_procedimiento` | text | Current state of development of the public procurement procedure | `Seleccionado` | Procedure status | candidate — `tender/status` — pending P-05 ADR | 1 | — |
| 41 | `id_estado_del_procedimiento` | number | Identifier of the state of the procedure | `70` | Numeric status code; no codebook exists in the metadata, so semantics are only inferable by pairing with #40 | unknown | 1 | [UNKNOWN-1] |
| 42 | `adjudicado` | text | Determines whether the process was awarded | `No` | Awarded flag | candidate — `awards/0/status` — pending P-05 ADR | 1 | — |
| 43 | `id_adjudicacion` | text | Identifier of the award | `No Adjudicado` *(sentinel)* | Award identifier; sentinel string when not awarded | candidate — `awards/0/id` — pending P-05 ADR | 1 | [GAP-16] — sentinel `No Adjudicado` |
| 44 | `codigoproveedor` | text | Code, on the platform, of the awarded supplier | `No Definido` *(sentinel)* | Platform supplier code | candidate — `awards/0/suppliers/0/additionalIdentifiers` — pending P-05 ADR | 1 | [GAP-16] — sentinel |
| 45 | `departamento_proveedor` | text | Department in which the awarded supplier is registered | `No Definido` *(sentinel)* | Supplier registration department | candidate — `awards/0/suppliers/0/address/region` — pending P-05 ADR | 1 | [GAP-16] — sentinel |
| 46 | `ciudad_proveedor` | text | City in which the awarded supplier is registered | `No Definido` *(sentinel)* | Supplier registration city | candidate — `awards/0/suppliers/0/address/locality` — pending P-05 ADR | 1 | [GAP-16] — sentinel |
| 47 | `fecha_adjudicacion` | calendar_date | Date on which the award of the process was made to the selected supplier | *(absent in sample row — process not awarded)* | Award date | candidate — `awards/0/date` — pending P-05 ADR | 2 | [GAP-17] — absent from sample row; also state-dependent (only populated when awarded) |
| 48 | `valor_total_adjudicacion` | number | Total awarded value | `0` | Award value (COP; 0 when not awarded) | candidate — `awards/0/value/amount` — pending P-05 ADR | 1 | — |
| 49 | `nombre_del_adjudicador` | text | Name of the user who executed the award action | `No Adjudicado` *(sentinel)* | Awarding officer name | unknown | 1 | [HUMAN-9] [GAP-16] |
| 50 | `nombre_del_proveedor` | text | Name of the awarded supplier | `No Definido` *(sentinel)* | Supplier name | candidate — `awards/0/suppliers/0/name` — pending P-05 ADR | 1 | [GAP-16] — sentinel |
| 51 | `nit_del_proveedor_adjudicado` | text | NIT of the awarded supplier | `No Definido` *(sentinel)* | Supplier NIT | candidate — `awards/0/suppliers/0/identifier/id` — pending P-05 ADR | 1 | [GAP-16] — sentinel |
| 52 | `codigo_principal_de_categoria` | text | UNSPSC code of the main category of the product or service acquired in the process | `V1.80111500` | Main UNSPSC classification | candidate — `tender/items/0/classification/id` — pending P-05 ADR | 1 | — |
| 53 | `estado_de_apertura_del_proceso` | text | Current state of opening of information of the process | `Abierto` | Transparency/openness status of the process | unknown | 1 | — |
| 54 | `tipo_de_contrato` | text | Type of contract defined for the procurement process | `Prestación de servicios` | Contract type | unknown | 1 | [HUMAN-5] |
| 55 | `subtipo_de_contrato` | text | Subtype of contract defined for the procurement process | `No Definido` *(sentinel)* | Contract subtype | unknown | 1 | [GAP-18] — sentinel `No Definido` |
| 56 | `categorias_adicionales` | text | Identifier of the additional UNSPSC categories included in the product or service acquired in the process | `No definido` *(sentinel)* | Additional UNSPSC categories | candidate — items additional classifications — pending P-05 ADR | 1 | [GAP-19] — sentinel `No definido` |
| 57 | `urlproceso` | url | URL, on the platform, where the procurement process can be consulted | nested object: `{"url":"https://community.secop.gov.co/Public/Tendering/OpportunityDetail/Index?noticeUID=CO1.NTC.2597221"}` | Link to the source process on SECOP II (PR-01) | candidate — `tender/url` — pending P-05 ADR | 1 | [AMBIG-6] |
| 58 | `codigo_entidad` | number | Code of the entity on the SECOPII platform | `700474109` | Internal platform entity code | candidate — `buyer/additionalIdentifiers` — pending P-05 ADR | 1 | — |
| 59 | `estado_resumen` | text | Summary of the state of the public procurement process | `Presentación de oferta` | Summary state — sample duplicates #13 (`fase`); unclear whether stored or derived | unknown | 1 | [AMBIG-4] |

**Row count check: 59 fields present (1–59), matching the verified metadata ("columns: 59"). ✔**

---

## 4. Verification procedure
1. Query the dataset's metadata endpoint `/api/views/{id}.json` for field names, types, and official descriptions, and `$limit=1` for a sample payload (full procedure to be documented in `docs/ingestion-runbook.md` once that file exists).
2. Record field, type, description, sample value, proposed meaning, OCDS candidate, reliability level, and flags — only from verified API metadata (PR-06).
3. A human validates the entries before they are used in code (PR-06, PR-15). Until then, nothing here is normative.

## 5. Known coverage gaps (PR-08)
What the Tier A pair (`jbjy-vk9h` + `p6dx-8zbt`) cannot provide:
- **Physical execution data** — actual delivery/execution progress is not in SECOP II (Level 3 per master-plan; SPI/DNP territory; see also Tier C dataset `mfmm-jqmq`, deferred).
- **SECOP I legacy contracts** — contracts published before SECOP II are not in Tier A; `rpmr-utcd` (Tier B) holds them but is deferred (double-counting risk, ADR-001a).
- **Supplier registry attributes** — the dedicated registry `qmzu-gj57` (Tier C) holds additional supplier attributes not present in the contracts dataset; supplier identity in Tier A is limited to `documento_proveedor`/`proveedor_adjudicado`/`codigo_proveedor`.
- **Invoices, guarantees, budget lines, suspensions, bidders per process** — held in separate Tier C datasets, out of Phase 1 scope.
- **Ultimate beneficial owners** — not in SECOP (Level 3 per master-plan; RUB/DIAN).
- **Field-level gaps within Tier A** — see [GAP-1]…[GAP-19]: sentinel values (`No Definido`, `Sin Descripcion`, `No aplica`, `No Adjudicado`) and fields absent from the sample row mean population coverage must be measured before any aggregate relies on these fields (aggregates inherit the lowest reliability level of their inputs, PR-07).

---


---

## 6. Validation notes (V-1…V-4 — accepted 2026-10-21)

Findings from the 2026-10-21 conceptual validation round, executed with read-only
SoQL queries against the Tier A datasets. Accepted by the human as amendments to
Conceptual Model v0.1. These notes are **binding domain constraints** for all
future documentation, ingestion design, and mapping work.

### V-1 — `id_contrato` is a logical business identifier, not a physical row identifier
- Evidence: duplicated `id_contrato` values confirmed across distinct rows in `jbjy-vk9h` (group-by/count verification).
- Rule: `id_contrato` identifies a contract at the logical/business level. Row-level physical identity and deduplication strategy are storage-phase concerns and are deliberately out of scope here.
- Consequence: any future storage model must treat contract identity as requiring a documented deduplication rule; it may not assume row-level uniqueness from the source.

### V-2 — Process → Contract cardinality is 1:N
- Evidence: real dataset evidence shows single processes legitimately associated with multiple contracts (top observed process has 654 linked contracts; several processes exceed 380).
- Rule: one Process relates to zero or many Contracts. One-to-one assumptions are forbidden.
- Consequence: any model, query, or export that assumes one contract per process is invalid.

### V-3 — Supplier identity authority rules
- Evidence: process-side supplier fields (`nit_del_proveedor_adjudicado`, `codigoproveedor` in `p6dx-8zbt`) are frequently populated with the sentinel value `No Definido`; contract-side fields (`documento_proveedor`, `proveedor_adjudicado`, `codigo_proveedor` in `jbjy-vk9h`) are consistently populated.
- Rule: **`jbjy-vk9h` is the authoritative source for supplier identity in Phase 1.** `p6dx-8zbt` supplier fields are **supplementary only** and must never be used as a join key or identity resolution source.
- Consequence: supplier deduplication/identity resolution must be built on contract-side fields; process-side supplier data may enrich but never define identity.

### V-4 — Authoritative cross-dataset linkage (CRITICAL)
- Evidence: contract-side process identifiers use the `CO1.BDOS.*` namespace, which does **not** exist in `p6dx-8zbt.id_del_proceso` (`CO1.REQ.*` namespace). The same `CO1.BDOS.*` values are found in `p6dx-8zbt.id_del_portafolio`, with all sampled links confirmed present (8/8).
- Rule (binding):
  > **`jbjy-vk9h.proceso_de_compra` → `p6dx-8zbt.id_del_portafolio`** is the authoritative cross-dataset linkage.
- `id_del_proceso` (`CO1.REQ.*`) is a different entity level within the process dataset (the request/requirement) and must **never** be used to join contracts to processes.
- Consequence: any join, mapping, or OCDS alignment that links via `id_del_proceso` is invalid and must be corrected before design. This rule overrides any prior assumption in any document.

---

## 7. Cross-dataset linkage section

Verified linkage map (2026-10-21, all evidence from live read-only queries):

| Contract side (`jbjy-vk9h`) | Linkage namespace | Process side (`p6dx-8zbt`) | Verified? | Notes |
|---|---|---|---|---|
| `proceso_de_compra` | `CO1.BDOS.*` | `id_del_portafolio` | ✅ Yes (8/8 sample) | **Authoritative** contract-to-process link (V-4) |
| `proceso_de_compra` | `CO1.BDOS.*` | `id_del_proceso` | ❌ No (0/8 sample) | Forbidden as a join — different namespace (`CO1.REQ.*`) |
| — | — | `referencia_del_proceso` | not a linkage | Entity-generated reference, format varies per entity; not a reliable key |

Linkage counts observed:
- Distinct `proceso_de_compra` in `jbjy-vk9h`: **5,116,664**
- Distinct `id_del_portafolio` in `p6dx-8zbt`: **8,669,811**
- Contracts reference a subset of processes — expected, since not every process yields a contract.

**Linkage rule (institutional, cite as V-4):** any future artifact that relates contracts to processes must join on `proceso_de_compra` ↔ `id_del_portafolio` and must document this rule explicitly.

---

## 8. Supplier identity authority rules (per V-3)

| Purpose | Authoritative source | Field(s) | Notes |
|---|---|---|---|
| Supplier identity resolution | `jbjy-vk9h` | `documento_proveedor` (natural ID), `tipodocproveedor` (ID type), `proveedor_adjudicado` (name), `codigo_proveedor` (platform code) | Consistently populated; NIT normalization to `text` applies (dictionary §6, Decision 1) |
| Supplier enrichment (supplementary) | `p6dx-8zbt` | `nit_del_proveedor_adjudicado`, `codigoproveedor`, `nombre_del_proveedor` | Frequently `No Definido`; may enrich but never define identity |
| Supplier registry attributes | `qmzu-gj57` (Tier C — out of Phase 1) | documented in §5 | Deferred; future ADR/phase if needed |

Rules:
1. Supplier entity identity in Phase 1 is defined **only** from contract-side fields (V-3).
2. Process-side supplier fields are supplementary and must never be used as join keys or as the primary identity source.
3. The dedicated supplier registry (`qmzu-gj57`) remains Tier C; any linkage to it requires a new ADR.

---

## 9. Identifier catalog (verified 2026-10-21)

Identifier namespaces and rules observed across Tier A datasets. All identifiers are stored as `text` regardless of source type (dictionary §6, Decision 1).

| Identifier | Namespace / format | Dataset(s) | Entity it identifies | Authority | Notes |
|---|---|---|---|---|---|
| `proceso_de_compra` | `CO1.BDOS.*` | `jbjy-vk9h` | Contract-container process | `jbjy-vk9h` (contract side) | Links to `id_del_portafolio` (V-4) |
| `id_del_portafolio` | `CO1.BDOS.*` | `p6dx-8zbt` | Same process container, process side | `p6dx-8zbt` | **The** process-side half of the V-4 link |
| `id_del_proceso` | `CO1.REQ.*` | `p6dx-8zbt` | Request/requirement level within the process | `p6dx-8zbt` | Distinct entity level; never joins to contracts (V-4) |
| `id_contrato` | `CO1.PCCNTR.*` | `jbjy-vk9h` | Contract | `jbjy-vk9h` | Logical business identifier, not physical row ID (V-1) |
| `referencia_del_contrato` | entity-defined (varies) | `jbjy-vk9h` | Contract (entity-generated) | `jbjy-vk9h` | Non-standardized format; not a key |
| `nit_entidad` | Colombian NIT | `jbjy-vk9h` (`number`), `p6dx-8zbt` (`text`) | Buying entity | Both; text normalization per Decision 1 | Type divergence is the reason for Decision 1 |
| `nit_del_proveedor_adjudicado` | Colombian NIT | `p6dx-8zbt` | Awarded supplier (process side) | `p6dx-8zbt` | Supplementary only (V-3) |
| `documento_proveedor` | ID number (type in `tipodocproveedor`) | `jbjy-vk9h` | Awarded supplier (contract side) | `jbjy-vk9h` | Authoritative (V-3) |
| `codigo_entidad` | platform-internal | `jbjy-vk9h`, `p6dx-8zbt` | Buying entity (platform code) | both | Useful for cross-dataset entity reconciliation |
| `codigo_proveedor` / `codigoproveedor` | platform-internal | `jbjy-vk9h`, `p6dx-8zbt` | Supplier (platform code) | contract side authoritative | Same entity, different field name per dataset — document both spellings |
| `id_adjudicacion` | platform-internal | `p6dx-8zbt` | Award | `p6dx-8zbt` | Sentinel `No Adjudicado` when unawarded (GAP-16) |

Identifier design rules (institutional):
1. All identifiers stored as `text` (Decision 1); never coerced to numeric types.
2. Identifier namespaces (`CO1.BDOS.*`, `CO1.REQ.*`, `CO1.PCCNTR.*`) are semantically distinct — never treated as interchangeable.
3. Sentinel values (`No Definido`, `No Adjudicado`, `Sin Descripcion`) are published values, preserved as-is (PR-06); they are not nulls and not identifiers.

---

## 10. Validation evidence log (2026-10-21)

| ID | Validation | Method | Result | Accepted |
|---|---|---|---|---|
| V-1 | `id_contrato` uniqueness | group-by/count on `jbjy-vk9h` | duplicates confirmed; logical key only | 2026-10-21 |
| V-2 | Process→Contract cardinality | group-by `proceso_de_compra`, top counts | 1:N confirmed (max observed 654) | 2026-10-21 |
| V-3 | Supplier identity authority | field sampling both datasets | contract-side authoritative | 2026-10-21 |
| V-4 | Cross-dataset linkage | namespace comparison + sample joins | `proceso_de_compra` ↔ `id_del_portafolio` confirmed; `id_del_proceso` rejected | 2026-10-21 |


## Flag validation checklist (for the human)

| Ref | Field(s) | Dataset | Issue | Question for the human |
|---|---|---|---|---|
| [UNKNOWN-1] | `id_estado_del_procedimiento` | p6dx-8zbt | Numeric status code, no codebook in metadata | Confirm status-code semantics (or accept `estado_del_procedimiento` as the only status source) |
| [UNKNOWN-2] | `condiciones_de_entrega` | jbjy-vk9h | Sample value `NXTWY.DLVY.3` is an opaque platform code | Confirm meaning/code format |
| [AMBIG-1] | `ppi` | p6dx-8zbt | Description says unit code; sample value equals `codigo_entidad` | Confirm actual meaning |
| [AMBIG-2] | `puntos_del_acuerdo`, `pilares_del_acuerdo` | jbjy-vk9h | Near-duplicate fields, identical descriptions | Confirm distinction (or treat as duplicates) |
| [AMBIG-3] | `codigo_pci` | p6dx-8zbt | Name suggests a PCI code; description says centralization | Confirm meaning |
| [AMBIG-4] | `estado_resumen` | p6dx-8zbt | Sample duplicates `fase`; stored vs. derived unclear | Confirm whether derived — affects PR-05/PR-06 labeling if we derive it |
| [AMBIG-5] | `fecha_de_publicacion` | p6dx-8zbt | Name collides with `fecha_de_publicacion_del` (different meanings) | Confirm distinction to avoid ingestion mix-ups |
| [AMBIG-6] | `urlproceso` | both | API type `url` but payload is a nested object `{"url": …}` | Confirm ingestion unwrapping rule |
| [AMBIG-7] | `obligaciones_postconsumo` vs `obligaciones_posconsumo` | jbjy-vk9h | Two fields with near-identical meaning | Confirm distinction |
| [AMBIG-8] | `recursos_propios_alcald_as_gobernaciones_y_resguardos_ind_genas_` | jbjy-vk9h | Field name enumerates sources; description is generic ("entity's own resources") | Confirm actual scope |
| [AMBIG-9] | `conteo_de_respuestas_a_ofertas` | p6dx-8zbt | "Responses made directly in the offers" unclear | Confirm semantics |
| [HUMAN-1] | `valor_amortizado`, `valor_pendiente_de` | jbjy-vk9h | Amortization semantics | Domain confirmation (fiscal) |
| [HUMAN-2] | `saldo_cdp`, `saldo_vigencia` | jbjy-vk9h | CDP / vigencia budget semantics | Domain confirmation (fiscal) |
| [HUMAN-3] | `origen_de_los_recursos`, `destino_gasto`, `presupuesto_general_de_la_nacion_pgn`, `sistema_general_de_participaciones`, `sistema_general_de_regal_as`, `recursos_propios_alcald_as_…`, `recursos_de_credito`, `recursos_propios` | jbjy-vk9h | Budget-source breakdown; sample suggests components sum to `valor_del_contrato` | Confirm fiscal classification and whether components are additive |
| [HUMAN-4] | `orden`, `sector`, `rama`, `entidad_centralizada`, `ordenentidad` | both | Colombian administrative classification taxonomy | Confirm taxonomy interpretation |
| [HUMAN-5] | `tipo_de_contrato`, `modalidad_de_contratacion` | both | Legal-framework vocabularies | Legal validation before code relies on value sets |
| [HUMAN-6] | `nombre_del_banco`, `tipo_de_cuenta`, `n_mero_de_cuenta` | jbjy-vk9h | Sensitive financial data | Privacy/legal review before any exposure decision |
| [HUMAN-7] | `es_grupo`, `es_pyme` | jbjy-vk9h | Pyme/group definitions reference an out-of-scope CCE dataset | Confirm definitions and future linkage plan |
| [HUMAN-8] | `duraci_n_del_contrato` (text "50 Dia(s)"), `duracion` + `unidad_de_duracion` (number + unit) | both | Two different duration encodings | Confirm normalization rule (unit parsing) |
| [HUMAN-9] | legal-representative, supervisor, ordenador del gasto/pago, adjudicador fields (names, ID numbers, gender) | both | Personal data | Privacy/legal review before any exposure; PR-09-adjacent caution |
| [HUMAN-10] | `dias_adicionados` | jbjy-vk9h | "Days added" — extension semantics | Confirm whether it reflects amendments/extensions only |

**Flag totals: UNKNOWN 2 · AMBIG 9 · HUMAN 10 · GAP 19** (GAP refs are per-field population-gap markers, several shared across sibling fields).

*End of draft. Pending human validation (PR-06): nothing in this document is normative until accepted, and no OCDS mapping is decided until the P-05 ADR (Q-1 constraint).*

## 6. Storage and ingestion decisions (human-approved 2026-10-21)
1. **NIT normalization (Decision 1, 2026-10-21):** All NIT values are normalized as `text` in future storage models, regardless of the source dataset's declared type (`nit_entidad` is `number` in `jbjy-vk9h`, `text` in `p6dx-8zbt`). Never coerce NITs to numeric types.
2. **`urlproceso` special case (Decision 2, 2026-10-21):** Verified special-case field — the metadata type and the payload representation differ, and the payload carries a nested object structure. Ingestion rules must account for this structure; do not treat it as a plain text URL.
3. **Personal and sensitive data (Decision 3, 2026-10-21):** Fields flagged HUMAN-6 (bank-account data) and HUMAN-9 (personal data) must not be exposed without a dedicated ADR on personal and sensitive data exposure — added to the roadmap backlog; pending.
