<?php

namespace App\Filament\Widgets;

use App\Enums\TripStatus;
use App\Models\Area;
use App\Models\Trip;
use App\Models\Driver;
use App\Models\Company;
use App\Models\Vehicle;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Companies', Company::count())
            ->description('Active ' . Company::count())
            ->descriptionIcon('heroicon-m-building-office')
            ->color('info')
            ->chart([2, 4, 6, 8, 10, 12, 14]),
        
            Stat::make('Total Drivers', Driver::count())
                ->description('Number of Drivers')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('success')
                ->chart([5, 7, 10, 12, 15, 18, 20]),
            
            Stat::make('Total Vehicles', Vehicle::count())
                ->description('Number of Vehicles')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning')
                ->chart([3, 5, 7, 6, 9, 11, 13]),
            
            Stat::make('Total Trips This Month', Trip::lastThirtyDays()->inProgress()->count())
                ->description('Number of Trips this month')
                ->descriptionIcon('heroicon-m-truck')
                ->color('primary')
                ->chart([8, 10, 15, 18, 20, 25, 30]),
            
            Stat::make('Completed Trips This Month', Trip::lastThirtyDays()->where('status', TripStatus::DELIVERED)->count())
                ->description('Number of Completed Trips this month')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([2, 5, 8, 12, 15, 18, 22]),
            
            Stat::make('Number of Covered Areas', Area::count())
                ->description('Number of Covered Areas')
                ->descriptionIcon('heroicon-m-map')
                ->color('primary')
                ->chart([1, 2, 3, 4, 6, 8, 10]),


            // Stat::make('Active Trips', Trip::active()->count())
            //     ->description('Completed this month: ' . Trip::thisMonth()->completed()->count())
            //     ->descriptionIcon('heroicon-m-map-pin')
            //     ->color('primary')
            //     ->chart([2, 5, 3, 8, 4, 9, 6]),
        ];
    }
}
