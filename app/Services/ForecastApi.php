<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class ForecastApi
{
    /**
     * Make request to forecast api
     *
     * @param float $latitude    
     * @param float $longitude    
     * @return Collection $api_response    
     */
    public function makeRequest(float $latitude, float $longitude, Carbon $departure_date): Collection
    {
        $today = Carbon::now();
        $end_date =
            $departure_date->isPast() || $departure_date->diffInDays($today) > 7 ?
            Carbon::now()->addWeek() :
            $departure_date;

        $api_response = Http::forecast()->get(
            '/',
            [
                "latitude" => $latitude,
                "longitude" => $longitude,
                "timezone" => "auto",
                "current_weather" => true,
                "hourly" => [
                    "precipitation",
                    "temperature_2m",
                    "windgusts_10m",
                    "windspeed_10m",
                ],
                "daily" => [
                    "precipitation_sum",
                    "temperature_2m_max",
                    "windgusts_10m_max",
                    "windspeed_10m_max",
                ],
                "start_date" => $today->format('o-m-d'),
                "end_date" => $end_date->format('o-m-d'),
            ]
        )->collect();

        return $api_response;
    }
}
