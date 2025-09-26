<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class AnalyzeQueryPerformance extends Command
{
    protected $signature = 'analyze:performance {--table= : Specific table to analyze}';
    protected $description = 'Analyze database query performance and suggest optimizations';

    public function handle()
    {
        $table = $this->option('table');
        
        if ($table) {
            $this->analyzeTable($table);
        } else {
            $this->analyzeAllTables();
        }
    }

    private function analyzeAllTables()
    {
        $this->info('Analyzing all tables for performance...');
        $this->line('');

        $tables = [
            'companies' => 'Company queries and filtering',
            'drivers' => 'Driver queries by company',
            'vehicles' => 'Vehicle queries by company',
            'cities' => 'City dropdown options',
            'areas' => 'Area queries by city',
            'trips' => 'Trip filtering and statistics',
            'packages' => 'Package weight calculations',
        ];

        foreach ($tables as $table => $description) {
            $this->analyzeTable($table, $description);
        }
    }

    private function analyzeTable($table, $description = null)
    {
        $this->info("Analyzing table: {$table}");
        if ($description) {
            $this->line("Purpose: {$description}");
        }
        $this->line('');

        try {
            // Get table statistics
            $stats = $this->getTableStats($table);
            
            if ($stats) {
                $this->table(
                    ['Metric', 'Value'],
                    [
                        ['Rows', number_format($stats['rows'])],
                        ['Data Size', $this->formatBytes($stats['data_length'])],
                        ['Index Size', $this->formatBytes($stats['index_length'])],
                        ['Total Size', $this->formatBytes($stats['data_length'] + $stats['index_length'])],
                    ]
                );
            }

            // Get existing indexes
            $indexes = $this->getTableIndexes($table);
            if (!empty($indexes)) {
                $this->line('');
                $this->info('Existing Indexes:');
                $this->table(
                    ['Index Name', 'Columns', 'Type', 'Unique'],
                    $indexes
                );
            }

            // Suggest optimizations based on table
            $this->suggestOptimizations($table);

        } catch (\Exception $e) {
            $this->error("Error analyzing table {$table}: " . $e->getMessage());
        }

        $this->line('');
    }

    private function getTableStats($table)
    {
        $result = DB::select("
            SELECT 
                table_rows as rows,
                data_length,
                index_length
            FROM information_schema.tables 
            WHERE table_schema = DATABASE() 
            AND table_name = ?
        ", [$table]);

        return $result[0] ?? null;
    }

    private function getTableIndexes($table)
    {
        $indexes = DB::select("
            SELECT 
                index_name,
                GROUP_CONCAT(column_name ORDER BY seq_in_index) as columns,
                index_type,
                non_unique
            FROM information_schema.statistics 
            WHERE table_schema = DATABASE() 
            AND table_name = ?
            GROUP BY index_name, index_type, non_unique
            ORDER BY index_name
        ", [$table]);

        return array_map(function ($index) {
            return [
                $index->index_name,
                $index->columns,
                $index->index_type,
                $index->non_unique ? 'No' : 'Yes'
            ];
        }, $indexes);
    }

    private function suggestOptimizations($table)
    {
        $suggestions = [
            'companies' => [
                'Add composite index on (industry, name) for filtering and sorting',
                'Add index on (name, email) for search operations',
                'Consider full-text search on name and address fields'
            ],
            'drivers' => [
                'Add composite index on (company_id, name) for company-based queries',
                'Add index on (email, phone) for unique constraint checks',
                'Add index on license_number for searches'
            ],
            'vehicles' => [
                'Add composite index on (company_id, name) for company-based queries',
                'Add index on plate_number for searches',
                'Add composite index on (weight, company_id) for weight-based sorting'
            ],
            'cities' => [
                'Add index on name for sorting and searching',
                'Consider full-text search if name searches are frequent'
            ],
            'areas' => [
                'Add composite index on (city_id, name) for city-based queries',
                'Add index on name for direct searches'
            ],
            'trips' => [
                'Add composite index on (status, start_date) for status filtering',
                'Add composite index on (company_id, status) for company-based queries',
                'Add composite index on (start_date, end_date) for date range queries',
                'Add composite index on (driver_id, status) for driver-based queries',
                'Add composite index on (vehicle_id, status) for vehicle-based queries',
                'Add composite index on (from_area, to_area) for area-based queries',
                'Add composite index on (created_at, status) for recent trips',
                'Add composite index on (start_date, status) for monthly statistics'
            ],
            'packages' => [
                'Add composite index on (trip_id, type) for trip-based queries',
                'Add composite index on (trip_id, weight) for weight calculations',
                'Consider partitioning by trip_id for large datasets'
            ]
        ];

        if (isset($suggestions[$table])) {
            $this->line('');
            $this->info('Suggested Optimizations:');
            foreach ($suggestions[$table] as $suggestion) {
                $this->line("• {$suggestion}");
            }
        }
    }

    private function formatBytes($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, 2) . ' ' . $units[$pow];
    }
}
