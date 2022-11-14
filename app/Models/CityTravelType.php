<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\CityTravel;

class CityTravelType extends Model
{
    use HasFactory, SoftDeletes;

    public function cityTravels()
    {
        return $this->hasMany(CityTravel::class);
    }
}
