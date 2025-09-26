<?php

use App\Models\Area;
use App\Models\City;
use App\Models\Company;
use App\Models\Driver;
use App\Models\User;
use App\Models\Vehicle;

it('can create model instances via factories', function (string $modelClass) {
    $model = $modelClass::factory()->create();

    expect($model->exists)->toBeTrue();
})->with([
    Area::class,
    City::class,
    Company::class,
    Driver::class,
    User::class,
    Vehicle::class,
]);

it('persists to and reads from the database', function (string $modelClass) {
    $model = $modelClass::factory()->create();

    $found = $modelClass::query()->find($model->getKey());

    expect($found)->not->toBeNull()
        ->and($found->getKey())->toEqual($model->getKey());
        
})->with([
    Area::class,
    City::class,
    Company::class,
    Driver::class,
    User::class,
    Vehicle::class,
]);


