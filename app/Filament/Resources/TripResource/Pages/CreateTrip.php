<?php

namespace App\Filament\Resources\TripResource\Pages;

use App\Enums\TripStatus;
use App\Filament\Resources\TripResource;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

class CreateTrip extends CreateRecord
{
    protected static string $resource = TripResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $existDriverOverlap = DB::table('trips')
            ->where('driver_id', $data['driver_id'])
            ->whereNotIn('status', [TripStatus::DELIVERED, TripStatus::CANCELLED])
            ->where('start_date', '<', $data['end_date'])
            ->where('end_date', '>', $data['start_date'])
            ->exists();

        
        if ($existDriverOverlap) {
            Notification::make()
            ->danger()
            ->title('Driver already has another trip')
            ->send();
    
            $this->halt(); 
        }

        // Vehicle overlap check
        $existVehicleOverlap = DB::table('trips')
            ->where('vehicle_id', $data['vehicle_id'])
            ->whereNotIn('status', [TripStatus::DELIVERED, TripStatus::CANCELLED])
            ->where('start_date', '<', $data['end_date'])
            ->where('end_date', '>', $data['start_date'])
            ->exists();

        if ($existVehicleOverlap) {
            Notification::make()
            ->danger()
            ->title('Vehicle already has another trip')
            ->send();
    
            $this->halt(); 
        }

        return $data;
    }
}
