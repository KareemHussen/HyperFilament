<?php

namespace App\Filament\Widgets;

use App\Models\Trip;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class ActiveTripsTable extends TableWidget
{
    protected int | string | array $columnSpan = 'full';

    protected static ?int $sort = 2;

    public function table(Table $table): Table
    {
        return $table
            ->query(Trip::query()->with(['company', 'driver', 'vehicle']))
            ->defaultSort('start_date', 'asc')
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->label('Company')
                    ->badge()
                    ->color('info'),


                Tables\Columns\TextColumn::make('driver.name')
                    ->label('Driver')
                    ->badge()
                    ->color('success'),

                Tables\Columns\TextColumn::make('vehicle.plate_number')
                    ->label('Vehicle')
                    ->badge()
                    ->color('warning'),

                Tables\Columns\TextColumn::make('start_date')
                    ->label('Start Time')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge(),
            ])
           
            ->paginated([5, 10, 25]);
    }

    protected function getTableHeading(): string
    {
        return 'Active Trips';
    }
}
