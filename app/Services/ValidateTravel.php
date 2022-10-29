<?php

namespace App\Services;

use App\Services\ForecastApi;
use Illuminate\Support\Facades\Validator;

class ValidateTravel
{
    public function __construct(
        private ForecastApi $forecast,
    ) {
    }

    private const RULES = [
        "precipitation" => "numeric|max:40",
        "temperature_2m" => "numeric|between:0,40",
        "windgusts_10m" => "numeric|max:40",
        "windspeed_10m" => "numeric|max:30",
    ];

    /**
     * Validates the travel according to forecast of the day with the rules
     *
     * @param float $latitude    
     * @param float $longitude    
     * @return bool $validated    
     */
    public function validate(float $latitude, float $longitude): bool
    {
        $full_forecast = $this->forecast->makeRequest($latitude, $longitude)->get("hourly");
        $hourly_forecast = collect($full_forecast)->except("time");

        $averages_forecast = $hourly_forecast->map(function ($value) {
            return collect($value)->chunk(24)->first()->avg();
        });

        $validated = !Validator::make($averages_forecast->all(), self::RULES)->stopOnFirstFailure()->fails();

        return $validated;
    }
}
