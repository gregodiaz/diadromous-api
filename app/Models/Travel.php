<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\City;
use App\Models\Ticket;

class Travel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'travels';
    protected $guarded = [];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function cities()
    {
        return $this->belongsToMany(City::class)->using(CityTravel::class)
            ->withPivot(['type_id', 'port_call'])
            ->join('city_travel_types', 'type_id', '=', 'city_travel_types.id')
            ->select(
                'city_travel_types.name as type_name',
                'city_travel.port_call as port_call',
                'cities.* as city'
            );
    }
}
