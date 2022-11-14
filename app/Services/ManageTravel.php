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
    public function show(float $latitude, float $longitude, Carbon $departure_date): bool|Collection
    {
        $validation = Carbon::now()->lessThanOrEqualTo($departure_date) ?
            $this->percentages->calculate($latitude, $longitude, $departure_date) :
            $this->validate_travel->validate($latitude, $longitude);

        return $validation;
    }

    /**
     * Validate the date, the departure and arrival cities. Date at least today and cities exist in the database.
     *
     * @param Illuminate\Http\Request  $request  
     * @return array $array variables validated or a message with the error
     */
    public function store(Request $request): array
    {
        $travel = $request['travel'];
        $requests_cities = collect($request['cities']);

        $departure_date = Carbon::parse($travel['departure_date'])->setTimezone('UTC');
        if ($departure_date->isPast()) return ['message' => 'The departure datemust be at least today.'];

        $cities = $requests_cities->map(function ($city) {
            $city_found = City::find($city['city_id']);
            $type = CityTravelType::find($city['city_travel_type_id']);

            return collect(compact('type'))->merge($city_found);
        });

        foreach ($cities as $city) {
            if ($city->count() === 1) return ['message' => $city['type']['name'] . " city not found"];
        }

        return compact('travel', 'cities');
    }
}
