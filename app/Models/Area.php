<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Area extends Model
{
    /** @use HasFactory<\Database\Factories\AreaFactory> */
    use HasFactory;

    protected $fillable = ['name', 'city_id'];

    public function city()
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Get areas by city with caching
     */
    public static function getCachedByCity($cityId)
    {
        return Cache::remember("areas.city.{$cityId}", 1800, function () use ($cityId) {
            return static::where('city_id', $cityId)
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        });
    }

    /**
     * Get areas for dropdown with caching
     */
    public static function getCachedOptions()
    {
        return Cache::remember('areas.options', 3600, function () {
            return static::with('city')
                ->select('id', 'name', 'city_id')
                ->orderBy('name')
                ->get()
                ->mapWithKeys(function ($area) {
                    return [$area->id => $area->name . ' (' . $area->city->name . ')'];
                });
        });
    }

    /**
     * Clear area cache when model is updated
     */
    protected static function booted()
    {
        static::saved(function ($area) {
            Cache::forget("areas.city.{$area->city_id}");
            Cache::forget('areas.options');
        });

        static::deleted(function ($area) {
            Cache::forget("areas.city.{$area->city_id}");
            Cache::forget('areas.options');
        });
    }
}
