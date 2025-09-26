<?php

namespace App\Models;

use App\Enums\IndustryEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory;

    protected $casts = [
        'industry' => IndustryEnum::class
    ];

    protected $fillable = [
        'name',
        'industry',
        'address',
        'phone',
        'email',
        'website'
    ];

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function trips()
    {
        return $this->hasMany(Trip::class);
    }

    /**
     * Get all companies with caching
     */
    public static function getCachedCompanies()
    {
        return Cache::remember('companies.all', 3600, function () {
            return static::select('id', 'name')->orderBy('name')->get();
        });
    }

    /**
     * Get companies for dropdown with caching
     */
    public static function getCachedOptions()
    {
        return Cache::remember('companies.options', 3600, function () {
            return static::pluck('name', 'id');
        });
    }

    /**
     * Clear company cache when model is updated
     */
    protected static function booted()
    {
        static::saved(function () {
            Cache::forget('companies.all');
            Cache::forget('companies.options');
        });

        static::deleted(function () {
            Cache::forget('companies.all');
            Cache::forget('companies.options');
        });
    }
}
