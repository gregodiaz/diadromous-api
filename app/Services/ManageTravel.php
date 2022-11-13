<?php

namespace App\Services;

use App\Models\City;
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
        $route = collect($request['route']);

        $departure_date = Carbon::parse($travel['departure_date'])->setTimezone('UTC');
        $departure_city = City::where('name', $route->get('departure_city'))->first();
        $arrival_city = City::where('name', $route->get('arrival_city'))->first();

        if ($departure_date->isPast()) return ['message' => 'The departure datemust be at least today.'];
        if (!$departure_city) return ['message' => 'Departure city not found.'];
        if (!$arrival_city) return ['message' => 'Arrival city not found.'];

        return compact('travel', 'departure_city', 'arrival_city');
    }
}
