<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class City extends Model
{
    /** @use HasFactory<\Database\Factories\CityFactory> */
    use HasFactory;

    protected $fillable = ['name'];

    public function areas()
    {
        return $this->hasMany(Area::class);
    }

    /**
     * Get all cities with caching
     */
    public static function getCachedCities()
    {
        return Cache::remember('cities.all', 3600, function () {
            return static::select('id', 'name')->orderBy('name')->get();
        });
    }

    /**
     * Get cities for dropdown with caching
     */
    public static function getCachedOptions()
    {
        return Cache::remember('cities.options', 3600, function () {
            return static::pluck('name', 'id');
        });
    }

    /**
     * Clear city cache when model is updated
     */
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('cities.all');
            Cache::forget('cities.options');
        });

        static::deleted(function () {
            Cache::forget('cities.all');
            Cache::forget('cities.options');
        });
    }
}
