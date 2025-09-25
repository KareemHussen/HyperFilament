<?php

namespace App\Models;

use App\Enums\TripStatus;
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

    public function driver()
    {
        return $this->hasOne(Driver::class);
    }

    public function vehicle()
    {
        return $this->hasOne(Vehicle::class);
    }

    public function fromArea()
    {
        return $this->belongsTo(Area::class, 'from_area');
    }

    public function toArea()
    {
        return $this->belongsTo(Area::class, 'to_area');
    }
}
