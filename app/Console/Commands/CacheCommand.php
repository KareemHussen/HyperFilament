<?php

namespace App\Console\Commands;

use App\Services\CacheService;
use Illuminate\Console\Command;

class CacheCommand extends Command
{
    protected $signature = 'cache:manage {action : The action to perform (clear|warm|stats)}';
    protected $description = 'Manage application cache (clear, warm up, or show stats)';

    public function handle()
    {
        $action = $this->argument('action');

        switch ($action) {
            case 'clear':
                $this->clearCache();
                break;
            case 'warm':
                $this->warmCache();
                break;
            case 'stats':
                $this->showStats();
                break;
            default:
                $this->error('Invalid action. Use: clear, warm, or stats');
                return 1;
        }

        return 0;
    }

    private function clearCache()
    {
        $this->info('Clearing all application cache...');
        CacheService::clearAllCache();
        $this->info('Cache cleared successfully!');
    }

    private function warmCache()
    {
        $this->info('Warming up application cache...');
        CacheService::warmUpCache();
        $this->info('Cache warmed up successfully!');
    }

    private function showStats()
    {
        $this->info('Cache Statistics:');
        $this->line('');

        $stats = CacheService::getDashboardStats();
        
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Companies', $stats['total_companies']],
                ['Total Drivers', $stats['total_drivers']],
                ['Total Vehicles', $stats['total_vehicles']],
                ['Total Trips', $stats['total_trips']],
                ['Active Trips', $stats['active_trips']],
                ['Completed Trips', $stats['completed_trips']],
            ]
        );
    }
}
