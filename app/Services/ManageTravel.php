<?php

namespace App\Services;

use App\Models\City;
use App\Models\CityTravelType;
use App\Services\OddsOfCancellingTravel;
use App\Services\ValidateTravel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class ManageTravel
{
    public function __construct(
        private OddsOfCancellingTravel $percentages,
        private ValidateTravel $validate_travel,
    ) {
    }

    /**
     * if $departure_date is today, validates or not the travel, otherwise return the odds of cancel from today to $departure_date 
     *
     * @param float $latitude    
     * @param float $longitude    
     * @param Carbon  $departure_date    
     * @return bool|Collection $validation    
     */
    public function validator(float $latitude, float $longitude, Carbon $departure_date): bool|Collection
    {
        $validation = Carbon::now()->lessThan($departure_date) ?
            $this->percentages->calculate($latitude, $longitude, $departure_date) :
            $this->validate_travel->validate($latitude, $longitude);

        return $validation;
    }
}
