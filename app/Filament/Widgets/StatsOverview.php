<?php

namespace App\Filament\Widgets;

use App\Services\CacheService;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $stats = CacheService::getDashboardStats();

        return [
            Stat::make('Total Companies', $stats['total_companies'])
                ->description('Registered companies')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('success'),

            Stat::make('Total Drivers', $stats['total_drivers'])
                ->description('Active drivers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('info'),

            Stat::make('Total Vehicles', $stats['total_vehicles'])
                ->description('Fleet vehicles')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning'),

            Stat::make('Active Trips', $stats['active_trips'])
                ->description('Currently in progress')
                ->descriptionIcon('heroicon-m-map-pin')
                ->color('success'),

            Stat::make('Completed Trips', $stats['completed_trips'])
                ->description('Successfully completed')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make('Total Trips', $stats['total_trips'])
                ->description('All time trips')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),
        ];
    }

    protected function getColumns(): int
    {
        return 3;
    }
}