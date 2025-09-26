<?php

namespace App\Models;

use App\Enums\TripStatus;
use App\Services\CacheService;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Trip extends Model
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasFactory;

    protected $casts = [
        'status' => TripStatus::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
    ];

    protected $fillable = [
        'company_id',
        'driver_id',
        'vehicle_id',
        'from_area',
        'to_area',
        'status',
        'start_date',
        'end_date'
    ];

    public function packages()
    {
        return $this->hasMany(Package::class);
    }

    public function company()
    {
        return $this->hasOne(Company::class , "id" , "company_id");
    }

    public function driver()
    {
        return $this->hasOne(Driver::class , "id" , "driver_id");
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class , "id" , "vehicle_id");
    }

    public function fromArea()
    {
        return $this->belongsTo(Area::class, 'from_area');
    }

    public function toArea()
    {
        return $this->belongsTo(Area::class, 'to_area');
    }

    public function scopeInProgress(Builder $query)
    {
        return $query->whereNotIn('status' , [TripStatus::CANCELLED]);
    }

    public function scopeLastThirtyDays(Builder $query)
    {
        return $query->where('start_date' , '>', now()->subDays(30));
    }

    /**
     * Get trip statistics with caching
     */
    public static function getCachedStats()
    {
        return Cache::remember('trips.stats', 600, function () {
            return [
                'total' => static::count(),
                'active' => static::where('status', '!=', 'cancelled')->count(),
                'completed' => static::where('status', 'completed')->count(),
                'cancelled' => static::where('status', 'cancelled')->count(),
                'recent' => static::with(['company', 'driver', 'vehicle'])
                    ->latest()
                    ->limit(10)
                    ->get(),
            ];
        });
    }

    /**
     * Get trips by company with caching
     */
    public static function getCachedByCompany($companyId)
    {
        return Cache::remember("trips.company.{$companyId}", 1800, function () use ($companyId) {
            return static::where('company_id', $companyId)
                ->with(['driver', 'vehicle', 'fromArea', 'toArea'])
                ->latest()
                ->get();
        });
    }

    /**
     * Clear trip cache when model is updated
     */
    protected static function booted()
    {
        static::saved(function ($trip) {
            CacheService::clearModelCache('trips');
            Cache::forget("trips.company.{$trip->company_id}");
        });

        static::deleted(function ($trip) {
            CacheService::clearModelCache('trips');
            Cache::forget("trips.company.{$trip->company_id}");
        });
    }
}
