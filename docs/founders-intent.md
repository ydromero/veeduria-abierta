# Founder's Intent — Open Oversight (Veeduría Abierta)

> **Status:** Accepted (ADR-022) · **Version:** 1.0 · **Drafted & accepted:** 2026-10-21
> **Editor:** The founder, in their own voice. Agents may format, transcribe, and
> propose; they may never alter the meaning of this document (PR-15).
>
> **Position in the hierarchy of norms.** This document is **informative (level 7,
> `principles.md` §1.1)**. It records *why* the constitutional and vision layers exist —
> it does not create obligations, exceptions, or rights. **If anything in this document
> appears to conflict with `principles.md`, `master-plan.md`, `docs/vision.md`, or any
> accepted ADR, those documents win and this testimony yields.** No future reader,
> contributor, or agent may cite this document to weaken a principle, reopen a decided
> question, or grant the founder — or anyone — authority the constitution does not grant.
>
> **A note on fallibility.** This is human testimony: memories are imperfect, judgments
> were made at a point in time, and the founder may one day disagree with their past
> self. That is acceptable. The document's value is context and conviction, not
> authority. Where memory and constitution disagree, only the constitution is normative.

---

## 1. Why this initiative exists

The project did not begin with SECOP itself. It began with a recurring feeling of
powerlessness.

I became increasingly frustrated by seeing public resources managed poorly, by social
inequality, by political spectacle that often distracts from real problems, and by the
feeling that as a society we spend more time complaining about what is wrong than
building tools that help us do something about it.

At some point I realized that transparency alone is not enough. Data can be public and
still be inaccessible in practice. Colombia publishes procurement data openly; that did
not, by itself, make anyone more able to see how their municipality's money is spent.

I started this initiative because I believe citizens should have practical ways to
understand what public institutions are doing, verify information for themselves, and
participate more meaningfully in public life.

I do not expect the project to eliminate corruption. I hope it can reduce the conditions
that allow it to thrive by making verification easier and more accessible. This is why
the mission is deliberately stated as a *capability* — a population that can check — and
not as an outcome the project cannot deliver or honestly claim
(`docs/vision.md` §1, PR-02).

## 2. The civic problems that motivated it

Four problems, as I experienced them:

1. **Asymmetric visibility.** Journalists, auditors, and civil-society organizations can
   only look at a fraction of the country at any given time. Opacity is structurally
   cheap because scrutiny is scarce; most municipalities are effectively unwatched.
2. **Public data that is not practically accessible.** The problem was never that the
   information does not exist — it is that an ordinary citizen cannot read, interpret,
   or re-verify it. The gap is interpretive, not informational.
3. **Dependence on intermediaries.** Citizens receive conclusions — from news, reports,
   social media — that they have no way to check. This reproduces the exact asymmetry of
   power the project exists to correct; a platform that simply produced better-sounding
   conclusions would reproduce it too (PR-02).
4. **Complaining without building.** The civic energy around public problems flows
   mostly into outrage. Very little of it flows into tools, methods, and habits that let
   people *do* something verifiable. This project is my answer to that imbalance.

## 3. What citizens are currently missing

- **The method.** Portals show results; nobody teaches how to check them. This is why
  "teach the method, not just the results" is a principle and not a feature (PR-01).
- **Calibrated trust.** Citizens cannot currently tell *how much* to believe what they
  read. Reliability levels and stated limitations (PR-07, PR-08) are my answer to that:
  false confidence is as corrosive as misinformation.
- **Permission and company.** Some citizens already want to participate and lack tools,
  knowledge, confidence, or community. Others have come to believe participation is
  simply not for them. Part of this initiative's work is providing practical tools; an
  equally important part is demonstrating that participation is possible, useful, and
  worth the effort. The goal is not only to support existing civic engagement, but to
  cultivate it (`docs/vision.md` §3–4).
- **A procedural next step.** After seeing something suspicious, a citizen has nowhere
  procedural to go. Calls to action here are always procedural — how to verify, where to
  complain, how to read a dataset — never accusatory (PR-02, PR-09).

## 4. What I hope future contributors will understand

**The rules are not bureaucratic caution. They are foresight — and some of them are
protections against the project's own temptations.** Three risks I specifically fear
future contributors may erode, and the distinctions I ask you never to lose:

1. **Do not turn this into an accusation machine.** Heuristics surface priorities for
   human scrutiny — nothing more. The platform is not a court, and one defamatory
   screen could destroy everything else it has ever published (PR-09).
2. **Do not prioritize attention and visibility over rigor and evidence.** Alarm
   engages; an "engaging" dashboard quietly becomes an alarmist one. Algorithms here
   serve discovery and understanding only (PR-13).
3. **Do not slowly replace citizen verification with pre-packaged conclusions.** I want
   this project to help people think, not think for them (PR-01, PR-02).

**The distinction I never want lost: evidence, interpretation, and judgment are three
different things.** Official data is evidence (PR-05, PR-06); derived values and
indicators are interpretation (labeled, documented, graded — PR-07, PR-12); what to do,
believe, or prosecute is judgment — and that belongs to citizens and institutions, not
to us. Much of the constitution exists to keep those three layers from merging.

Two more things I hope you understand:

- **Humility toward the ecosystem.** BuscaSECOP, demoanalitica, MOE, Congreso Visible
  and peers did foundational work before us. Our differentiation is standardization and
  method transparency — not being a better viewer of what they already show (PR-03,
  ADR-001).
- **AI has a narrow, deliberate contract here.** AI assistance is what makes an effort
  of this scale sustainable with few people. It is welcome — labeled, auditable,
  separated from official data, and never positioned to decide anything that matters
  (PR-12, PR-15). Future contributors will arrive in an era where these lines are drawn
  nowhere else; here they are drawn, and they are drawn for reasons.

## 5. What must survive change — and what should not

### 5.1 What must survive technologies, leaders, models, and contributors

- **Immutable official data with provenance.** The forensic foundation. Every claim the
  platform makes must remain re-checkable against what the state actually published,
  forever (PR-05). One silent mutation destroys the project's only unassailable defense:
  "check our sources."
- **The epistemic contract.** Graded honesty (PR-07) and stated limitations (PR-08) —
  trust in civic life should come from evidence, not authority.
- **Human authority at the gates** (PR-15). Including that AI and automation propose and
  never decide. This must survive model generations that do not yet exist.
- **Independence.** No political, funder, or platform alignment — ever (PR-04, and
  governance §5 of `docs/vision.md`).
- **Distributed memory.** Knowledge lives in documents, not people. The survival
  criterion is the permanence test: *if every current contributor vanished tomorrow,
  could the project continue from the repository alone?* (PR-04.)
- **The mission at the level of public action, not a dataset.** SECOP is the first
  implementation, not the definition (ADR-017, `docs/vision.md` §1).
- **Integrity unbroken.** Never an accusation, never a simulated citizen voice, never a
  blurred line between official data and generated content (PR-05, PR-09, PR-10, PR-12).
  After any number of years, this record intact is the achievement that makes all
  others credible.

### 5.2 What should NOT survive

Naming what is disposable protects what is not:

- The current technology stack (ADR-004 fixes it *for now*, deliberately — it is not
  sacred).
- The current product scope and phase plan.
- The current leadership structure, including my own role in it.
- Any dataset, partner, or integration that stops being the best available source.

If a future contributor replaces any of these while keeping §5.1 intact, they are
honoring this document, not betraying it.

## 6. The risks that could corrupt the mission

The risk I fear most is **mission capture**: that individuals, organizations, political
actors, or even future contributors could attempt to appropriate the project — use it to
advance their own agendas, or transform it into an instrument of pressure, intimidation,
harassment, or political conflict. I want this to remain a civic tool. Capture does not
always announce itself; it arrives as funding, partnership, community growth, or a
"pragmatic exception." The capture-resistance commitments (`docs/vision.md` §5.3) and
PR-04 exist because of this fear. Every contributor should treat any attempt to steer
the method or the message as an existential threat, not a negotiation.

The others, in the order I think about them:

1. **Accusation creep** — the pressure to name names, especially when a story is
   *probably* true (PR-09).
2. **Engagement drift** — optimizing for attention until alarmism looks like success
   (PR-13).
3. **Pre-packaged conclusions** — convenience quietly replacing the method the platform
   exists to teach (PR-01, PR-02).
4. **AI blur** — generated content gradually mixing with official data (PR-05, PR-12).
5. **Inauthentic scale** — astroturfed civic voice as the community layer grows; the
   most tempting violation precisely when the project is succeeding (PR-10).
6. **Constitutional erosion by a thousand reasonable exceptions** — there is no
   exception pathway, and that is the point (`principles.md` §1.3, §4).
7. **Safety blindness** — visibility translating into physical risk for people in
   vulnerable territories; the hard stop in PR-11 must never be reasoned around.
8. **Founder capture** — including *my own*. This document must never become a veto
   channel. If my testimony and the constitution ever diverge in a future reader's
   hands, the constitution wins.

## 7. What success means beyond software

- **The method survives the platform.** If the citizen verification method is documented,
  freely licensed, and taught widely enough to outlive this software, the project
  succeeded even if the software dies (`docs/vision.md` §7.1).
- **Verified by habit.** Local people routinely verifying local contracts without
  prompting from the core team (`docs/vision.md` §7.2).
- **The ecosystem is stronger because of us** — measured in adoptions and integrations,
  not in comparisons (PR-03).
- **A reproducible model** — replicated by unaffiliated people, in another country or
  sector (`docs/vision.md` §7.4).
- **"Show me the data" as a normal civic question** — a norm shift, not virality.
- **Integrity intact.** After ten years: no accusation ever published, no simulated
  citizen voice ever used, no official datum ever blurred with generated content
  (`docs/vision.md` §7.7).
- **Explicitly not success:** engagement metrics, an accusation machine, replacing
  journalism, state oversight bodies, or existing civic organizations (PR-02, PR-03,
  PR-09, PR-13).

I measure success in *capability built*, not in attention captured. Civic habits change
slowly; I accept a decade-scale horizon (`docs/vision.md` §3).

## 8. Founder assumptions (stated honestly, for future stress-testing)

These are the beliefs this project rests on. They are assumptions, not facts; future
contributors are entitled — indeed expected — to test them against evidence, through the
constitution's amendment procedure.

1. Public data, made verifiable and teachable, changes behavior.
2. The method of citizen verification is teachable and transferable — across domains,
   countries, and generations of technology.
3. Citizens are not apathetic by nature: some lack tools, knowledge, confidence, or
   community; others have been taught that participation is not for them. Both can be
   reached (§3 above).
4. Openness is itself a trust mechanism — verifiability only means something if anyone
   can run the check (PR-14).
5. Credibility requires being equally critical of every administration (PR-04).
6. A capability-building, decade-scale horizon is acceptable, and slow visible progress
   is not failure.
7. AI assistance is necessary to sustain this effort with few people — and safe only
   under the hard lines of PR-12 and PR-15.

If evidence someday shows one of these to be wrong, the honest response is to adapt the
*approach* through an ADR — never to quietly abandon the values underneath (§5.1).

---

## 9. Relationship to the constitutional layer

This document **cites** `principles.md` (PR-01…PR-15), `master-plan.md`, `docs/vision.md`,
and the Decision Log; it **restates nothing as normative**. When it quotes a principle's
spirit, the canonical text in `principles.md` governs. This document amends nothing,
creates no authority, and binds no one. It asks future contributors to understand — and
then it steps aside.

*— The founder, 2026-10-21. Adopted as an informative-layer document via ADR-022
(`decision-log.md`): it creates no norms and yields to all higher layers.*
