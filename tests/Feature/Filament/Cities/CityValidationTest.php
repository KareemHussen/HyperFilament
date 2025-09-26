<?php

use App\Filament\Resources\CityResource;
use App\Filament\Resources\CityResource\Pages\CreateCity;
use App\Models\User;
use function Pest\Livewire\livewire;

it('can render page', function () {
    $this->get(CityResource::getUrl('index'))->assertSuccessful();
});

it('validates city name min length', function () {

    livewire(CreateCity::class)
        ->fillForm([
            'name' => 'ab',
        ])
        ->call('create')
        ->assertHasFormErrors(['name']);
});


