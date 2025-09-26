<?php

use App\Filament\Resources\VehicleResource;
use App\Filament\Resources\VehicleResource\Pages\CreateVehicle;
use App\Filament\Resources\VehicleResource\Pages\EditVehicle;
use App\Models\Company;
use App\Models\Vehicle;
use function Pest\Livewire\livewire;

dataset('requiredFields', [
    'name',
    'weight',
    'plate_number',
    'company_id',
]);
it('creates vehicle via Filament create page', function () {


    $company = Company::factory()->create();

    livewire(CreateVehicle::class)
        ->fillForm([
            'name' => 'Truck 1',
            'weight' => 1000,
            'plate_number' => 'ABC-123',
            'company_id' => $company->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();
});

it('requires field when creating vehicle', function (string $field) {
    $company = Company::factory()->create();

    $valid = [
        'name' => 'Truck A',
        'weight' => 1200,
        'plate_number' => 'XYZ-123',
        'company_id' => $company->id,
    ];

    unset($valid[$field]);

    livewire(CreateVehicle::class)
        ->fillForm($valid)
        ->call('create')
        ->assertHasFormErrors([$field => 'required']);
})->with('requiredFields');

/**
 * Plate number validation: must be exactly XYZ-123 format
 */
it('accepts valid plate format XYZ-123 when creating', function () {
    $company = Company::factory()->create();

    livewire(CreateVehicle::class)
        ->fillForm([
            'name' => 'Truck B',
            'weight' => 1500,
            'plate_number' => 'XYZ-123',
            'company_id' => $company->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(Vehicle::query()->where('plate_number', 'XYZ-123')->exists())->toBeTrue();
});

dataset('invalidPlates', [
    'XYZ123',     // missing hyphen
    'XY-123',     // only 2 letters
    'WXYZ-123',   // 4 letters
    'XYZ-12',     // only 2 digits
    'XYZ-1234',   // 4 digits
    'AB1-123',    // number in letters part
    'ABC-12A',    // letter in digits part
    'abc-123',    // lowercase letters
    ' XYZ-123',   // leading space
    'XYZ -123',   // space around hyphen
    'XYZ-123 ',   // trailing space
]);

it('rejects invalid plate formats on create', function (string $plate) {
    $company = Company::factory()->create();

    livewire(CreateVehicle::class)
        ->fillForm([
            'name' => 'Truck C',
            'weight' => 1100,
            'plate_number' => $plate,
            'company_id' => $company->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['plate_number']);
})->with('invalidPlates');

it('rejects invalid plate formats on edit', function () {
    $vehicle = Vehicle::factory()->create([
        'name' => 'Edit Me',
        'plate_number' => 'XYZ-123',
    ]);

    livewire(EditVehicle::class, ['record' => $vehicle->getRouteKey()])
        ->fillForm([
            'plate_number' => 'ABC123',
        ])
        ->call('save')
        ->assertHasFormErrors(['plate_number']);
});


