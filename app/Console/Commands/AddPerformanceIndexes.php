<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AddPerformanceIndexes extends Command
{
    protected $signature = 'indexes:add {--force : Force run even in production}';
    protected $description = 'Add performance indexes to optimize database queries';

    public function handle()
    {
        if (app()->environment('production') && !$this->option('force')) {
            $this->error('This command is not allowed in production without --force flag');
            return 1;
        }

        $this->info('Adding performance indexes...');
        $this->line('');

        // Run the migration
        $exitCode = Artisan::call('migrate', [
            '--path' => 'database/migrations/2025_01_27_000000_add_performance_indexes.php'
        ]);

        if ($exitCode === 0) {
            $this->info('✅ Performance indexes added successfully!');
            $this->line('');
            $this->info('Added indexes for:');
            $this->line('• Companies: industry filtering, name searching');
            $this->line('• Drivers: company-based queries, license searches');
            $this->line('• Vehicles: company-based queries, plate searches');
            $this->line('• Cities: name sorting and searching');
            $this->line('• Areas: city-based queries');
            $this->line('• Trips: status filtering, date ranges, company queries');
            $this->line('• Packages: trip-based queries, weight calculations');
            $this->line('• Users: email authentication');
            $this->line('');
            $this->info('Run "php artisan analyze:performance" to verify the indexes.');
        } else {
            $this->error('❌ Failed to add performance indexes');
            return 1;
        }

        return 0;
    }
}
