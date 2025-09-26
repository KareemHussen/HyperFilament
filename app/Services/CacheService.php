<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\City;
use App\Models\Area;
use App\Models\Trip;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CacheService
{
    /**
     * Cache key constants
     */
    const CACHE_KEYS = [
        'companies' => 'companies.all',
        'companies_options' => 'companies.options',
        'drivers_options' => 'drivers.options',
        'vehicles_options' => 'vehicles.options',
        'cities_options' => 'cities.options',
        'areas_options' => 'areas.options',
        'trip_stats' => 'trips.stats',
        'dashboard_stats' => 'dashboard.stats',
    ];

    /**
     * Get dashboard statistics with caching
     */
    public static function getDashboardStats()
    {
        return Cache::remember(self::CACHE_KEYS['dashboard_stats'], 300, function () {
            return [
                'total_companies' => Company::count(),
                'total_drivers' => Driver::count(),
                'total_vehicles' => Vehicle::count(),
                'total_trips' => Trip::count(),
                'active_trips' => Trip::where('status', '!=', 'cancelled')->count(),
                'completed_trips' => Trip::where('status', 'completed')->count(),
                'recent_trips' => Trip::with(['company', 'driver', 'vehicle'])
                    ->latest()
                    ->limit(5)
                    ->get(),
            ];
        });
    }

    /**
     * Get trip statistics with caching
     */
    public static function getTripStats()
    {
        return Cache::remember(self::CACHE_KEYS['trip_stats'], 600, function () {
            return [
                'total_trips' => Trip::count(),
                'active_trips' => Trip::where('status', '!=', 'cancelled')->count(),
                'completed_trips' => Trip::where('status', 'completed')->count(),
                'cancelled_trips' => Trip::where('status', 'cancelled')->count(),
                'trips_by_status' => Trip::select('status', DB::raw('count(*) as count'))
                    ->groupBy('status')
                    ->pluck('count', 'status'),
                'trips_by_company' => Trip::with('company')
                    ->select('company_id', DB::raw('count(*) as count'))
                    ->groupBy('company_id')
                    ->get()
                    ->mapWithKeys(function ($trip) {
                        return [$trip->company->name => $trip->count];
                    }),
            ];
        });
    }

    /**
     * Get cached options for all models
     */
    public static function getAllOptions()
    {
        return Cache::remember('all.options', 3600, function () {
            return [
                'companies' => Company::getCachedOptions(),
                'drivers' => Driver::getCachedOptions(),
                'vehicles' => Vehicle::getCachedOptions(),
                'cities' => City::getCachedOptions(),
                'areas' => Area::getCachedOptions(),
            ];
        });
    }

    /**
     * Clear all cache
     */
    public static function clearAllCache()
    {
        $keys = array_values(self::CACHE_KEYS);
        $keys[] = 'all.options';
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }

        // Clear model-specific caches
        Cache::tags(['companies', 'drivers', 'vehicles', 'cities', 'areas', 'trips'])->flush();
    }

    /**
     * Clear cache by model
     */
    public static function clearModelCache($model)
    {
        $model = strtolower($model);
        
        switch ($model) {
            case 'company':
            case 'companies':
                Cache::forget(self::CACHE_KEYS['companies']);
                Cache::forget(self::CACHE_KEYS['companies_options']);
                break;
            case 'driver':
            case 'drivers':
                Cache::forget(self::CACHE_KEYS['drivers_options']);
                break;
            case 'vehicle':
            case 'vehicles':
                Cache::forget(self::CACHE_KEYS['vehicles_options']);
                break;
            case 'city':
            case 'cities':
                Cache::forget(self::CACHE_KEYS['cities_options']);
                break;
            case 'area':
            case 'areas':
                Cache::forget(self::CACHE_KEYS['areas_options']);
                break;
            case 'trip':
            case 'trips':
                Cache::forget(self::CACHE_KEYS['trip_stats']);
                Cache::forget(self::CACHE_KEYS['dashboard_stats']);
                break;
        }
        
        Cache::forget('all.options');
    }

    /**
     * Warm up cache
     */
    public static function warmUpCache()
    {
        // Pre-load all cached data
        Company::getCachedOptions();
        Driver::getCachedOptions();
        Vehicle::getCachedOptions();
        City::getCachedOptions();
        Area::getCachedOptions();
        self::getDashboardStats();
        self::getTripStats();
        self::getAllOptions();
    }
}
