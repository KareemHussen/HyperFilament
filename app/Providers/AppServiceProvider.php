<?php

namespace App\Providers;

use Filament\Support\Facades\FilamentColor;
use Gate;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Filament\Support\Colors\Color;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        
        FilamentColor::register([
            'PENDING' => Color::Gray,
            'APPROVED' => Color::Yellow,
            'DRIVER_RECEIVED' => Color::Sky,
            'ON_ITS_WAY' => Color::Purple,
            'DELIVERED' => Color::Green,
            'CANCELLED' => Color::Red,
        ]);

        Model::unguard();

        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });

    }
}
