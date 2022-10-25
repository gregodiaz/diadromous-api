<?php

namespace App\Services;

use App\Services\ForecastApi;
use Illuminate\Support\Facades\Validator;

class ValidateTravel
{
    private const RULES = [
        "temperature_2m_max" => "numeric|between:3,30",
        "precipitation_sum" => "numeric|between:0,30",
        "windspeed_10m_max" => "numeric|between:5,30",
        "windgusts_10m_max" => "numeric|between:5,40",
    ];

    public function __invoke(float $latitude, float $longitude, $forecast = new ForecastApi)
    {
        $travels_forecast = $forecast($latitude, $longitude)['daily'];

        foreach ($travels_forecast as $key => $number) {
            $travels_forecast[$key] = array_sum($number) / count($number);
        };

        $validated = !Validator::make($travels_forecast, self::RULES)->stopOnFirstFailure()->fails();

        return response()->json(compact('travels_forecast', 'validated'));
    }
}
