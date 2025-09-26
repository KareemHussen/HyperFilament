<?php

use App\Enums\IndustryEnum;
use App\Filament\Resources\CompanyResource;
use App\Filament\Resources\CompanyResource\Pages\CreateCompany;
use App\Filament\Resources\CompanyResource\Pages\EditCompany;
use App\Models\Company;
use function Pest\Livewire\livewire;

it('renders index page', function () {
    $this->get(CompanyResource::getUrl('index'))->assertSuccessful();
});

it('can create a company', function () {
    livewire(CreateCompany::class)
        ->fillForm([
            'name' => 'Acme Co',
            'industry' => IndustryEnum::Banking ?? 0,
            'address' => '123 Street',
            'phone' => '01011111788',
            'email' => 'info@acme.test',
            'website' => 'https://acme.test',
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Company::class, ['email' => 'info@acme.test']);
});

it('validates company email required', function () {
    livewire(CreateCompany::class)
        ->fillForm([
            'name' => 'Bad',
            'industry' => IndustryEnum::Banking,
            'address' => '123 Street',
            'phone' => '01012345678',
            'email' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'required']);
});

it('validates required fields when creating company', function () {
    livewire(CreateCompany::class)
        ->fillForm([
            'name' => null,
            'industry' => null,
            'address' => null,
            'phone' => null,
            'email' => null,
        ])
        ->call('create')
        ->assertHasFormErrors([
            'name' => 'required',
            'industry' => 'required',
            'address' => 'required',
            'email' => 'required',
        ]);
});

it('can edit a company', function () {
    $company = Company::factory()->create(['name' => 'Old']);

    livewire(EditCompany::class, ['record' => $company->getRouteKey()])
        ->fillForm([
            'name' => 'New',
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($company->refresh()->name)->toBe('New');
});


