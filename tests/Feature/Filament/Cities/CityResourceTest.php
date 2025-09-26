<?php

use App\Filament\Resources\CityResource;
use App\Filament\Resources\CityResource\Pages\CreateCity;
use App\Filament\Resources\CityResource\Pages\EditCity;
use App\Filament\Resources\CityResource\Pages\ListCities;
use App\Models\City;
use function Pest\Livewire\livewire;

it('renders index page', function () {
    $this->get(CityResource::getUrl('index'))->assertSuccessful();
});

it('can create a city', function () {
    livewire(CreateCity::class)
        ->fillForm([
            'name' => 'Cairo',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(City::class, ['name' => 'Cairo']);
});

it('can edit a city', function () {
    $city = City::factory()->create(['name' => 'Old']);

    livewire(EditCity::class, ['record' => $city->getRouteKey()])
        ->fillForm([
            'name' => 'New',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($city->refresh()->name)->toBe('New');
});

it('validates city name required', function () {
    livewire(CreateCity::class)
        ->fillForm([
            'name' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['name' => 'required']);
});


