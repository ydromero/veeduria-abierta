# Long-Term Vision — Open Oversight (Veeduría Abierta)

> **Status: Accepted baseline (ADR-017).** Accepted by the human as the baseline vision
> document of the broader initiative, with refinements applied: (1) mission language
> broadened beyond public spending to public action generally, with every domain expansion
> gated behind its own ADR; (2) governance anchored as an open network, with no formal
> membership or association structure assumed. See ADR-017 in `decision-log.md`.
> **Version:** 1.0 · **Drafted & accepted:** 2026-10-21 · **Editor:** Humans only.
>
> **Scope note.** This document describes the *initiative* (the larger purpose Veeduría
> Abierta serves beyond SECOP). It deliberately contains no software architecture. The
> Master Plan remains the authority on product scope and phases; if accepted, this
> document would sit beside it as the vision layer, and `master-plan.md` §2 would cite it.
>
> **Three pillars.** The initiative rests on three mutually reinforcing pillars:
> 1. **Civic infrastructure** — verifiable, trustworthy data and tools (the current platform).
> 2. **Civic education** — the *method* of citizen verification, taught and transferable (PR-01).
> 3. **Civic community** — a network of people who practice the method together and sustain it.

---

## 1. Mission

**Working statement (English).**

> To give Colombian citizens the data, the method, and the community they need to
> verify how public power acts — how it spends, legislates, and serves — so that
> oversight is something citizens *do*, not something they consume.

**Short form.** *Data, method, and community for citizen verification of public action.*

**Domains of application.** The mission is stated at the level of *public action* so it
outlives any single dataset. Its application expands by human decision, one domain at a
time, each with its own evidentiary grounding:

| Domain | Status |
|---|---|
| Public spending (procurement — SECOP II / OCDS) | **Current implementation focus** (Master Plan Phase 1) |
| Public programs and public services | Potential future domain — requires its own data grounding and human decision |
| Legislation and legislative activity | Potential future domain — requires its own data grounding and human decision |
| Citizen participation and other public information | Potential future domain — requires its own data grounding and human decision |

The domain list is illustrative, not a commitment. **Adding any domain requires its own
ADR** (ADR-017) covering, at minimum: source validation, reliability analysis, scope
definition, architectural impact assessment, and human approval (PR-15) — and must pass
the constitutional gates that already govern SECOP (immutable raw data, PR-05; nothing
invented, PR-06; graded honesty, PR-07; indicators, not accusations, PR-09).

**Relationship to the pillars.**
- Data and tools → **civic infrastructure** (what we build).
- The method of verification → **civic education** (what we teach — PR-01: "teach the method, not just the results").
- People practicing it together → **civic community** (what we sustain).

The mission deliberately stops short of "ending corruption". It aims at a *capability*:
a population that can check. Outcomes in the world (convictions, recovered funds,
deterrence) belong to institutions and to citizens themselves; the initiative's
accountable deliverable is verifiable evidence, transferable method, and a live community.

---

## 2. Vision

**Ten-year vision statement (working draft).**

> A Colombia where citizens routinely verify public action as part of civic life —
> where the question "how do we know?" is asked, and answerable, in every municipality —
> and where the tools, the method, and the community to answer it exist independently
> of any government, platform, or founder.

This expands the Master Plan's long-term vision ("to strengthen Colombian civic identity
through informed participation") into a testable end-state. The guiding phrase —
*"a view of Colombia that no one else shows you"* — remains the emotional frame:
not a louder opinion, but a vantage point others cannot offer.

**What success is not:**
- Not a mass-audience product with engagement metrics (PR-13: algorithms serve discovery, not attention).
- Not an accusation machine (PR-09: indicators, not accusations).
- Not a replacement for journalism, state oversight bodies, or existing civic organizations (PR-03: strengthen, don't reinvent).

---

## 3. Theory of Change

**If / then chain.**

- **IF** citizens have trustworthy, verifiable access to official public information — beginning with public procurement data (infrastructure),
- **AND** they are taught the method to interpret and re-verify it independently (education),
- **AND** they practice alongside a community that normalizes the habit of asking (community),
- **THEN** oversight becomes a distributed civic capability rather than a service provided by elites or platforms.
- **AND THEREFORE** public spending decisions face informed scrutiny everywhere, not only where journalists or state auditors happen to look — raising the perceived and actual cost of opacity, and strengthening civic identity through participation (Master Plan §2).

**Logic model (condensed).**

| Stage | Content (initiative-wide) |
|---|---|
| Inputs | Open official data (immutable, provenance-tracked); open-source tools; the documented verification method; volunteer and partner effort |
| Activities | Maintain verifiable infrastructure; publish plain-language explanations of method and limits (PR-08); teach verification workshops and materials; convene monitors, journalists, and developers; integrate with the existing ecosystem rather than duplicating it (PR-03) |
| Outputs | Citizens who can trace a public contract from publication to verification; educational materials freely reusable; investigations supported (ours and others'); reusable open tooling other organizations adopt |
| Outcomes | Verification becomes a shared civic habit; civic organizations adopt the method and tools; public institutions face consistent, evidence-based, non-accusatory scrutiny; the ecosystem's overall verification capacity grows |
| Impact | Stronger civic identity through informed participation; transparency norms that outlast any administration; a replication model other countries and sectors can reuse |

**Pre-conditions and honest risks (required by PR-08's spirit — limitations are part of the content).**
- Impact depends on state data quality and continued open publication (Level 1 sources).
- Visibility can create risk in vulnerable contexts (PR-11): community expansion in such territories is gated on safety and sovereignty pre-conditions, resolved by the human.
- Civic habits change slowly; the theory accepts a decade-scale horizon and measures capability-building, not virality.

---

## 4. Community Model

**Design constraints from the constitution:** procedural calls to action only, never accusatory
(PR-02); one-account-per-person identity integrity and labeled automation, never astroturfing
(PR-10); community features are excluded from Phase 1 of the product and only begin when the
human decides (Master Plan scope).

### 4.1 Three circles of participation

1. **Learners (citizens).** Come to understand how their municipality's money is spent.
   Their path: see → understand → verify → ask. The platform serves them evidence and
   method; the community serves them context and company. No passive consumption:
   every surface invites a procedural next step ("how to check this yourself"), never a conclusion (PR-02).
2. **Practitioners (monitors, journalists, researchers).** Use the forensic depth
   (filters, timelines, network views). They extend the initiative's reach: their
   investigations, built on verifiable data and method, are the multiplier. The initiative's
   duty to them: provenance they can cite (PR-05), reliability levels they can trust (PR-07),
   and exports that let third parties reproduce verification (PR-01).
3. **Builders (civic developers).** Reuse the open-source platform, data model, and method
   to build new civic tools (Master Plan §3). They turn the infrastructure into an
   ecosystem asset — the ultimate defense against single-platform dependence (PR-04).

### 4.2 Progression between circles

The community model is a *ladder of capability*: learners become practitioners by learning
the method; practitioners become builders by reusing the tools; builders teach learners.
Every transition is voluntary and skill-based, never gamified or engagement-driven (PR-13).

### 4.3 Community norms (mirror of the constitutional values)

- Evidence and method, never verdicts (PR-02, PR-09).
- Stated sources and limitations in every shared analysis (PR-07, PR-08).
- Authentic participation only: no coordinated accounts, no simulated citizen voices (PR-10).
- Allyship with the existing ecosystem: the community collaborates with, not against,
  BuscaSECOP, demoanalitica, MOE, Congreso Visible, and peers (PR-03).
- Every contribution carries provenance; every claim can be re-checked (PR-05, PR-06).

### 4.4 Moderation and safety

Moderation enforces the norms above, with two constitutional hard stops:
no content that asserts guilt about persons or companies (PR-09), and
no operation in vulnerable territories (e.g., Indigenous communities) until safety and
data-sovereignty pre-conditions are resolved by the human (PR-11).

---

## 5. Governance Principles

The initiative's governance is the mechanism that protects its values over decades
(P-04: built to outlast; PR-04). Working draft of the governing commitments:

1. **Human authority where it matters.** Vision, priorities, what is published, tone of
   voice, legal interpretation, partnerships, secrets, and publication remain human
   decisions (PR-15). AI and automation propose; humans decide.
2. **Independence from any administration.** No political or administrative alignment is
   ever a design or editorial criterion (PR-04). The initiative criticizes and
   acknowledges by the same evidentiary standard, whoever governs.
3. **Capture resistance.** Governance and funding structures are designed so no single
   patron, advertiser, or government can steer the method or the message. Any funding
   relationship that would create such leverage is declined.
4. **Openness as trust mechanism.** Code public (MIT), derivations documented, method
   reproducible (PR-14). Verifiability is only meaningful if anyone can run the check.
5. **Ecosystem allyship.** Formal commitment (PR-03) to scan, integrate, and strengthen
   existing civic tools before building; never compete with an existing public good.
6. **Constitutional continuity.** The constitutional layer (`principles.md`) governs the
   initiative, not only the software; its amendment procedure (human-only, explicit,
   never silent) applies initiative-wide.
7. **Distributed memory.** Knowledge lives in documents, not in people (PR-04): any
   newcomer — human, volunteer, or agent — can orient from the repository alone.
8. **Community voice grows with maturity.** As the community matures, consultative
   (non-binding) community input into priorities expands — but Tier E values and
   editorial authority remain human-held, per the constitutional amendment rules.
9. **Open network, not a formal membership body.** The initiative operates as an open
   network of contributors and allies. No formal association, membership structure, or
   representative body is assumed or created unless future community needs justify it
   through an explicit human decision (PR-15). This keeps participation low-barrier and
   avoids premature institutionalization (PR-04: structures are built to outlast, not
   built early).

---

## 6. Public Narrative

**Note (PR-15 / AGENTS.md language rules):** the *published* narrative is citizen-facing
content — it must be in plain Spanish and its final wording and tone are human decisions.
Below: the internal working statement (English) and a **draft Spanish rendering for your
approval**, not for publication.

**Internal working statement.**

> We believe oversight should be a habit, not a headline. Veeduría Abierta gives every
> Colombian the data, the method, and the company of others needed to check how public
> power acts — how it spends, legislates, and serves — in their municipality, with their
> own eyes, at their own pace. We
> don't tell anyone what to think; we show how to verify. We don't accuse; we surface
> risk indicators for human scrutiny. We don't replace anyone; we strengthen the
> organizations already doing this work. Everything we show can be re-checked, because
> trust in civic life should come from evidence, not authority.

**Draft Spanish rendering (plain language, pending human tone approval).**

> Creemos que la veeduría debe ser un hábito, no un titular. Veeduría Abierta le da a
> cada ciudadano los datos, el método y la compañía de otros para verificar cómo actúa
> el poder público — cómo gasta, legisla y sirve — en su municipio, con sus propios
> ojos, a su propio ritmo. No le
> decimos a nadie qué pensar; le mostramos cómo verificar. No acusamos; señalamos
> indicadores de riesgo para que una persona los examine. No reemplazamos a nadie;
> fortalecemos a las organizaciones que ya hacen este trabajo. Todo lo que mostramos se
> puede volver a verificar, porque la confianza en la vida pública debe venir de la
> evidencia, no de la autoridad.

**Narrative guardrails (binding when published):**
- Always pair claims with source and limitations (PR-07, PR-08).
- Vocabulary: *indicador de riesgo*, never accusation framing (PR-09).
- Every public communication is human-authored or human-approved; any AI assistance is
  labeled (PR-12, PR-15).
- The project's public name *Veeduría Abierta* is never translated (AGENTS.md).

---

## 7. Ten-Year Aspirations

Working draft — deliberately capability-oriented, not vanity-metric-oriented.

1. **Method as common knowledge.** The citizen verification method is documented,
   freely licensed, and taught (workshops, materials, partnerships with universities,
   schools, and journalism programs) — so it survives without the platform.
2. **Verified-by-habit municipalities.** In a meaningful number of municipalities,
   local people independently and routinely verify local contracts using the initiative's
   data and method, without prompting from the core team.
3. **The ecosystem is stronger because of us.** Partner organizations
   (journalism, transparency, academia) build on the infrastructure and method;
   PR-03's ecosystem test shows net strengthening, measurable in adoptions and integrations.
4. **A reproducible model.** The approach (immutable data + open method + community of
   practice) is documented and replicated in another country or another public sector,
   by people unaffiliated with the project.
5. **Institutional independence.** Governance, funding, and knowledge are structured so
   that the initiative continues unchanged if every founder and current contributor left —
   the permanence test (PR-04) passes at initiative scale.
6. **Norms shift.** "Show me the data" becomes an expected civic question in public
   procurement debates; public institutions routinely publish in verifiable,
   machine-readable form, partly because citizens and practitioners demanded it.
7. **Integrity intact.** After ten years, the initiative has never published an
   accusation, never simulated citizen voice, and never blurred official data with
   generated content — the Tier E principles (PR-05, PR-09, PR-10, PR-12) hold unbroken.
   This is the aspiration that makes all others credible.

---

## 8. What this document does NOT decide

Explicitly deferred to the human (PR-15):
- Adoption, amendment, or rejection of this draft (recorded in the Decision Log).
- The Spanish wording and tone of the public narrative (§6).
- When and whether community features enter the product (Master Plan scope).
- Funding and partnership strategy (capture-resistance principle §5.3 needs concrete policy).
- Any change to `master-plan.md` or `principles.md` to reference this document.
