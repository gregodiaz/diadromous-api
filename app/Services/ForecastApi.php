<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class ForecastApi
{
    private function requestToApi(float $latitude, float $longitude)
    {
        $api_response = Http::forecast()->get(
            '/',
            [
                "latitude" => $latitude,
                "longitude" => $longitude,
                "timezone" => "auto",
                "current_weather" => true,
                "daily" => [
                    "weathercode",
                    "temperature_2m_max",
                    "precipitation_sum",
                    "windspeed_10m_max",
                    "windgusts_10m_max",
                ],
            ]
        );

        $data = json_decode($api_response, true);

        return $data;
    }
}
