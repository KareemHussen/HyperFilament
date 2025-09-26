<?php

use App\Enums\TripStatus;
use App\Enums\PackageTypeEnum;
use App\Filament\Resources\TripResource;
use App\Filament\Resources\TripResource\Pages\CreateTrip;
use App\Filament\Resources\TripResource\Pages\EditTrip;
use App\Models\Area;
use App\Models\City;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Trip;
use App\Models\Vehicle;
use Carbon\Carbon;
use function Pest\Livewire\livewire;

it('renders index page', function () {
    $this->get(TripResource::getUrl('index'))->assertSuccessful();
});

function seedTripGeo(): array {
    $cityFrom = City::factory()->create();
    $cityTo = City::factory()->create();
    $fromArea = Area::factory()->create(['city_id' => $cityFrom->id]);
    $toArea = Area::factory()->create(['city_id' => $cityTo->id]);
    return [$fromArea, $toArea];
}

it('can create a trip', function () {
    $company = Company::factory()->create();
    $vehicle = Vehicle::factory()->create(['company_id' => $company->id]);
    $driver = Driver::factory()->create(['company_id' => $company->id]);
    [$fromArea, $toArea] = seedTripGeo();

    $component = livewire(CreateTrip::class);
    $component->fillForm([
        'company_id' => $company->id,
        'vehicle_id' => $vehicle->id,
        'driver_id' => $driver->id,
        'from_city' => $fromArea->city_id,
        'to_city' => $toArea->city_id,
        'from_area' => $fromArea->id,
        'to_area' => $toArea->id,
        'status' => TripStatus::PENDING,
        'start_date' => Carbon::now()->addHours(2),
        'end_date' => Carbon::now()->addHours(4),
    ]);
    $state = $component->get('data');
    $uuid = array_key_first($state['packages']);
    $component->set("data.packages.$uuid.type", PackageTypeEnum::Box);
    $component->set("data.packages.$uuid.weight", 10);
    $component->set("data.packages.$uuid.length", 10);
    $component->set("data.packages.$uuid.width", 10);
    $component->set("data.packages.$uuid.height", 10);
    $component->set("data.packages.$uuid.quantity", 1);
    $component->set("data.packages.$uuid.note", null);
    $component->call('create')->assertHasNoFormErrors();
});

it('validates required fields when creating trip', function () {
    livewire(CreateTrip::class)
        ->fillForm([
            'company_id' => null,
            'vehicle_id' => null,
            'driver_id' => null,
            'from_city' => null,
            'to_city' => null,
            'from_area' => null,
            'to_area' => null,
            'status' => null,
            'start_date' => null,
            'end_date' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'company_id' => 'required',
            'vehicle_id' => 'required',
            'driver_id' => 'required',
            'from_area' => 'required',
            'to_area' => 'required',
            'status' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
});

it('prevents driver overlap', function () {
    $company = Company::factory()->create();
    $vehicle = Vehicle::factory()->create(['company_id' => $company->id]);
    $driver = Driver::factory()->create(['company_id' => $company->id]);
    [$fromArea, $toArea] = seedTripGeo();

    Trip::factory()->create([
        'company_id' => $company->id,
        'vehicle_id' => $vehicle->id,
        'driver_id' => $driver->id,
        'from_area' => $fromArea->id,
        'to_area' => $toArea->id,
        'status' => TripStatus::PENDING,
        'start_date' => Carbon::now()->addHour(),
        'end_date' => Carbon::now()->addHours(3),
    ]);

    $component = livewire(CreateTrip::class);
    $component->fillForm([
        'company_id' => $company->id,
        'vehicle_id' => $vehicle->id,
        'driver_id' => $driver->id,
        'from_city' => $fromArea->city_id,
        'to_city' => $toArea->city_id,
        'from_area' => $fromArea->id,
        'to_area' => $toArea->id,
        'status' => TripStatus::PENDING,
        'start_date' => Carbon::now()->addHours(2),
        'end_date' => Carbon::now()->addHours(4),
    ]);
    $state = $component->get('data');
    $uuid = array_key_first($state['packages']);
    $component->set("data.packages.$uuid.type", PackageTypeEnum::Box);
    $component->set("data.packages.$uuid.weight", 10);
    $component->set("data.packages.$uuid.length", 10);
    $component->set("data.packages.$uuid.width", 10);
    $component->set("data.packages.$uuid.height", 10);
    $component->set("data.packages.$uuid.quantity", 1);
    $component->call('create')->assertNotified('Driver already has another trip');
});

it('prevents vehicle overlap', function () {
    $company = Company::factory()->create();
    $vehicle = Vehicle::factory()->create(['company_id' => $company->id]);
    $driverA = Driver::factory()->create(['company_id' => $company->id]);
    $driverB = Driver::factory()->create(['company_id' => $company->id]);
    [$fromArea, $toArea] = seedTripGeo();

    Trip::factory()->create([
        'company_id' => $company->id,
        'vehicle_id' => $vehicle->id,
        'driver_id' => $driverA->id,
        'from_area' => $fromArea->id,
        'to_area' => $toArea->id,
        'status' => TripStatus::PENDING,
        'start_date' => Carbon::now()->addHour(),
        'end_date' => Carbon::now()->addHours(3),
    ]);

    $component = livewire(CreateTrip::class);
    $component->fillForm([
        'company_id' => $company->id,
        'vehicle_id' => $vehicle->id,
        'driver_id' => $driverB->id,
        'from_city' => $fromArea->city_id,
        'to_city' => $toArea->city_id,
        'from_area' => $fromArea->id,
        'to_area' => $toArea->id,
        'status' => TripStatus::PENDING,
        'start_date' => Carbon::now()->addHours(2),
        'end_date' => Carbon::now()->addHours(4),
    ]);
    $state = $component->get('data');
    $uuid = array_key_first($state['packages']);
    $component->set("data.packages.$uuid.type", PackageTypeEnum::Box);
    $component->set("data.packages.$uuid.weight", 10);
    $component->set("data.packages.$uuid.length", 10);
    $component->set("data.packages.$uuid.width", 10);
    $component->set("data.packages.$uuid.height", 10);
    $component->set("data.packages.$uuid.quantity", 1);
    $component->call('create')->assertNotified('Vehicle already has another trip');
});


