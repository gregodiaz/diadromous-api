<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\Travel;
use App\Models\CityTravel;

class City extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function travels()
    {
        return $this->belongsToMany(Travel::class)->using(CityTravel::class)->withPivot('type_id');
    }
}
