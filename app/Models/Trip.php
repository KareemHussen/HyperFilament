<?php

namespace App\Models;

use App\Enums\TripStatus;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    /** @use HasFactory<\Database\Factories\TripFactory> */
    use HasFactory;

    protected $casts = [
        'status' => TripStatus::class
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
}
