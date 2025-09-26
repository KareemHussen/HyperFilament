<?php

use App\Filament\Resources\CityResource\Pages\EditCity;
use App\Filament\Resources\CityResource\RelationManagers\AreasRelationManager;
use App\Models\Area;
use App\Models\City;
use function Pest\Livewire\livewire;

it('can create an area under city', function () {
    $city = City::factory()->create();

    livewire(AreasRelationManager::class, [
        'ownerRecord' => $city,
        'pageClass' => EditCity::class,
    ])
        ->mountTableAction('create')
        ->setTableActionData([
            'name' => 'Downtown',
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas(Area::class, [
        'city_id' => $city->id,
        'name' => 'Downtown',
    ]);
});


