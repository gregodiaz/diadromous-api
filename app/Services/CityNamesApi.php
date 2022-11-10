<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class CityNamesApi
{
    /**
     * Make request to city names api
     *
     * @param string $name    
     * @return Collection $api_response    
     */
    public function makeRequest(string $name): Collection
    {
        $api_response = Http::get(
            '/https://geocoding-api.open-meteo.com/v1/search',
            [
                "name" => $name,
                "count" => 1,
            ]
        )->collect();

        return $api_response;
    }
}
