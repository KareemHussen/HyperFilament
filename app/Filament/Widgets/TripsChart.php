<?php

namespace App\Filament\Widgets;

use App\Enums\TripStatus;
use App\Models\Trip;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;
use App\Support\Widgets\CacheableWidget;

class TripsChart extends ChartWidget
{
    use CacheableWidget;
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'Monthly Trips Overview';
    }

    protected function getData(): array
    {
        $year = now()->year;
        $months = $this->rememberWidget("trips_chart:{$year}", 600, function () use ($year) {
            return collect(range(1, 12))->map(function ($month) use ($year) {
                return [
                    'month' => Carbon::create(null, $month)->format('M'),
                    'scheduled' => Trip::whereMonth('start_date', $month)
                        ->whereYear('start_date', $year)
                        ->whereNotIn('status', [TripStatus::DELIVERED, TripStatus::CANCELLED])
                        ->count(),
                    'completed' => Trip::whereMonth('start_date', $month)
                        ->whereYear('start_date', $year)
                        ->where('status', TripStatus::DELIVERED)
                        ->count(),
                    'cancelled' => Trip::whereMonth('start_date', $month)
                        ->whereYear('start_date', $year)
                        ->where('status', TripStatus::CANCELLED)
                        ->count(),
                ];
            });
        });

        return [
            'datasets' => [
                [
                    'label' => 'Scheduled',
                    'data' => $months->pluck('scheduled')->toArray(),
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#1d4ed8',
                ],
                [
                    'label' => 'Completed',
                    'data' => $months->pluck('completed')->toArray(),
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#059669',
                ],
                [
                    'label' => 'Cancelled',
                    'data' => $months->pluck('cancelled')->toArray(),
                    'backgroundColor' => '#ef4444',
                    'borderColor' => '#dc2626',
                ],
            ],
            'labels' => $months->pluck('month')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => true,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
