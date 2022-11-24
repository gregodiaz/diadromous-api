<?php

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

/**
 * Calculates the odds of cancelling with and exponential calculus
 *
 * @param float $forecast_value    
 * @param float $max    
 * @return int $percentage    
 */
function calculateOdds(float $forecast_value, float $max): int
{
    if ($forecast_value > $max) return 100;

    $a = $max / 5;
    $b = 1000;

    $percentage = intval(100 * exp(- ($a / $b) * ($forecast_value - $max) ** 2));

    return $percentage;
}

/**
 * Request the cities from a json with all the cities in the world
 *
 * @param int $initial    
 * @param int $end    
 * @return Collection $cities    
 */
function requestCities(int $initial = 1074, int $end = 150): Collection
{
    $cities_response = Http::get("https://raw.githubusercontent.com/lutangar/cities.json/master/cities.json")
        ->collect()
        ->splice($initial, $end);

    $cities = collect($cities_response)
        ->map(function ($city) {
            return  [
                'country_code' => $city['country'],
                'name' => $city['name'],
                'latitude' => $city['lat'],
                'longitude' => $city['lng'],
            ];
        });

    return $cities;
}
