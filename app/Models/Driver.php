<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    /** @use HasFactory<\Database\Factories\DriverFactory> */
    use HasFactory;

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function trips()
    {
        return $this->belongsToMany(Trip::class);
    }
}
