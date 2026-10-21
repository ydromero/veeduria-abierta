# Principles — Open Oversight (Veeduría Abierta)

**Constitutional document.** Defines the values and non-negotiable commitments of the project and the rules by which every other artifact is governed.

- **Version:** 1.0 · **Status:** Accepted · **Adopted:** 2026-10-21 · **Accepted via:** ADR-014
- **Editor:** Humans only. Agents and contributors may propose amendments exclusively through the Decision Log (see §4).
- **Precedence:** This document is the apex norm on *values*. On *vision and scope detail*, `master-plan.md` governs. Conflicts between the two go to the human — never resolved by an agent (§1.3).

---

## 0. Preamble

Open Oversight is an open-source civic platform for the auditing, exploration, and forensic analysis of Colombian public procurement, beginning with SECOP II and standardizing on the Open Contracting Data Standard (OCDS). Its purpose is educational before it is technical: to democratize technical scrutiny of public money and to teach the **method** of citizen verification, so that the value survives any single platform, tool, or contributor.

Guiding phrase: *"a view of Colombia that no one else shows you."*

This document exists because principles scattered across several files drift apart silently. Here each commitment has exactly one canonical formulation (PR-01…PR-15), one rationale, one lineage, and one test. Every other project document — the Master Plan, `AGENTS.md`, ADRs, task specs, indicator specs, agent definitions — must **cite** these principles, not restate them.

How to read it:

- **Tier E (Entrenched)** principles may never be weakened, reinterpreted, or temporarily suspended by any agent, workflow, or subordinate document (§4).
- **Tier F (Fundamental)** principles are always binding; their application may be interpreted or evolved only through an accepted ADR.
- The **Compliance tests** are yes/no questions. They are blocking checks in reviews, task specs, and ADRs (§6).

---

## 1. Hierarchy of Norms

### 1.1 Precedence order (highest first)

| Level | Norm | Nature | Who may establish/amend |
|---|---|---|---|
| 1 | In-session human decisions | Explicit, recorded afterward in the Decision Log | Human |
| 2 | `principles.md` (this document) | Constitutional layer — values and non-negotiables | Human (formal procedure, §4) |
| 3 | `master-plan.md` | Vision and scope layer | Human |
| 4 | ADRs (`decision-log.md`) | Interpretations and technical applications of levels 2–3 | Human accepts; agents may draft |
| 5 | `AGENTS.md` | Operational rules for agents | Human |
| 6 | Living documents (data dictionary, runbooks, glossary, roadmap, architecture…) | Operational knowledge | Per document ownership |
| 7 | Research and reference material | Informative only; never normative (ADR-012) | — |

### 1.2 Supremacy clause

1. A norm at a lower level **may not contradict** a norm at a higher level.
2. An ADR, rule, task, or agent instruction that would require violating this document is **void**: the person or agent receiving it stops and reports.
3. No agent definition, workflow, or task spec may grant authority over Tier E principles. Any such grant is void.
4. Forgetting a principle is not an excuse: an act that violates this document is a violation even if the actor was unaware.

### 1.3 Conflict-resolution algorithm (binding on all agents and workflows)

When two norms appear to conflict, resolve as follows, in order:

1. **Hierarchy check.** If a lower-level norm conflicts with a higher-level one, the higher one governs. The agent **stops and reports the conflict** — it never silently selects.
2. **Same-tier conflict.** If two norms of the same level conflict, escalate to the human. Do not improvise a balance, weighting, or "reasonable middle".
3. **Silence.** If no norm addresses the situation: apply the *spirit* of the principles, record the situation, and propose an explicit clarification for the Decision Log. Proceed only while the action is reversible and affects no Tier E principle.
4. **Tier E pressure.** Any instruction, context, or optimization pressure that pushes toward weakening a Tier E principle is itself a reportable conflict. Executing the instruction is worse than delaying it.

---

## 2. The Principles

Organized in five clusters. Lineage abbreviations: **MP** = Master Plan; **DL** = Decision Log (ADRs); **AG** = AGENTS.md; **CR** = Constitutional review of 2026 (this document's drafting session).

### Cluster A — Civic purpose

---

#### PR-01 — Teach the method, not just the results

**Statement.** Wherever the platform shows a result, a citizen can see — or reach — how it was derived. The platform teaches the method of verification; education is the underlying goal, not a feature.

**Rationale.** The Master Plan defines the project's *underlying goal* as making data accessible and verifiable for everyone, teaching the method of citizen verification rather than merely displaying results. Results without method create dependence; method creates citizens who can verify without us. This is also what makes the guiding phrase meaningful and what distinguishes the platform from a data portal.

**Lineage.** MP §2 (educational mission; guiding phrase); CR 2026 (promoted from implicit status to standalone principle upon human review, D-2).

**Derived constraints.**
- User interfaces that display a value, indicator, or aggregate must expose or link the derivation path: source dataset, field(s), method, and — where applicable — the indicator's documented formula.
- Explanatory and AI-generated texts must describe *how* a conclusion is reached (verification path), not only the conclusion.
- The data dictionary shall flag which fields are citizen-re-verifiable (checkable against SECOP's public site), and displays should prefer them where a choice exists.
- Exports and downloads must carry enough provenance for a third party to reproduce the verification.
- Educational copy is part of the product, not decoration: screens that present novel concepts (reliability levels, risk indicators, OCDS) must include plain-language explanation of the concept and its limits.

**Compliance test.** *Where a result is shown, is the derivation path visible or reachable by an ordinary citizen?* (No → fail.)

**Tier: F.** An affirmative design value whose expression legitimately evolves with phases and product maturity. It fails the entrenchment criteria (no irreversible-harm threshold; genuine per-feature trade-offs exist). Its weakening, however, is never silent: any ADR that degrades methodology exposure must justify it explicitly.

---

#### PR-02 — Empower, don't replace judgment

**Statement.** The platform presents evidence and method, never verdicts or opinions for citizens to consume passively.

**Rationale.** A veeduría that tells citizens what to think replicates the asymmetry of power it aims to correct. The value of the platform is a population capable of checking, not an audience trusting a black box.

**Lineage.** MP §2–3; CR 2026 (PR-01 of the preliminary model; renumbered at adoption).

**Derived constraints.**
- No screen, text, or notification may conclude, recommend, or imply what citizens should believe or do about a person, company, or institution.
- Calls to action are procedural (how to verify, where to complain, how to read a dataset), never accusatory.
- Editorial framing is human-owned (PR-15): agents do not choose civic messaging.

**Compliance test.** *Does any output assert, recommend, or imply a conclusion a citizen should adopt, rather than evidence and a method to reach their own?* (Yes → fail.)

**Tier: F.** Binding, but its edge cases (educational emphasis vs. neutrality, curation of topics) are product judgments to be balanced through ADRs.

---

#### PR-03 — Strengthen, don't reinvent or confront

**Statement.** Before building anything, examine what already exists in the civic-transparency ecosystem and act as an ally; never build a competitor to an existing public good.

**Rationale.** The ecosystem (BuscaSECOP, demoanalitica/anticorrupcion.co, Congreso Visible, MOE, and others) has done foundational work. Duplicating it fragments civic attention and spends trust. The project's differentiation is the OCDS standardization and method transparency, not yet another viewer.

**Lineage.** MP §3 principle 1; ADR-001 (consequence: no new SECOP viewer).

**Derived constraints.**
- Every significant feature proposal includes an "ecosystem scan": what exists, and how this feature strengthens, reuses, or inter-operates with it.
- Integration-friendly choices (open formats, stable identifiers, documented APIs) are preferred over closed ones.
- No feature whose value proposition is "a better version of X", where X is an existing civic tool, without an explicit human decision (as with ADR-001's precedent).

**Compliance test.** *Was the existing ecosystem examined, and does the feature strengthen rather than duplicate or compete with it?* (No → fail.)

**Tier: F.** Requires situational judgment: what counts as "reinventing" depends on the ecosystem's state at each moment.

---

#### PR-04 — Built to outlast

**Statement.** Every design decision assumes the project will outlive its founders and remain independent of any current administration.

**Rationale.** Civic oversight platforms die when they are personalized. Permanence and independence are design inputs: institutional memory over individual memory, boring durable technology over fashionable fragile technology, funding-and-governance structures that no patron can capture.

**Lineage.** MP §3 principle 7.

**Derived constraints.**
- Documentation, memory systems, and onboarding are maintained so that any newcomer — human or agent — can orient without tribal knowledge.
- Avoid single points of human dependency: secrets, credentials, and critical knowledge are never held by one person alone where avoidable; knowledge lives in the documents, not in chat history.
- Technology choices favor long-term maintainability and staff independence over novelty.
- Political or administrative alignment is never a design criterion.

**Compliance test.** *If every current contributor and agent disappeared tomorrow, could the project continue from the repository alone?* (No → fail.)

**Tier: F.** A design orientation applied through architecture and documentation decisions.

---

### Cluster B — Epistemic integrity

---

#### PR-05 — Official data is inviolable

**Statement.** Raw official SECOP data is stored unmodified, physically separated from any generated content, and traceable to its source query. Nothing — agent, human, process, or migration — mutates it.

**Rationale.** This is the forensic foundation of the entire project. Every claim the platform makes must be re-checkable against what the state actually published, forever. One silent mutation destroys the audit trail — and with it, the platform's only unassailable defense: "check our sources."

**Lineage.** MP §3 principle 2; ADR-007; AG rule 1 (Core Domain Rules).

**Derived constraints.**
- Raw ingestion lands in immutable staging storage; updates from the source create **new records** (append-only), never overwrites.
- Any content derived or generated from the data (normalizations, translations, AI text, indicator scores) lives in physically separate storage, linked to — never mixed with — raw data.
- Every raw record preserves provenance: source dataset, retrieval query/pagination window, and retrieval timestamp.
- Migrations, backfills, and maintenance jobs are designed so that raw tables are structurally incapable of in-place content edits (append-only or equivalent enforcement).
- Ingestion write operations occur within a database transaction (AG, Data and Ingestion).

**Compliance test.** *Can every displayed or generated value be traced back to an immutable raw record, with provenance intact?* (No → fail.)

**Tier: E.** A single violation is irreversible and unrecoverable in credibility; no legitimate exception case exists.

---

#### PR-06 — Nothing is invented

**Statement.** No datum may be presented as official data without a traceable origin in a validated source. Unknown or missing data is treated as an error — logged, reported, never filled with placeholders. Derived values (aggregations, computations, features) are not invention when computed exclusively from validated fields, with the formula documented and the output labeled as derived. AI-generated content is not invention when labeled and auditable per PR-12. Invention is the *unlabeled, untraceable presentation of any value as data*.

**Rationale.** The platform's entire value proposition is factual integrity about public money: we do not make things up. A fabricated field, an assumed endpoint, an interpolated number that reaches citizens is functionally as damaging as a false accusation — it is a false statement by the platform. The distinction between *derivation* (legitimate) and *invention* (forbidden) is definitional, not discretionary: traceable origin, validated inputs, documented formula, honest labeling.

**Lineage.** AG rule 4 ("Do not invent SECOP fields or endpoints") and AG Data-and-Ingestion rule (no dummy values or artificial nulls to bypass constraints); ADR-012 (fields must come from the dictionary or API metadata); MP §3 principle 2; CR 2026 (promoted to Tier E upon human review, D-3, with the derivation carve-out written into the entrenched formulation).

**Derived constraints.**
- Only fields present in the validated data dictionary or verified against API metadata may be used; discovery of a new field updates the dictionary first, code second (AG rule 4).
- Missing critical data is an error state: log, report, and stop — never a placeholder, default value, or artificial null (AG Data and Ingestion).
- Every computed or aggregated value displayed anywhere carries, by construction, its formula (or a link to it) and its input lineage.
- Statistics cited in documents (market figures, counts) are not treated as facts unless their original source is verified (ADR-012).
- Agent definitions may not create pathways that allow untraceable values to enter any data layer.

**Compliance test.** *Does every datum and statistic presented have a traceable, validated origin — and are unknowns always treated as errors, never placeholders?* (No → fail.)

**Tier: E.** A single fabricated value reaching citizens causes irreversible credibility harm; under the refined formulation, no legitimate exception case exists. Entrenchment does not freeze the principle: ADRs may still *clarify* it (e.g., what counts as a "validated source" for a new dataset) — they may never *weaken* it.

---

#### PR-07 — Graded honesty

**Statement.** Every datum is shown with its source and reliability level. Level 2/3 data is never presented as Level 1. Phase 1 exposes Level 1 only.

**Rationale.** Data has different provenance quality, and pretending otherwise manufactures false confidence. Reliability levels (MP) are the project's epistemic contract with citizens: they can calibrate exactly how much to trust what they see.

**Lineage.** MP "Data Reliability Levels"; MP §3 principle 4; AG rule 3 (Reliability levels).

**Derived constraints.**
- Every display component that renders a datum carries its level and source by construction (component contract, not per-screen discipline).
- Level 2/3 data is never blended with Level 1 in the same display without explicit visual and textual distinction.
- Phase 1 features consume Level 1 sources only; introducing Level 2/3 requires an ADR that names the phase and the mitigation for the gaps.
- Aggregates inherit the lowest reliability level of their inputs.

**Compliance test.** *Are source and reliability level stated wherever data appears, and is level mixing visually and textually explicit?* (No → fail.)

**Tier: F.** Phase-dependent implementation will evolve (the human anticipates later phases); the *honesty* is constant, its mechanics are ADR-interpretable.

---

#### PR-08 — Limitations are part of the content

**Statement.** Every screen, export, and explanatory text states what the data cannot say.

**Rationale.** Transparency about limitations is a core principle of the Master Plan (§3 principle 4) — and it is what keeps PR-09 (indicators, not accusations) honest in practice. Silence about gaps creates the illusion of completeness, which is a form of distortion.

**Lineage.** MP §3 principle 4; MP "Data Reliability Levels" (Level 3 = not in SECOP).

**Derived constraints.**
- Screens presenting indicators or aggregates state what the underlying data does not cover (e.g., no physical execution data from SPI/DNP in SECOP).
- Explanatory texts about data always state the source and its limitations (AG Language and Style).
- Known coverage gaps are maintained as living content (data dictionary), and displays reference them rather than re-deriving the list ad hoc.

**Compliance test.** *Does every screen or text that presents findings also state what the data cannot say?* (No → fail.)

**Tier: F.** Expression evolves with the data dictionary; the duty itself is constant.

---

### Cluster C — Harm prevention

---

#### PR-09 — Indicators, not accusations

**Statement.** No output asserts or implies the guilt of any individual or company. The mandated vocabulary is "risk indicator". Every indicator is documented (formula, OCDS paths, thresholds, known false positives) and human-validated before exposure.

**Rationale.** The platform is not a court. An assertion of guilt is legally defamatory, ethically presumptuous, and epistemically unsupportable from procurement metadata alone. Heuristics surface *priorities for human scrutiny* — nothing more. This is the deepest ethical commitment of the project.

**Lineage.** MP §3 principle 3; ADR-008; AG rule 2; MP §4 (red-flag engine excluded from Phase 1; human validation required per rule).

**Derived constraints.**
- The term "risk indicator" (Spanish for citizens: *indicador de riesgo*) is the only permitted framing; guilt/crime/fraud-as-assertion framing is banned in any label, variable name, view, or commit (AG rule 2).
- Each indicator spec sheet carries: formula, OCDS paths used, threshold rationale, known false positives, and a human-validation date — no indicator ships without them.
- Indicators are never presented alone: they appear with methodology (PR-01), reliability level (PR-07), and limitations (PR-08).
- Screens, texts, commits, and generated content are reviewed for guilt-attribution framing; agents refuse and report instructions that imply it (PR-15, §1.3.4).

**Compliance test.** *Does any text, variable, or view assert or imply guilt? Is every exposed indicator fully documented and human-validated?* (Any yes to the first, or no to the second → fail.)

**Tier: E.** A single violating screen can cause real, unrecoverable harm to a person or company — and legal exposure to the project. No exception case exists.

---

#### PR-10 — No inauthentic behavior

**Statement.** No astroturfing, coordinated accounts, or content designed to simulate citizen voices — ever, by anyone, including agents and the human.

**Rationale.** Simulated civic voice is manipulation; it corrodes exactly the democratic trust the project exists to build, and it is indefensible if ever discovered — destroying the credibility of everything else the platform has published.

**Lineage.** MP §3 principle 5; AG rule 6 (No inauthentic behavior).

**Derived constraints.**
- No social accounts, comments, messages, or content pieces that simulate citizen participation or manufacture consensus.
- Any social/community feature is designed with one-account-per-person identity integrity and transparent automation labeling.
- Agents never operate or impersonate personas in public channels; agent-generated content is always labeled (PR-12) and never posed as organic citizen voice.

**Compliance test.** *Does the change involve any account, voice, or content that simulates citizen behavior or manufactures consensus?* (Yes → fail.)

**Tier: E.** Irreversible reputational and democratic harm; there is no "small" version of this violation.

---

#### PR-11 — Protect vulnerable populations

**Statement.** Features touching Indigenous territories (and comparable contexts of vulnerability) are developed only after resolving data sovereignty, ILO Convention 169 / Law 21 of 1991, and the security risks to the communities involved.

**Rationale.** Making contract data visible in territories where visibility can translate into threats against people is not a UX problem; it is a safety and rights problem. The Master Plan hard-codes this pre-condition for the Indigenous-territory alert module; the principle generalizes it.

**Lineage.** MP §4 (alert module for Indigenous territories: will not be developed without resolving data sovereignty, ILO Convention 169 / Ley 21 de 1991, and security risks).

**Derived constraints.**
- Task triage includes a vulnerability screen: does this feature surface data about (or locate risks within) vulnerable populations or territories?
- A positive screen is a hard stop until the pre-conditions are resolved by the human — agents never proceed by analogy or exception.
- Resolutions are recorded in the Decision Log before any related implementation task is created.

**Compliance test.** *Does the change affect Indigenous territories or similar contexts, and if so, are the pre-conditions formally resolved and recorded?* (Affects + unresolved → fail.)

**Tier: F.** The guardrail is absolute, but its resolution path is inherently a human process (legal interpretation, community consultation) that cannot be pre-hardcoded.

---

### Cluster D — Integrity of AI and machine reasoning

---

#### PR-12 — AI content is always labeled and auditable

**Statement.** All AI-generated content is visibly labeled for citizens, stored separately from official data, and traceable to the model, version, and inputs used.

**Rationale.** Generated content is legitimately useful (summaries, explanations, drafts) and legitimately dangerous (fluency mistaken for authority). The labeling+auditability pair is what lets the platform use AI without ever blurring the line between what the state published and what a model produced.

**Lineage.** MP §3 principle 2; ADR-007; ADR-011 (model per session recorded); AG rule 1 (immutable raw data / AI-generated content labeled and auditable).

**Derived constraints.**
- Storage separation is enforced at the schema level: AI-generated columns/tables are structurally distinct from raw-data tables and carry provenance metadata (model, version, input references/hashes).
- Citizen-facing surfaces label AI-generated content visibly and in plain language; the label is part of the content, not a footnote.
- The model used per session/agent is recorded in session metrics (ADR-011 discipline) so effects are never conflated between agents and models.
- Generated content never mutates raw data (PR-05) and never presents untraceable values as data (PR-06).

**Compliance test.** *Is all AI-generated content labeled for citizens and auditable (model, version, inputs), and stored separately from official data?* (No → fail.)

**Tier: E.** The core trust mechanism between official data and generated content; one silent blur between the two undermines every other guarantee.

---

#### PR-13 — Algorithms serve discovery, not attention

**Statement.** Machine learning and algorithmic features exist exclusively for investigative discovery and understanding — never to maximize engagement, dwell time, or controversy.

**Rationale.** Optimization pressure corrupts quietly: a "more engaging" dashboard gradually becomes an alarmist one, because alarm engages. The Master Plan hard-codes the purpose of ML to prevent the platform from drifting into the attention economy it was built to counteract.

**Lineage.** MP §3 principle 8.

**Derived constraints.**
- Feature proposals involving ML or personalization state the discovery value and explicitly rule out engagement-optimization metrics.
- Analytics/tracking dependencies (third-party trackers, recommendation feeds, engagement SDKs) are not introduced without an ADR that passes PR-13 review.
- Success metrics for algorithmic features are discovery-oriented (e.g., investigations supported, verifications completed), never engagement-oriented.

**Compliance test.** *Does the feature optimize anything other than discovery and understanding — e.g., engagement, retention, controversy?* (Yes → fail.)

**Tier: F.** Evolving technical judgments (what counts as "engagement optimization") belong in ADRs.

---

### Cluster E — Openness and governance

---

#### PR-14 — Open by default; secrets never

**Statement.** Code is public and MIT-licensed; data derivations are public and documented; secrets exist only as human-managed configuration and never enter any artifact. **The secrecy clause admits no exception pathway.**

**Rationale.** Openness is the project's trust mechanism (verifiability is only meaningful if the verifier can run the code) and its contribution model. The secrecy clause is absolute because a credential that reaches a public artifact is permanently exposed — git history does not forget — and because secret handling is a human domain (PR-15).

**Lineage.** MP §3 principle 6; ADR-009 (public repo from day one; no secrets in repo or documentation); ADR-003 (credentials only in configuration); AG rule 5 (no secrets anywhere — "serious defect"); AG Safe Operation.

**Derived constraints.**
- `.gitignore` exclusions (.env, vendor, node_modules, venv, storage) are maintained; nothing secret-adjacent enters commits, docs, logs, or messages (AG rule 5).
- Agents never read `.env` files by any means and never request or handle secret values; configuration reaches code only through typed config access, with key *names* (never values) documented by the human.
- Human verification precedes any initial public commit (ADR-009).
- Attribution and licensing of third-party data/content (demoanalitica CC BY 4.0, OCP materials) are maintained as part of openness.

**Compliance test.** *Do any credentials or secret material appear in any artifact — and is everything that should be public actually public and attributed?* (Secret present → fail.)

**Tier: F — with a hardened secrecy clause.** The openness dimension involves case-by-case judgment (partner contributions, data licensing). The secrecy clause is absolute in its own text: it admits no exception pathway, functions with Tier E force in practice (per AG rule 5), and may be amended only by explicit human decision.

---

#### PR-15 — Authority is human where it matters

**Statement.** Vision and priorities; approving or modifying ADRs; legal and data interpretation; what is published and the tone of voice; communication with partners; secrets and access credentials; merges and publication — these are human decisions. Agents propose, draft, and execute only reversible, verifiable tasks.

**Rationale.** This principle is the governance backstop: the mechanism that protects every other principle. An agent authorized to erode it would eventually erode all the others. Authority boundaries also make agents *safe to run* — predictable, reviewable, and reversible.

**Lineage.** MP "Who does what"; AG "Human Decisions (Never Assume)"; ADR framework (agents may draft, humans accept); P-03 (agents do not commit); AG Safe Operation (no destructive commands).

**Derived constraints.**
- Agent definitions enumerate their authority: what they may create/update, what they may only draft, what is read-only, what is forbidden — and every charter defers to this document on Tier E principles.
- Agents do not commit or push; they propose messages and wait for confirmation (P-03).
- Agents do not run destructive commands, touch other projects' infrastructure (sonnar-platform), or execute long/unreversible operations without explicit human authorization.
- Ambiguity about authority is resolved conservatively: ask the human (AG "When Information Is Missing" — one clear, specific question with a recommended course).

**Compliance test.** *Did the work stay within the actor's declared authority, with a human decision recorded at every gate the constitution and AGENTS.md require?* (No → fail.)

**Tier: E.** The backstop itself; allowing it to be eroded removes the mechanism that protects all other principles.

---

## 3. Constraint Matrices

Condensed binding map: which principles constrain each future surface. (Full per-surface check lists live with the respective templates; this matrix is the authority.)

### 3.1 Agents (`.pi/agents/*.md`)

| Constraint | Principle |
|---|---|
| Charter opens with mandatory pre-task reading: this document; lists the subset of principles governing the role | §1, PR-15 |
| Authority enumeration: create / draft-only / read-only / forbidden — no grant may cover Tier E principles | PR-15, §4 |
| Unknowns are errors and questions, never assumptions | PR-06 |
| No citizen-visible output without level/source labels | PR-07, PR-08 |
| Generated text only in labeled, auditable channels | PR-12 |
| Refuse and report guilt-attribution instructions | PR-09, §1.3.4 |
| Never read `.env` or handle secrets; never commit/push; no destructive commands | PR-14, PR-15 |

### 3.2 Workflows (`docs/workflows/*.md`)

| Constraint | Principle |
|---|---|
| Header field "Principles engaged" | §1 |
| Ingestion workflows carry mandatory gates: DB::transaction, append-only raw writes, provenance capture | PR-05 |
| Indicator-authoring workflow: formula, OCDS paths, threshold rationale, false positives, human validation — all blocking steps | PR-09 |
| Publication workflow ends in human sign-off on tone and framing | PR-15 |
| Review workflows apply the Constitutional Test (§6) as a blocking check | All |

### 3.3 Risk indicators (`docs/risk-indicators/*.md`)

| Constraint | Principle |
|---|---|
| "Risk indicator" vocabulary only; guilt framing banned | PR-09 |
| Spec sheet: formula, OCDS paths, threshold rationale, false positives, human-validation date | PR-09 |
| Inputs restricted to data-dictionary-validated fields | PR-06 |
| Outputs carry reliability level and "indicator, not verdict" disclaimer by construction | PR-07, PR-08, PR-09 |
| Formula exposed to citizens (or linked) | PR-01 |

### 3.4 AI-generated content

| Constraint | Principle |
|---|---|
| Stored separately from official data; never mutates raw tables | PR-05, PR-12 |
| Visibly labeled (citizen) + machine-readable provenance (model, version, inputs) | PR-12 |
| No assertions about persons/companies beyond sourced data | PR-09 |
| Explanations expose the verification path, not just conclusions | PR-01 |
| Model recorded per session/agent | PR-12, ADR-011 |
| ML features ranked by discovery value only | PR-13 |

### 3.5 Technical decisions (ADRs, architecture, dependencies)

| Constraint | Principle |
|---|---|
| Every ADR states its "Constitutional basis" (principles cited) | §1 |
| Data-model decisions preserve immutability (append-only raw layer; no destructive migrations of raw data) | PR-05 |
| Stack/tooling introduces no engagement-optimization dependencies | PR-13 |
| Repo hygiene: .gitignore, secrets, attribution continuously maintained | PR-14 |
| Vulnerability screen in triage; hard stop on PR-11 contexts | PR-11 |
| Docs/onboarding maintained so the project survives any contributor | PR-04 |

---

## 4. Amendment Procedure

**Entrenchment tiers.**

- **Tier E (Entrenched).** May never be weakened, reinterpreted, or temporarily suspended by any agent, workflow, or subordinate document. Amendment requires: (a) an explicit human statement that a constitutional principle is being changed, (b) a new ADR marked as amending the principle (never a silent edit), and (c) a corresponding update to `AGENTS.md`. There is no "minor exception" pathway.
- **Tier F (Fundamental).** Always binding. Application may be interpreted, balanced, or evolved through an accepted ADR. Agents may propose such ADRs; they never apply an interpretation without acceptance.

**Procedure.**

1. **Proposal.** Anyone (human or agent) proposes an amendment via the Decision Log, citing affected principle IDs and the concrete situation that motivated it.
2. **Acceptance.** Human-only, with the additional requirements for Tier E stated above.
3. **History.** Principles are never deleted or silently reworded. Amended principles keep an "Amended by" line pointing to the ADR; the original formulation remains visible in git history.
4. **Versioning.** This document carries a version number and last-revised date; bumps accompany accepted amendments.
5. **Interpretation register.** When the human resolves a dispute about a principle's *application*, the resolution is recorded as a dated note under the principle ("Interpretations: …"). Interpretations build case law; they never contradict the statement.

**Current register.** None (document in draft).

---

## 5. Compliance-Test Annex — full list

Each test is a yes/no blocking question applied in reviews, task specs, and ADRs. Where mechanically checkable (vocabulary searches, schema separation, provenance columns), the check should be automated; otherwise it is a review gate.

1. **Method test** (PR-01) — Where a result is shown, is the derivation path visible or reachable by an ordinary citizen?
2. **Verdict test** (PR-02) — Does any output assert, recommend, or imply a conclusion a citizen should adopt?
3. **Ecosystem test** (PR-03) — Was the existing ecosystem examined; does the feature strengthen rather than duplicate?
4. **Permanence test** (PR-04) — Could the project continue from the repository alone if every contributor vanished?
5. **Lineage test** (PR-05) — Can every displayed/generated value be traced to an immutable raw record with provenance?
6. **Invention test** (PR-06) — Does every datum/statistic have a traceable validated origin; are unknowns always errors, never placeholders?
7. **Reliability test** (PR-07) — Are source and reliability level stated wherever data appears; is level mixing explicit?
8. **Limitations test** (PR-08) — Does every presentation of findings state what the data cannot say?
9. **Indicators test** (PR-09) — Does any text/variable/view imply guilt; is every exposed indicator documented and human-validated?
10. **Authenticity test** (PR-10) — Does the change simulate citizen behavior or manufacture consensus?
11. **Vulnerability test** (PR-11) — Does the change affect vulnerable-population contexts; are pre-conditions resolved and recorded?
12. **Labeling test** (PR-12) — Is all AI-generated content labeled and auditable (model, version, inputs), stored separately from official data?
13. **Purpose test** (PR-13) — Does the feature optimize anything other than discovery and understanding?
14. **Secrecy test** (PR-14) — Do any credentials or secret material appear in any artifact?
15. **Authority test** (PR-15) — Did the work stay within declared authority, with human decisions recorded at every gate?

---

## 6. Constitutional Test (blocking checklist)

Applied to **every task spec, ADR, indicator spec, and PR** before approval. A single failure blocks approval; the failure is recorded with the reason.

- [ ] 1. Method — derivation path visible or reachable? *(PR-01)*
- [ ] 2. Verdict — no asserted/implied conclusions for passive adoption? *(PR-02)*
- [ ] 3. Ecosystem — strengthens, does not duplicate or compete? *(PR-03)*
- [ ] 4. Permanence — repository alone would suffice? *(PR-04)*
- [ ] 5. Lineage — all values traceable to immutable raw records? *(PR-05)*
- [ ] 6. Invention — all data validated and traceable; unknowns are errors? *(PR-06)*
- [ ] 7. Reliability — source and level stated; mixing explicit? *(PR-07)*
- [ ] 8. Limitations — what the data cannot say is stated? *(PR-08)*
- [ ] 9. Indicators — no guilt implication; documentation + human validation present? *(PR-09)*
- [ ] 10. Authenticity — no simulated citizen behavior? *(PR-10)*
- [ ] 11. Vulnerability — pre-conditions resolved where applicable? *(PR-11)*
- [ ] 12. Labeling — AI content labeled and auditable? *(PR-12)*
- [ ] 13. Purpose — discovery-only optimization? *(PR-13)*
- [ ] 14. Secrecy — no secrets in any artifact? *(PR-14)*
- [ ] 15. Authority — human gates respected and recorded? *(PR-15)*

---

## 7. Annex — Adoption Notes (for the Decision Log record)

1. **Origin.** Consolidates MP §3 (8 non-negotiables), AG core rules 1–6, and ADR-007/008/009/012 into a single canonical layer; those documents will be updated to *cite* these principles after acceptance (knock-on edits listed in the adoption ADR).
2. **Additions during drafting (human-approved):** PR-01 *Teach the method* promoted from implicit status (D-2 analysis); PR-06 *Nothing is invented* promoted to Tier E with the derivation carve-out written into its formulation (D-3 analysis); PR-14's secrecy clause hardened to "no exception pathway".
3. **ID mapping from the preliminary model (for auditability):** PR-01→PR-02 · PR-02→PR-03 · PR-03→PR-04 · PR-04→PR-05 · PR-05→PR-06 · PR-06→PR-07 · PR-07→PR-08 · PR-08→PR-09 · PR-09→PR-10 · PR-10→PR-11 · PR-11→PR-12 · PR-12→PR-13 · PR-13→PR-14 · PR-14→PR-15 · (new) PR-01.
4. **Tier E membership at adoption:** PR-05, PR-06, PR-09, PR-10, PR-12, PR-15.
5. **Renumbering window.** Principle IDs are permanent from acceptance onward; the amendment rules (§4) forbid renumbering after adoption.
