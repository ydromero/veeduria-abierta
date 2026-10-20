# Registro de decisiones (ADR) — Veeduría Abierta

Cada decisión técnica o de producto relevante se registra aquí. Una decisión no se borra: si cambia, se marca `Reemplazada` y se agrega una nueva que la referencia.

**Estados:** Aceptada · Pendiente · Reemplazada
**Quién decide:** el humano. Los agentes proponen y redactan, no aceptan ADRs.

---

## ADR-001 — Fuente de verdad: SECOP II vía API SODA
- **Estado:** Aceptada · 2026-10-19
- **Decisión:** los datos de contratación salen del SECOP II publicado en datos.gov.co (SODA). Datasets: `jbjy-vk9h` (contratos electrónicos) y `rpmr-utcd`.
- **Consecuencia:** no se reconstruye un visor de SECOP (ya existe BuscaSECOP). Principio rector: *fortalecer, no reinventar ni enfrentar*.

## ADR-002 — Paginación por cursor con `:id`
- **Estado:** Aceptada · 2026-10-19
- **Contexto:** `$offset` se degrada hacia los ~283.500 registros.
- **Decisión:** la ingesta usa paginación por cursor sobre el campo interno `:id` de Socrata, nunca `$offset` para cargas masivas.

## ADR-003 — Autenticación SODA3 con Key ID + Key Secret
- **Estado:** Aceptada · 2026-10-19
- **Decisión:** Basic Auth con Key ID y Key Secret (no `$$app_token`). Las credenciales viven solo en `.env`; ver ADR-009.

## ADR-004 — Versiones del stack fijadas
- **Estado:** Aceptada · 2026-10-19
- **Decisión:** se fija lo instalado en la sesión inicial y se actualiza más adelante, como tarea propia.
- **Versiones verificadas en esa sesión:** Laravel 13.37.0 · PHP 8.5 (imagen `sail-8.5/app`) · Livewire 4.4.7 · laravel/ui 4.6.3 con Bootstrap 5.3.8 instalada (constraint `^5.2.3` en `package.json`) · PostgreSQL 18 (`postgres:18-alpine`) · Vite 8.3.4 · Node 24 (verificado por el humano el 2026-10-20: Node v24.22.0 dentro del contenedor Sail; el Node del host, 22.x, no se usa).
- **Riesgo conocido:** la mayoría de los ejemplos y modelos conocen mejor Livewire 3. Toda sintaxis de Livewire se verifica contra el código en `vendor/livewire/livewire` o su documentación v4 antes de usarla.

## ADR-005 — Cola de trabajos con driver `database`
- **Estado:** Aceptada · 2026-10-19
- **Decisión:** `QUEUE_CONNECTION=database` (la tabla `jobs` ya está migrada). Sin Redis ni Horizon en la fase 1.
- **Verificado por el humano el 2026-10-20:** `.env` contiene `QUEUE_CONNECTION=database` (el agente no puede leer `.env`).
- **Revisar cuando:** el volumen de la ingesta histórica haga lenta la cola.

## ADR-006 — Pruebas contra la base `testing`
- **Estado:** Aceptada · 2026-10-19 (interpretación a confirmar)
- **Decisión:** las pruebas corren contra la base `testing` que crea Sail, nunca contra la base de desarrollo. Trait por defecto: `RefreshDatabase`.
- **Pendiente de verificar:** que `phpunit.xml` apunte a `pgsql` / `testing` y no a sqlite.

## ADR-007 — Dato oficial separado de la interpretación de IA
- **Estado:** Aceptada · 2026-10-19
- **Decisión:** el dato crudo oficial se guarda inmutable y separado de cualquier texto generado por un LLM. Todo contenido de IA se muestra etiquetado como tal y es auditable (qué modelo, qué versión, qué entrada).

## ADR-008 — Banderas rojas son indicadores de riesgo, no acusaciones
- **Estado:** Aceptada · 2026-10-19
- **Decisión:** ninguna pantalla, texto o documento afirma que una persona o empresa cometió un delito. Se habla de "indicador de riesgo". El motor de banderas rojas queda fuera de la fase 1, pero **está comprometido para una fase posterior** (confirmado 2026-10-20). Cuándo entra exactamente: ver P-01.

## ADR-009 — Repositorio público desde el día uno
- **Estado:** Aceptada · 2026-10-19
- **Decisión:** `git init` con licencia MIT. El primer commit solo se hace después de verificar que `.gitignore` excluye `.env`, `vendor/`, `node_modules/`, `venv/` y `storage` con datos.
- **Regla:** ningún secreto entra al repositorio ni a los documentos.

## ADR-010 — Puertos locales propios
- **Estado:** Aceptada · 2026-10-19
- **Contexto:** `sonnar-platform` ocupa 80, 5173, 5432 en la misma máquina.
- **Decisión:** en `.env`: `APP_PORT=8080`, `VITE_PORT=5174`, `FORWARD_DB_PORT=5433`. (Acción manual del humano: PI no puede editar `.env`.)
- **Verificado por el humano el 2026-10-20:** `.env` contiene los tres valores indicados (el agente no puede leer `.env`).

## ADR-011 — Agentes con modelos mixtos
- **Estado:** Aceptada · 2026-10-19
- **Decisión:** algunos agentes usan modelos de Claude y otros modelos económicos. El modelo de cada sesión se registra siempre (`model=` en la línea `METRICS`) para no confundir el efecto del agente con el del modelo.

## ADR-012 — La investigación es referencia, no fuente de verdad
- **Estado:** Aceptada · 2026-10-19
- **Decisión:** `docs/referencia/investigacion-plataformas-veeduria-secop.md` informa el diseño pero no manda. Donde difiere de un ADR, gana el ADR. Diferencias conocidas: usa `$offset` y `X-App-Token` (ADR-002/003), asume Livewire 3, Bootstrap 4, Redis/Horizon y reglas heredadas de Sonnar GPON (ADR-004/005). Sus cifras de mercado no se citan como hechos sin verificar la fuente.

---

## Pendientes (decisiones abiertas)
- **P-01** — ¿En qué fase entra el motor de banderas rojas? Ya está decidido que sí entra (ADR-008); falta definir cuándo.
- **P-02** — Nombre de la capa social/comunitaria (distinta de la plataforma editorial "Veeduría Abierta").
- **P-03** — ¿Los agentes pueden hacer commits? Por defecto: **no**; proponen y el humano confirma.
- **P-04** - ¿Se adopta Laravel Boost como fuente de documentación versionada para los agentes (riesgo Livewire 4)? Si se instala, puede reescribir AGENTS.md y CLAUDE.md.
