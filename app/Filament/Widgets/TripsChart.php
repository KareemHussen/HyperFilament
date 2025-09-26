<?php

namespace App\Filament\Widgets;

use App\Enums\TripStatus;
use App\Models\Trip;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class TripsChart extends ChartWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    public function getHeading(): ?string
    {
        return 'Monthly Trips Overview';
    }

    protected function getData(): array
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return [
                'month' => Carbon::create(null, $month)->format('M'),
                'InProgress' => Trip::whereMonth('start_date', $month)
                    ->whereYear('start_date', now()->year)
                    ->whereNotIn('status', [TripStatus::DELIVERED, TripStatus::CANCELLED])
                    ->count(),
                'completed' => Trip::whereMonth('start_date', $month)
                    ->whereYear('start_date', now()->year)
                    ->where('status', TripStatus::DELIVERED)
                    ->count(),
                'cancelled' => Trip::whereMonth('start_date', $month)
                    ->whereYear('start_date', now()->year)
                    ->where('status', TripStatus::CANCELLED)
                    ->count(),
            ];
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
