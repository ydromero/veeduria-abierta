<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;

/**
 * READ-ONLY pre-flight command (Data Ingestor charter §6 — schema-change detection gate).
 *
 * For each Tier A dataset (ADR-001a: jbjy-vk9h, p6dx-8zbt) it:
 *  1. Fetches live SODA metadata (/api/views/{id}.json) and compares the live
 *     fieldName + dataTypeName sets against the data-dictionary baseline
 *     (docs/data-dictionary.md §2 and §3, verified 2026-10-21).
 *  2. Fetches the live row count (SoQL $select=count(*)) and reports the delta
 *     against the ADR-001a authoritative counts (source-side growth only — NOT
 *     ingestion coverage; PR-08).
 *
 * Constitutional properties:
 *  - No database access, no writes of any kind (smoke validation constraint).
 *  - Anonymous access only; no credentials are read or sent (ADR-001a pt. 4, PR-14).
 *  - Only endpoints recorded in docs/ingestion-runbook.md §3 are contacted.
 *  - The field baseline below is transcribed verbatim from the data dictionary;
 *     nothing is invented (PR-06). Drift result: exit 0 = no drift, exit 1 = drift
 *     (hard stop on affected fields per charter §6).
 */
#[Signature('secop:preflight {--dataset=* : Restrict to specific Tier A dataset id(s)}')]
#[Description('Read-only schema-change pre-flight and completeness baseline for Tier A SECOP II datasets (no data writes).')]
class SecopPreflight extends Command
{
    private const METADATA_URL = 'https://www.datos.gov.co/api/views/%s.json';
    private const RESOURCE_URL = 'https://www.datos.gov.co/resource/%s.json';

    /**
     * ADR-001a authoritative counts (verified 2026-10-21) — baseline for
     * source-side growth deltas only, not an ingestion-coverage assertion.
     */
    private const AUTHORITATIVE_COUNTS = [
        'jbjy-vk9h' => 5644941,
        'p6dx-8zbt' => 9349940,
    ];

    /**
     * Dictionary baseline — docs/data-dictionary.md §2 (`jbjy-vk9h`, 95 fields),
     * verified 2026-10-21 against /api/views/jbjy-vk9h.json. fieldName => dataTypeName.
     * Transcribed verbatim; never invented (PR-06).
     */
    private const DICTIONARY_JBJY_VK9H = [
        'nombre_entidad' => 'text',
        'nit_entidad' => 'number',
        'departamento' => 'text',
        'ciudad' => 'text',
        'localizaci_n' => 'text',
        'orden' => 'text',
        'sector' => 'text',
        'rama' => 'text',
        'entidad_centralizada' => 'text',
        'proceso_de_compra' => 'text',
        'id_contrato' => 'text',
        'referencia_del_contrato' => 'text',
        'estado_contrato' => 'text',
        'codigo_de_categoria_principal' => 'text',
        'descripcion_del_proceso' => 'text',
        'tipo_de_contrato' => 'text',
        'modalidad_de_contratacion' => 'text',
        'justificacion_modalidad_de' => 'text',
        'fecha_de_firma' => 'calendar_date',
        'fecha_de_inicio_del_contrato' => 'calendar_date',
        'fecha_de_fin_del_contrato' => 'calendar_date',
        'condiciones_de_entrega' => 'text',
        'tipodocproveedor' => 'text',
        'documento_proveedor' => 'text',
        'proveedor_adjudicado' => 'text',
        'es_grupo' => 'text',
        'es_pyme' => 'text',
        'habilita_pago_adelantado' => 'text',
        'liquidaci_n' => 'text',
        'obligaci_n_ambiental' => 'text',
        'obligaciones_postconsumo' => 'text',
        'reversion' => 'text',
        'origen_de_los_recursos' => 'text',
        'destino_gasto' => 'text',
        'valor_del_contrato' => 'number',
        'valor_de_pago_adelantado' => 'number',
        'valor_facturado' => 'number',
        'valor_pendiente_de_pago' => 'number',
        'valor_pagado' => 'number',
        'valor_amortizado' => 'number',
        'valor_pendiente_de' => 'number',
        'valor_pendiente_de_ejecucion' => 'number',
        'saldo_cdp' => 'number',
        'saldo_vigencia' => 'number',
        'espostconflicto' => 'text',
        'dias_adicionados' => 'number',
        'puntos_del_acuerdo' => 'text',
        'pilares_del_acuerdo' => 'text',
        'urlproceso' => 'url',
        'nombre_representante_legal' => 'text',
        'nacionalidad_representante_legal' => 'text',
        'domicilio_representante_legal' => 'text',
        'tipo_de_identificaci_n_representante_legal' => 'text',
        'identificaci_n_representante_legal' => 'text',
        'g_nero_representante_legal' => 'text',
        'presupuesto_general_de_la_nacion_pgn' => 'number',
        'sistema_general_de_participaciones' => 'number',
        'sistema_general_de_regal_as' => 'number',
        'recursos_propios_alcald_as_gobernaciones_y_resguardos_ind_genas_' => 'number',
        'recursos_de_credito' => 'number',
        'recursos_propios' => 'number',
        'ultima_actualizacion' => 'calendar_date',
        'codigo_entidad' => 'number',
        'codigo_proveedor' => 'text',
        'fecha_inicio_liquidacion' => 'calendar_date',
        'fecha_fin_liquidacion' => 'calendar_date',
        'objeto_del_contrato' => 'text',
        'duraci_n_del_contrato' => 'text',
        'nombre_del_banco' => 'text',
        'tipo_de_cuenta' => 'text',
        'n_mero_de_cuenta' => 'text',
        'el_contrato_puede_ser_prorrogado' => 'text',
        'fecha_de_notificaci_n_de_prorrogaci_n' => 'calendar_date',
        'nombre_ordenador_del_gasto' => 'text',
        'tipo_de_documento_ordenador_del_gasto' => 'text',
        'n_mero_de_documento_ordenador_del_gasto' => 'text',
        'nombre_supervisor' => 'text',
        'tipo_de_documento_supervisor' => 'text',
        'n_mero_de_documento_supervisor' => 'text',
        'nombre_ordenador_de_pago' => 'text',
        'tipo_de_documento_ordenador_de_pago' => 'text',
        'n_mero_de_documento_ordenador_de_pago' => 'text',
        'documentos_tipo' => 'text',
        'descripcion_documentos_tipo' => 'text',
        'direcci_n_de_ejecuci_n_del_contrato' => 'text',
        'criterios_de_sostenibilidad_ambiental' => 'text',
        'requisitos_ambientales_en_las_especificaciones_t_cnicas' => 'text',
        'criterios_ambientales_en_la_evaluaci_n_de_las_ofertas' => 'text',
        'obligaciones_contractuales_o_poscontractuales_a_nivel_ambiental' => 'text',
        'uso_de_etiquetado_ecol_gico' => 'text',
        'obligaciones_posconsumo' => 'text',
        'fecha_inicio_obligaciones_posconsumo' => 'calendar_date',
        'fecha_fin_obligaciones_posconsumo' => 'calendar_date',
        'fecha_inicio_reversi_n' => 'calendar_date',
        'fecha_fin_reversi_n' => 'calendar_date',
    ];

    /**
     * Dictionary baseline — docs/data-dictionary.md §3 (`p6dx-8zbt`, 59 fields),
     * verified 2026-10-21 against /api/views/p6dx-8zbt.json. fieldName => dataTypeName.
     * Transcribed verbatim; never invented (PR-06).
     */
    private const DICTIONARY_P6DX_8ZBT = [
        'entidad' => 'text',
        'nit_entidad' => 'text',
        'departamento_entidad' => 'text',
        'ciudad_entidad' => 'text',
        'ordenentidad' => 'text',
        'codigo_pci' => 'text',
        'id_del_proceso' => 'text',
        'referencia_del_proceso' => 'text',
        'ppi' => 'text',
        'id_del_portafolio' => 'text',
        'nombre_del_procedimiento' => 'text',
        'descripci_n_del_procedimiento' => 'text',
        'fase' => 'text',
        'fecha_de_publicacion_del' => 'calendar_date',
        'fecha_de_ultima_publicaci' => 'calendar_date',
        'fecha_de_publicacion_fase' => 'calendar_date',
        'fecha_de_publicacion_fase_1' => 'calendar_date',
        'fecha_de_publicacion' => 'calendar_date',
        'fecha_de_publicacion_fase_2' => 'calendar_date',
        'fecha_de_publicacion_fase_3' => 'calendar_date',
        'precio_base' => 'number',
        'modalidad_de_contratacion' => 'text',
        'justificaci_n_modalidad_de' => 'text',
        'duracion' => 'number',
        'unidad_de_duracion' => 'text',
        'fecha_de_recepcion_de' => 'calendar_date',
        'fecha_de_apertura_de_respuesta' => 'calendar_date',
        'fecha_de_apertura_efectiva' => 'calendar_date',
        'ciudad_de_la_unidad_de' => 'text',
        'nombre_de_la_unidad_de' => 'text',
        'proveedores_invitados' => 'number',
        'proveedores_con_invitacion' => 'number',
        'visualizaciones_del' => 'number',
        'proveedores_que_manifestaron' => 'number',
        'respuestas_al_procedimiento' => 'number',
        'respuestas_externas' => 'number',
        'conteo_de_respuestas_a_ofertas' => 'number',
        'proveedores_unicos_con' => 'number',
        'numero_de_lotes' => 'number',
        'estado_del_procedimiento' => 'text',
        'id_estado_del_procedimiento' => 'number',
        'adjudicado' => 'text',
        'id_adjudicacion' => 'text',
        'codigoproveedor' => 'text',
        'departamento_proveedor' => 'text',
        'ciudad_proveedor' => 'text',
        'fecha_adjudicacion' => 'calendar_date',
        'valor_total_adjudicacion' => 'number',
        'nombre_del_adjudicador' => 'text',
        'nombre_del_proveedor' => 'text',
        'nit_del_proveedor_adjudicado' => 'text',
        'codigo_principal_de_categoria' => 'text',
        'estado_de_apertura_del_proceso' => 'text',
        'tipo_de_contrato' => 'text',
        'subtipo_de_contrato' => 'text',
        'categorias_adicionales' => 'text',
        'urlproceso' => 'url',
        'codigo_entidad' => 'number',
        'estado_resumen' => 'text',
    ];

    public function handle(): int
    {
        $requested = (array) $this->option('dataset');
        $datasets = array_keys(self::AUTHORITATIVE_COUNTS);
        if ($requested !== []) {
            $unknown = array_diff($requested, $datasets);
            if ($unknown !== []) {
                $this->error('Unknown dataset id(s): '.implode(', ', $unknown).'. Tier A datasets: '.implode(', ', $datasets));

                return self::FAILURE;
            }
            $datasets = $requested;
        }

        $driftFound = false;
        $summary = [];

        foreach ($datasets as $id) {
            $this->info("=== Dataset {$id} ===");

            $metadata = $this->fetchJson(sprintf(self::METADATA_URL, $id));
            if ($metadata === null) {
                $this->error("Metadata fetch failed for {$id} — pre-flight aborted (unknowns are errors, never placeholders; PR-06).");

                return self::FAILURE;
            }

            [$fields, $excludedSystem] = $this->extractLiveFields($metadata);
            $baseline = $id === 'jbjy-vk9h' ? self::DICTIONARY_JBJY_VK9H : self::DICTIONARY_P6DX_8ZBT;

            $this->line(sprintf(
                'Metadata: name=%s | rows (metadata)=%s | live non-system fields=%d (excluded %d system/subcolumns) | dictionary baseline=%d fields',
                $metadata['name'] ?? '(unnamed)',
                isset($metadata['rowsUpdatedAt']) ? 'n/a' : 'n/a',
                count($fields),
                $excludedSystem,
                count($baseline),
            ));

            $added = array_keys(array_diff_key($fields, $baseline));
            $removed = array_keys(array_diff_key($baseline, $fields));
            $retyped = [];
            foreach (array_intersect_key($fields, $baseline) as $fieldName => $liveType) {
                if ($baseline[$fieldName] !== $liveType) {
                    $retyped[$fieldName] = ['dictionary' => $baseline[$fieldName], 'live' => $liveType];
                }
            }

            $datasetDrift = $added !== [] || $removed !== [] || $retyped !== [];
            $driftFound = $driftFound || $datasetDrift;

            foreach ($added as $fieldName) {
                $this->warn("  [ADDED]   {$fieldName} ({$fields[$fieldName]}) — not in dictionary §".($id === 'jbjy-vk9h' ? '2' : '3'));
            }
            foreach ($removed as $fieldName) {
                $this->warn("  [REMOVED] {$fieldName} ({$baseline[$fieldName]}) — no longer in live metadata");
            }
            foreach ($retyped as $fieldName => $types) {
                $this->warn("  [RETYPED] {$fieldName}: dictionary={$types['dictionary']} live={$types['live']}");
            }
            if (! $datasetDrift) {
                $this->info('  Schema: NO DRIFT (field set and types match the dictionary baseline).');
            }

            $count = $this->fetchCount($id);
            if ($count === null) {
                $this->error("  Row-count fetch failed for {$id} — completeness baseline unavailable (logged, not guessed; PR-06).");
                $summary[$id] = [
                    'schema_drift' => $datasetDrift,
                    'added' => $added,
                    'removed' => $removed,
                    'retyped' => $retyped,
                    'live_count' => null,
                    'authoritative_count_2026_10_21' => self::AUTHORITATIVE_COUNTS[$id],
                    'delta_vs_authoritative' => null,
                ];

                continue;
            }

            $delta = $count - self::AUTHORITATIVE_COUNTS[$id];
            $this->line(sprintf(
                '  Rows: live count(*)=%s | ADR-001a authoritative (2026-10-21)=%s | delta=%+d (source-side growth only, NOT ingestion coverage)',
                number_format($count),
                number_format(self::AUTHORITATIVE_COUNTS[$id]),
                $delta,
            ));

            $summary[$id] = [
                'schema_drift' => $datasetDrift,
                'added' => $added,
                'removed' => $removed,
                'retyped' => $retyped,
                'live_count' => $count,
                'authoritative_count_2026_10_21' => self::AUTHORITATIVE_COUNTS[$id],
                'delta_vs_authoritative' => $delta,
            ];

            Sleep::for(500)->milliseconds(); // be a polite anonymous API citizen
        }

        $this->newLine();
        $this->line('=== MACHINE-READABLE SUMMARY (for the DQ report) ===');
        $this->line(json_encode([
            'run_at_utc' => now()->utc()->toIso8601String(),
            'mode' => 'read-only smoke validation (no data writes)',
            'auth' => 'anonymous (ADR-001a pt. 4)',
            'datasets' => $summary,
            'drift_found' => $driftFound,
        ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        if ($driftFound) {
            $this->error('SCHEMA DRIFT DETECTED — hard stop on affected fields (charter §6); a DRAFT dictionary proposal is required before any ingestion of them.');

            return self::FAILURE;
        }

        $this->info('Pre-flight complete: no schema drift; read-only; nothing written.');

        return self::SUCCESS;
    }

    /**
     * Extract non-system columns as fieldName => dataTypeName, and count the
     * excluded system/subcolumn entries (SODA exposes :id, :meta etc.).
     *
     * @return array{0: array<string, string>, 1: int}
     */
    private function extractLiveFields(array $metadata): array
    {
        $fields = [];
        $excluded = 0;
        foreach ($metadata['columns'] ?? [] as $column) {
            $fieldName = $column['fieldName'] ?? null;
            $type = $column['dataTypeName'] ?? null;
            if ($fieldName === null || $type === null) {
                $excluded++;

                continue;
            }
            if (str_starts_with($fieldName, ':') || str_starts_with($type, 'meta.') || ($column['subColumnId'] ?? null) !== null) {
                $excluded++;

                continue;
            }
            $fields[$fieldName] = $type;
        }

        return [$fields, $excluded];
    }

    private function fetchJson(string $url): ?array
    {
        for ($attempt = 1; $attempt <= 3; $attempt++) {
            $response = Http::timeout(30)
                ->withHeaders(['Accept' => 'application/json'])
                ->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            $this->warn(sprintf(
                '  HTTP %d from %s (attempt %d/3)%s',
                $response->status(),
                $url,
                $attempt,
                $attempt < 3 ? ' — retrying after backoff' : ' — giving up',
            ));

            if ($attempt < 3) {
                Sleep::for(2)->seconds()->times($attempt); // 2s, 4s backoff
            }
        }

        return null;
    }

    private function fetchCount(string $datasetId): ?int
    {
        $payload = $this->fetchJson(sprintf(self::RESOURCE_URL, $datasetId).'?$select=count(*)');
        if ($payload === null || ! isset($payload[0]['count'])) {
            return null;
        }

        return (int) $payload[0]['count'];
    }
}
