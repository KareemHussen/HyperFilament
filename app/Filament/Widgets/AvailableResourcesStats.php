<?php

namespace App\Filament\Widgets;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Company;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;
use App\Support\Widgets\CacheableWidget;

class AvailableResourcesStats extends StatsOverviewWidget
{
    use CacheableWidget;
    protected static  bool $isDiscovered  = false;
    protected int | string | array $columnSpan = 'full';

    public $startDate = null;
    public $endDate = null;
    public $hasSearched = false;

    protected function getStats(): array
    {
        if (!$this->hasSearched || !$this->startDate || !$this->endDate) {
            return [
                Stat::make('Available Drivers', 0)
                    ->description('Select time range to view')
                    ->descriptionIcon('heroicon-m-users')
                    ->color('info'),

                Stat::make('Available Vehicles', 0)
                    ->description('Select time range to view')
                    ->descriptionIcon('heroicon-m-truck')
                    ->color('warning'),

                Stat::make('Total Available', 0)
                    ->description('Select time range to view')
                    ->descriptionIcon('heroicon-m-check-circle')
                    ->color('success'),

                Stat::make('Companies', 0)
                    ->description('Select time range to view')
                    ->descriptionIcon('heroicon-m-building-office')
                    ->color('gray'),
            ];
        }

        $cacheKey = md5(json_encode([
            'start' => $this->startDate,
            'end' => $this->endDate,
        ]));

        [$drivers, $vehicles, $companies] = $this->rememberWidget("available_stats:{$cacheKey}", 300, function () {
            $drivers = $this->getAvailableDrivers()->count();
            $vehicles = $this->getAvailableVehicles()->count();
            $companies = collect()
                ->concat($this->getAvailableDrivers()->pluck('company.name'))
                ->concat($this->getAvailableVehicles()->pluck('company.name'))
                ->unique()
                ->filter()
                ->count();
            return [$drivers, $vehicles, $companies];
        });

        return [
            Stat::make('Available Drivers', $drivers)
                ->description('Ready for assignment')
                ->descriptionIcon('heroicon-m-users')
                ->color('info')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Available Vehicles', $vehicles)
                ->description('Ready for trips')
                ->descriptionIcon('heroicon-m-truck')
                ->color('warning')
                ->chart([3, 8, 15, 12, 14, 7, 9]),

            Stat::make('Total Available', $drivers + $vehicles)
                ->description('Combined resources')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success')
                ->chart([5, 10, 8, 15, 12, 9, 14]),

            Stat::make('Companies', $companies)
                ->description('With available resources')
                ->descriptionIcon('heroicon-m-building-office')
                ->color('gray')
                ->chart([2, 4, 6, 3, 8, 5, 7]),
        ];
    }

    protected function getAvailableDrivers()
    {
        if (!$this->startDate || !$this->endDate) {
            return collect();
        }

        return Driver::query()
            ->with('company')
            ->where('is_active', true)
            ->where('is_available', true)
            ->whereDoesntHave('trips', function ($query) {
                $query->where(function ($q) {
                    $q->where('start_date', '<=', $this->endDate)
                      ->where('end_time', '>=', $this->startDate);
                });
            })
            ->get();
    }

    protected function getAvailableVehicles()
    {
        if (!$this->startDate || !$this->endDate) {
            return collect();
        }

        return Vehicle::query()
            ->with('company')
            ->where('is_active', true)
            ->where('is_available', true)
            ->whereDoesntHave('trips', function ($query) {
                $query->where(function ($q) {
                    $q->where('start_date', '<=', $this->endDate)
                      ->where('end_time', '>=', $this->startDate);
                });
            })
            ->get();
    }

    #[On('updateStatsWidget')]
    public function updateData($startDate, $endDate, $hasSearched)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->hasSearched = $hasSearched;

        // Refresh the widget
        $this->dispatch('$refresh');
    }
}
