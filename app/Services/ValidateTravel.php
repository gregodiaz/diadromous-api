<?php

namespace App\Services;

use App\Services\ForecastApi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;

class ValidateTravel
{
    public function __construct(
        private ForecastApi $forecast,
        private Carbon $today,
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
     * @param Carbon $departure_date    
     * @return array $validated in form ['validated' => bool]
     */
    public function validate(float $latitude, float $longitude): array
    {
        $full_forecast = $this->forecast->makeRequest($latitude, $longitude, $this->today->now())->get("hourly");
        $hourly_forecast = collect($full_forecast)->except("time");

        $averages_forecast = $hourly_forecast->map(function ($value) {
            return collect($value)->chunk(24)->first()->avg();
        });

        $validated = !Validator::make($averages_forecast->all(), self::RULES)->stopOnFirstFailure()->fails();

        return compact('validated');
    }
}
