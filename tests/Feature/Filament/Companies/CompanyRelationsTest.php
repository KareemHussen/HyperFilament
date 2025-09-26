<?php

use App\Filament\Resources\CompanyResource\Pages\EditCompany;
use App\Filament\Resources\CompanyResource\RelationManagers\DriversRelationManager;
use App\Filament\Resources\CompanyResource\RelationManagers\VehiclesRelationManager;
use App\Models\Company;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\User;
use function Pest\Livewire\livewire;

it('can create a driver via company drivers relation manager', function () {
    $company = Company::factory()->create();

    livewire(DriversRelationManager::class, [
        'ownerRecord' => $company,
        'pageClass' => EditCompany::class,
    ])
        ->mountTableAction('create')
        ->setTableActionData([
            'name' => 'John Driver',
            'email' => 'john@example.com',
            'phone' => '01012345678',
            'license_number' => '12345678',
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();
});

it('can create a vehicle via company vehicles relation manager', function () {
    $company = Company::factory()->create();

    livewire(VehiclesRelationManager::class, [
        'ownerRecord' => $company,
        'pageClass' => EditCompany::class,
    ])
        ->mountTableAction('create')
        ->setTableActionData([
            'name' => 'Truck X',
            'weight' => 2000,
            'plate_number' => 'XYZ-986',
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();
});

it('validates vehicle plate_number format as ABC-123', function () {
    $company = Company::factory()->create();

    // Invalid format should fail
    livewire(VehiclesRelationManager::class, [
        'ownerRecord' => $company,
        'pageClass' => EditCompany::class,
    ])
        ->mountTableAction('create')
        ->setTableActionData([
            'name' => 'Truck Bad',
            'weight' => 1500,
            'plate_number' => 'ABC-1234', // invalid
        ])
        ->callMountedTableAction()
        ->assertHasTableActionErrors(['plate_number']);

    // Valid format should pass
    livewire(VehiclesRelationManager::class, [
        'ownerRecord' => $company,
        'pageClass' => EditCompany::class,
    ])
        ->mountTableAction('create')
        ->setTableActionData([
            'name' => 'Truck Good',
            'weight' => 1500,
            'plate_number' => 'ABC-123', // valid
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();
});

it('validates vehicle plate_number uniqueness', function () {
    $company = Company::factory()->create();
    $existing = Vehicle::factory()->create([
        'company_id' => $company->id,
        'plate_number' => 'XYZ-123',
    ]);

    livewire(VehiclesRelationManager::class, [
        'ownerRecord' => $company,
        'pageClass' => EditCompany::class,
    ])
        ->mountTableAction('create')
        ->setTableActionData([
            'name' => 'Duplicate Plate',
            'weight' => 1200,
            'plate_number' => 'XYZ-123',
        ])
        ->callMountedTableAction()
        ->assertHasTableActionErrors(['plate_number' => 'unique']);
});

it('validates driver license_number to be 8 digits numeric', function () {
    $company = Company::factory()->create();

    // Invalid license number (not 8 digits)
    livewire(DriversRelationManager::class, [
        'ownerRecord' => $company,
        'pageClass' => EditCompany::class,
    ])
        ->mountTableAction('create')
        ->setTableActionData([
            'name' => 'Driver Bad',
            'email' => 'bad@example.com',
            'phone' => '01012345678',
            'license_number' => 'ABC-1234',
        ])
        ->callMountedTableAction()
        ->assertHasTableActionErrors(['license_number']);

    // Valid 8 digits
    livewire(DriversRelationManager::class, [
        'ownerRecord' => $company,
        'pageClass' => EditCompany::class,
    ])
        ->mountTableAction('create')
        ->setTableActionData([
            'name' => 'Driver Good',
            'email' => 'good@example.com',
            'phone' => '01011112222',
            'license_number' => '12345678',
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();
});

it('validates driver license_number uniqueness', function () {
    $company = Company::factory()->create();
    Driver::factory()->create([
        'company_id' => $company->id,
        'license_number' => '87654321',
    ]);

    livewire(DriversRelationManager::class, [
        'ownerRecord' => $company,
        'pageClass' => EditCompany::class,
    ])
        ->mountTableAction('create')
        ->setTableActionData([
            'name' => 'Driver Dup',
            'email' => 'dup@example.com',
            'phone' => '01099998888',
            'license_number' => '87654321',
        ])
        ->callMountedTableAction()
        ->assertHasTableActionErrors(['license_number' => 'unique']);
});


