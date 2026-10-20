# Proyecto maestro — Veeduría Abierta

*Documento vivo. Lo cambia solo el humano. Última revisión: 2026-10-19.*

## Propósito
Hacer que los datos de contratación pública colombiana (SECOP II) sean accesibles, comprensibles y verificables para cualquier ciudadano. El objetivo de fondo es educativo: enseñar el **método** de verificación, no solo mostrar resultados.

Visión de largo plazo: fortalecer la identidad cívica colombiana en torno a la participación informada. Frase guía: *"una vista de Colombia que nadie te muestra"*.

## Principios (no se negocian)
1. **Fortalecer, no reinventar ni enfrentar.** Antes de construir algo, se revisa qué ya existe (BuscaSECOP, demoanalitica/anticorrupcion.co, Congreso Visible, MOE) y se busca ser aliado.
2. **Dato oficial inmutable y separado** de la interpretación de IA, siempre etiquetada y auditable.
3. **Indicadores, no acusaciones.** Nunca se afirma culpabilidad de una persona o empresa.
4. **Transparencia sobre los límites.** Cada pantalla declara qué datos usa y su nivel de confiabilidad.
5. **Sin comportamiento inauténtico.** Nada de astroturfing ni cuentas coordinadas.
6. **Software público y abierto** (MIT), para que se pueda confiar y contribuir.
7. **Pensado para sobrevivir a cualquier colaborador** y no pertenecer al gobierno de turno.
8. El aprendizaje automático solo sirve para descubrimiento investigativo, nunca para maximizar interacción o polémica.

## Niveles de confiabilidad de los datos
- **Nivel 1 (confiable vía API de SECOP):** valor del contrato, fechas, estado, entidad, proveedor, modalidad.
- **Nivel 2 (disponible con vacíos):** fechas de terminación y liquidación, campo BPIN (validar completitud antes de usarlo).
- **Nivel 3 (no está en SECOP):** ejecución física (SPI/DNP), detalle presupuestal (SIIF), beneficiarios finales (RUB/DIAN).

## Alcance por fase
**Fase 1 — SECOP II (en curso).** Solo datos de nivel 1.
- Ingesta desde la API SODA (ADR-001/002/003) a tablas de *staging* con el dato crudo inmutable.
- Normalización a un modelo propio compatible con OCDS (por definir en `diccionario-datos.md`).
- Explorador de contratos con paginación y un tablero con indicadores básicos.
- Fuera de la fase 1: banderas rojas (P-01), cuentas de usuario, capa social, módulos de congreso.

**Fases posteriores (sin fecha, sujetas a decisión).**
- **Motor de banderas rojas (comprometido; fase por definir, P-01).** Indicadores de riesgo documentados uno por uno (fórmula, rutas OCDS, umbrales, límites y falsos positivos), siempre presentados como indicadores y nunca como acusaciones (ADR-008). Requiere validación humana de cada regla.
- Capa de grafos de entidades, contratos, contratistas y consorcios.
- Cruces con SIGEP II, Cuentas Claras y votaciones del Congreso.
- Registro razonado y consultable de los debates del Congreso (hueco que Congreso Visible y la MOE no cubren).
- Capa social ciudadana, una cuenta por persona (verificación por cédula: difícil, abierta).
- Módulo de alertas para territorios indígenas: **no se construye** sin resolver soberanía de datos, Convenio 169 / Ley 21 de 1991 y riesgos de seguridad.

## Stack
Ver ADR-004. Laravel 13 + Livewire 4 + Bootstrap 5 + PostgreSQL 18, todo en Docker con Sail. Sin JavaScript propio salvo lo indispensable.

## Quién hace qué
- **Humano (siempre):** visión, prioridades, decisiones, validación de interpretación legal y de datos, voz pública, relaciones con aliados, aprobación de ADRs, secretos, merges.
- **Agentes (proponen y ejecutan lo reversible y verificable):** investigación, borradores, código, pruebas, revisión, mantenimiento de documentos.
- El diseño detallado de roles y agentes vive en `.pi/agents/`.

## Documentos vivos
| Documento | Se actualiza cuando... |
|---|---|
| `proyecto-maestro.md` | cambia la visión o el alcance (humano) |
| `registro-decisiones.md` | se toma una decisión relevante |
| `AGENTS.md` | cambia una regla o convención (humano) |
| `diccionario-datos.md` (por crear) | se descubre algo de un campo o se agrega un dataset |
| `runbook-ingesta.md` (por crear) | cambia el proceso de carga o aparece un error nuevo |
| `CHANGELOG.md` (por crear) | cada cambio integrado |

El mapa completo de qué cambio toca qué documento estará en `.pi/memory/docs-map.md`.

## Referencias
Open Contracting Partnership · estándar OCDS · demoanalitica (CC BY 4.0) · BuscaSECOP · Decidim · MySociety · Ley 1712 de 2014.
