<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

use App\Models\CityTravelType;

class CityTravel extends Pivot
{
    use HasFactory;

    protected $table = 'city_travel';

    public function types()
    {
        return $this->belongsTo(CityTravelType::class);
    }
}
