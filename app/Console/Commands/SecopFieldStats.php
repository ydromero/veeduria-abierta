<?php

namespace App\Console\Commands;

use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Sleep;

/**
 * READ-ONLY field-evidence command (Data Ingestor charter §6 — Data Quality
 * Reporting; dictionary field-validation evidence package, PR-06 support).
 *
 * For one Tier A dataset field it produces, via anonymous SoQL queries on the
 * endpoints recorded in docs/ingestion-runbook.md §3:
 *  1. Total rows and non-null rows (null/absence rate) — SODA omits null fields
 *     from JSON payloads; SoQL count(column) counts non-null values, so this is
 *     the absence-rate measure.
 *  2. For number fields: min, max, avg.
 *  3. Distinct-value census (group-by), top N by row count, including sentinel
 *     values exactly as published (PR-06 — values are quoted, never imputed).
 *
 * Constitutional properties:
 *  - No database access, no writes of any kind (evidence gathering, not ingestion).
 *  - Anonymous access only; no credentials are read or sent (ADR-001a pt. 4, PR-14).
 *  - Only endpoints recorded in docs/ingestion-runbook.md §3 are contacted.
 *  - Throttled between queries (polite anonymous API citizen).
 *  - Output is machine-readable JSON suitable for citation in a DQ/evidence report.
 */
#[Signature('secop:fieldstats
    {dataset : Tier A dataset id (jbjy-vk9h or p6dx-8zbt)}
    {field : API fieldName to profile}
    {--group-limit=50 : How many top distinct values to report}
    {--group-fetch=1001 : Distinct values fetched before client-side top-N cut (use +1 to detect truncation)}')]
#[Description('Read-only SoQL field census (null rate, distinct values, sentinel rates) for a Tier A SECOP II field. No data writes.')]
class SecopFieldStats extends Command
{
    private const RESOURCE_URL = 'https://www.datos.gov.co/resource/%s.json';
    private const METADATA_URL = 'https://www.datos.gov.co/api/views/%s.json';

    /** Tier A datasets only (ADR-001a). Anything else is refused. */
    private const TIER_A = ['jbjy-vk9h', 'p6dx-8zbt'];

    /**
     * Sentinel literals as published by the platform (PR-06 — preserved verbatim,
     * never substituted by us). Matched case-sensitively against group values.
     */
    private const SENTINELS = [
        'No Definido', 'No definido', 'NO DEFINIDO',
        'No aplica', 'No Aplica', 'NO APLICA',
        'Sin Descripcion', 'Sin descripción', 'Sin Descripción',
        'No Adjudicado', 'No adjudicado',
    ];

    public function handle(): int
    {
        $dataset = (string) $this->argument('dataset');
        $field = (string) $this->argument('field');
        $groupLimit = max(1, (int) $this->option('group-limit'));
        $groupFetch = max($groupLimit, (int) $this->option('group-fetch'));

        if (! in_array($dataset, self::TIER_A, true)) {
            $this->error(json_encode([
                'error' => 'dataset_not_tier_a',
                'message' => 'Only Tier A datasets are allowed (ADR-001a): '.implode(', ', self::TIER_A),
                'dataset' => $dataset,
            ]));

            return self::FAILURE;
        }

        // Verify the field exists in live metadata and capture its published type
        // (unknown fields are errors, never guessed — PR-06).
        $metadata = $this->fetchJson(sprintf(self::METADATA_URL, $dataset));
        if ($metadata === null) {
            return self::FAILURE;
        }
        $liveType = null;
        foreach ($metadata['columns'] ?? [] as $column) {
            if (($column['fieldName'] ?? null) === $field) {
                $liveType = $column['dataTypeName'] ?? null;
                break;
            }
        }
        if ($liveType === null) {
            $this->error(json_encode([
                'error' => 'field_not_in_live_metadata',
                'message' => 'Field not found in live /api/views metadata — nothing invented (PR-06).',
                'dataset' => $dataset,
                'field' => $field,
            ]));

            return self::FAILURE;
        }

        Sleep::for(500)->milliseconds(); // be a polite anonymous API citizen

        // Query 1 — totals, null/absence rate, numeric summary (numbers only).
        $select = $liveType === 'number'
            ? "count(*) as total_rows, count({$field}) as non_null_rows, min({$field}) as min_value, max({$field}) as max_value, avg({$field}) as avg_value"
            : "count(*) as total_rows, count({$field}) as non_null_rows";
        $totals = $this->fetchJson(sprintf(self::RESOURCE_URL, $dataset).'?'.$this->buildQuery(['$select' => $select]));
        if ($totals === null) {
            return self::FAILURE;
        }

        Sleep::for(500)->milliseconds(); // be a polite anonymous API citizen

        // Query 2 — distinct-value census, client-side top-N by row count
        // (avoids any server-side alias-ordering assumption).
        $groupQuery = sprintf(self::RESOURCE_URL, $dataset).'?'.$this->buildQuery([
            '$select' => "{$field} as value, count(*) as row_count",
            '$group' => 'value',
            '$limit' => $groupFetch,
        ]);
        $group = $this->fetchJson($groupQuery);
        if ($group === null) {
            return self::FAILURE;
        }

        $totalRows = $totals[0]['total_rows'] ?? null;
        $nonNullRows = $totals[0]['non_null_rows'] ?? null;

        $census = collect($group)
            ->map(fn ($row) => [
                'value' => $row['value'] ?? null,
                'row_count' => (int) ($row['row_count'] ?? 0),
            ])
            ->sortByDesc('row_count')
            ->values();

        $sentinelCounts = $census
            ->filter(fn ($row) => in_array($row['value'], self::SENTINELS, true))
            ->values()
            ->all();

        $zeroRows = $census->firstWhere('value', '0');

        $result = [
            'run_at_utc' => now()->utc()->toIso8601String(),
            'mode' => 'read-only evidence gathering (no data writes)',
            'auth' => 'anonymous (ADR-001a pt. 4)',
            'dataset' => $dataset,
            'field' => $field,
            'live_dataTypeName' => $liveType,
            'full_dataset_aggregates' => [
                'total_rows' => $totalRows !== null ? (int) $totalRows : null,
                'non_null_rows' => $nonNullRows !== null ? (int) $nonNullRows : null,
                'null_or_absent_rows' => ($totalRows !== null && $nonNullRows !== null) ? (int) $totalRows - (int) $nonNullRows : null,
                'null_or_absent_rate' => ($totalRows !== null && $nonNullRows !== null && (int) $totalRows > 0)
                    ? round(((int) $totalRows - (int) $nonNullRows) / (int) $totalRows, 6)
                    : null,
                'min_value' => $totals[0]['min_value'] ?? null,
                'max_value' => $totals[0]['max_value'] ?? null,
                'avg_value' => $totals[0]['avg_value'] ?? null,
            ],
            'distinct_value_census' => [
                'fetch_limit' => $groupFetch,
                'distinct_values_returned' => $census->count(),
                'census_truncated' => $census->count() >= $groupFetch,
                'reported_top_n' => min($groupLimit, $census->count()),
                'top_values' => $census->take($groupLimit)->map(fn ($row) => [
                    'value' => $row['value'],
                    'row_count' => $row['row_count'],
                    'share_of_total' => ($totalRows !== null && (int) $totalRows > 0)
                        ? round($row['row_count'] / (int) $totalRows, 6)
                        : null,
                ])->all(),
            ],
            'sentinel_observation' => [
                'note' => 'Sentinel literals matched case-sensitively as published; values quoted verbatim (PR-06), never imputed.',
                'sentinels_observed_in_census' => $sentinelCounts,
                'sentinel_rows_in_census' => collect($sentinelCounts)->sum('row_count'),
                'zero_value_rows_in_census' => $zeroRows !== null ? $zeroRows['row_count'] : 0,
            ],
        ];

        $this->line(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        return self::SUCCESS;
    }

    /**
     * @param array<string, string> $params
     */
    private function buildQuery(array $params): string
    {
        return implode('&', array_map(
            fn ($key, $value) => $key.'='.urlencode($value),
            array_keys($params),
            $params,
        ));
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

            $this->error(json_encode([
                'error' => 'http_error',
                'status' => $response->status(),
                'url_host' => parse_url($url, PHP_URL_HOST),
                'attempt' => $attempt,
            ]));

            if ($attempt < 3) {
                Sleep::for(2)->seconds()->times($attempt); // 2s, 4s backoff
            }
        }

        return null;
    }
}
