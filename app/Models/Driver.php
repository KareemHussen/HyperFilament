<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'license_number',
        'company_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function trips()
    { 
        return $this->belongsToMany(Trip::class);
    }

    /**
     * Get drivers by company with caching
     */
    public static function getCachedByCompany($companyId)
    {
        return Cache::remember("drivers.company.{$companyId}", 1800, function () use ($companyId) {
            return static::where('company_id', $companyId)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Get drivers for dropdown with caching
     */
    public static function getCachedOptions()
    {
        return Cache::remember('drivers.options', 3600, function () {
            return static::with('company')
                ->select('id', 'name', 'company_id')
                ->orderBy('name')
                ->get()
                ->mapWithKeys(function ($driver) {
                    return [$driver->id => $driver->name . ' (' . $driver->company->name . ')'];
                });
        });
    }

    /**
     * Clear driver cache when model is updated
     */
    protected static function booted()
    {
        static::saved(function ($driver) {
            Cache::forget("drivers.company.{$driver->company_id}");
            Cache::forget('drivers.options');
        });

        static::deleted(function ($driver) {
            Cache::forget("drivers.company.{$driver->company_id}");
            Cache::forget('drivers.options');
        });
    }
}
