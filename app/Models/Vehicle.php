<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Vehicle extends Model
{
    /** @use HasFactory<\Database\Factories\VehicleFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'weight',
        'plate_number',
        'company_id'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class , 'vehicle_id');
    }

    /**
     * Get vehicles by company with caching
     */
    public static function getCachedByCompany($companyId)
    {
        return Cache::remember("vehicles.company.{$companyId}", 1800, function () use ($companyId) {
            return static::where('company_id', $companyId)
                ->select('id', 'name', 'weight')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Get vehicles for dropdown with caching
     */
    public static function getCachedOptions()
    {
        return Cache::remember('vehicles.options', 3600, function () {
            return static::with('company')
                ->select('id', 'name', 'company_id')
                ->orderBy('name')
                ->get()
                ->mapWithKeys(function ($vehicle) {
                    return [$vehicle->id => $vehicle->name . ' (' . $vehicle->company->name . ')'];
                });
        });
    }

    /**
     * Get vehicle weight with caching
     */
    public function getCachedWeight()
    {
        return Cache::remember("vehicle.weight.{$this->id}", 3600, function () {
            return $this->weight;
        });
    }

    /**
     * Clear vehicle cache when model is updated
     */
    protected static function booted()
    {
        static::saved(function ($vehicle) {
            Cache::forget("vehicles.company.{$vehicle->company_id}");
            Cache::forget('vehicles.options');
            Cache::forget("vehicle.weight.{$vehicle->id}");
        });

        static::deleted(function ($vehicle) {
            Cache::forget("vehicles.company.{$vehicle->company_id}");
            Cache::forget('vehicles.options');
            Cache::forget("vehicle.weight.{$vehicle->id}");
        });
    }
}
