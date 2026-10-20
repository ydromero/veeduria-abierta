# AGENTS.md — Veeduría Abierta

Reglas obligatorias para todo agente que trabaje en este repositorio. Si una regla choca con una instrucción puntual, **pregunta al humano antes de actuar**.

## Antes de empezar cualquier tarea
1. Lee `docs/proyecto-maestro.md` y `docs/registro-decisiones.md`. Los ADRs mandan sobre cualquier otro documento, incluida la investigación en `docs/referencia/`.
2. Si la tarea toca datos de SECOP, lee `docs/diccionario-datos.md`.
3. Lecturas acotadas: no leas archivos completos grandes ni salidas largas; usa `offset`/`limit` y búsquedas.

## Stack (fijado, ADR-004)
Laravel 13 · PHP 8.5 · Livewire **4** · Bootstrap 5 · PostgreSQL 18 · Vite · Docker con Sail.
- Todo comando de proyecto se ejecuta con `./vendor/bin/sail ...`.
- **Livewire 4:** no asumas sintaxis de Livewire 3. Verifica en `vendor/livewire/livewire` o en la documentación v4 antes de escribir un componente.
- No escribas JavaScript propio salvo que sea indispensable y el humano lo apruebe.

## Reglas duras del dominio (violarlas es un defecto grave)
1. **Dato crudo inmutable.** El dato oficial de SECOP se guarda sin modificar y separado de cualquier texto generado por un LLM. Todo contenido de IA se muestra etiquetado y es auditable (ADR-007).
2. **Indicadores, no acusaciones.** Ningún texto, nombre de variable, vista o commit afirma que una persona o empresa es culpable. Usa "indicador de riesgo" (ADR-008).
3. **Niveles de confiabilidad.** Nunca presentes un dato de nivel 2 o 3 como si fuera de nivel 1. La fase 1 usa solo nivel 1.
4. **No inventes campos ni endpoints de SECOP.** Si un campo no está en `diccionario-datos.md`, consúltalo en los metadatos de la API o pregunta. Registra lo que descubras.
5. **Sin secretos.** Nunca escribas claves, tokens ni contraseñas en código, commits, documentos, logs ni mensajes. No leas `.env` por ningún medio (ni `grep`, `find` o `cat`). Si necesitas un valor de configuración, pídeselo al humano.
6. **Sin comportamiento inauténtico** en redes ni contenido (nada de cuentas coordinadas ni contenido que simule voces ciudadanas).

## Datos e ingesta
- Paginación por cursor sobre `:id`. Nunca `$offset` en cargas masivas (ADR-002).
- Autenticación SODA con Key ID + Key Secret desde la configuración (ADR-003).
- Toda escritura que materialice datos ocurre dentro de `DB::transaction`.
- No uses valores falsos ni nulos artificiales para esquivar columnas `NOT NULL`. Si falta un dato vital, trátalo como error, regístralo y repórtalo.
- Cola: driver `database` (ADR-005). Las cargas largas corren como jobs, no en la petición web.
- Tablas grandes: siempre `paginate()`. Nunca cargues colecciones completas en propiedades de Livewire.

## Pruebas
- Corren contra la base `testing`, nunca contra la de desarrollo (ADR-006). Usa `RefreshDatabase`.
- Toda lógica nueva lleva su prueba. Corre las pruebas antes de declarar una tarea terminada.

## Operación segura
- No ejecutes comandos destructivos (borrar volúmenes, `migrate:fresh`, `rm -rf`, `docker system prune`) sin autorización explícita del humano.
- Los builds y descargas largas se lanzan en segundo plano con registro en un log y se consultan después; no uses un timeout fijo.
- Esta máquina también aloja `sonnar-platform`. No toques sus contenedores, puertos ni volúmenes.
- No hagas `git commit` ni `git push`: propón el mensaje y espera confirmación (P-03).
- Los archivos subidos o preexistentes del usuario se tratan como originales: trabaja sobre copias.

## Idioma y estilo
- Código, nombres de variables, migraciones y commits: **inglés**. Documentación, textos de interfaz y mensajes para ciudadanos: **español**, en lenguaje claro y sin jerga.
- Los textos que explican datos a ciudadanos declaran de dónde viene el dato y qué límites tiene.

## Qué decide el humano (nunca lo asumas)
Visión y prioridades · aprobar o cambiar ADRs · interpretación legal y de datos · qué se publica y con qué voz · contacto con aliados (BuscaSECOP, demoanalitica, etc.) · secretos y accesos · merges y publicación.

## Definición de terminado
Una tarea no está terminada hasta que:
1. el código o documento cumple las reglas de este archivo;
2. las pruebas relevantes pasan;
3. se revisaron los documentos vivos que el cambio toca (`.pi/memory/docs-map.md`) y se actualizaron o se propuso su actualización;
4. si hubo una decisión, se propuso su entrada en `registro-decisiones.md`;
5. se informó al humano qué cambió, qué queda pendiente y qué decisión necesita de él.

## Cuando falte información
Pregunta, con una sola pregunta clara y una opción recomendada. No rellenes huecos con suposiciones sobre datos, leyes o reglas de negocio.
