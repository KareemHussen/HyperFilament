<?php

use App\Filament\Resources\DriverResource;
use App\Filament\Resources\DriverResource\Pages\CreateDriver;
use App\Filament\Resources\DriverResource\Pages\EditDriver;
use App\Models\Company;
use App\Models\Driver;
use function Pest\Livewire\livewire;

it('renders index page', function () {
    $this->get(DriverResource::getUrl('index'))->assertSuccessful();
});

it('can create a driver', function () {
    $company = Company::factory()->create();

    livewire(CreateDriver::class)
        ->fillForm([
            'name' => 'John',
            'email' => 'john@example.com',
            'phone' => '01012345678',
            'license_number' => '12345678',
            'company_id' => $company->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Driver::class, ['email' => 'john@example.com']);
});

it('validates driver license_number 8 digits', function () {
    $company = Company::factory()->create();

    livewire(CreateDriver::class)
        ->fillForm([
            'name' => 'Bad',
            'email' => 'bad@example.com',
            'phone' => '01012345678',
            'license_number' => 'ABC-1234',
            'company_id' => $company->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['license_number']);
});

it('validates required fields when creating driver', function () {
    $company = Company::factory()->create();

    livewire(CreateDriver::class)
        ->fillForm([
            'name' => null,
            'email' => null,
            'phone' => null,
            'license_number' => null,
            'company_id' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'license_number' => 'required',
            'company_id' => 'required',
        ]);
});

it('can edit a driver', function () {
    $company = Company::factory()->create();
    $driver = Driver::factory()->create(['name' => 'Old', 'company_id' => $company->id]);

    livewire(EditDriver::class, ['record' => $driver->getRouteKey()])
        ->fillForm([
            'name' => 'New',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($driver->refresh()->name)->toBe('New');
});


