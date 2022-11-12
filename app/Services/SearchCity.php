<?php

namespace App\Services;

use App\Services\CityNamesApi;
use Illuminate\Support\Collection;

class SearchCity
{
    public function __construct(
        private CityNamesApi $city_name,
    ) {
    }

    /**
     * Search the first matching city by name
     *
     * @param string $name    
     * @return Collection $city_found    
     */
    public function search(string $name): Collection
    {
        $response = $this->city_name->makeRequest($name);

        if (!$response->get('results')) return collect(["message" => "City not found."]);

        $results = collect($response->get('results'));
        $first = collect($results->first());
        $city_found = $first->only(["name", "latitude", "longitude", "country_code"]);

        return $city_found;
    }
}
