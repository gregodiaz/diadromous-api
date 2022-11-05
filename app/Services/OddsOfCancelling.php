<?php

namespace App\Services;

use App\Services\ForecastApi;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

class OddsOfCancelling
{
    public function __construct(
        private ForecastApi $forecast,
    ) {
    }

    private const RULES = [
        "precipitation_sum" => 40,
        "temperature_2m_max" => 40,
        "windgusts_10m_max" => 55,
        "windspeed_10m_max" => 40,
    ];

    /**
     * Create and array with the weighteds odds of cancelling based on max forecast variables a week from now
     *
     * @param float $latitude    
     * @param float $longitude      
     * @param Carbon $departure_date
     * @return Collection $cancelation_percentages In form [DateTime => float] a week from now
     */
    public function calculate(float $latitude, float $longitude, Carbon $departure_date): Collection
    {
        $full_forecast = collect($this->forecast->makeRequest($latitude, $longitude, $departure_date)->get("daily"));

        $forecast = $full_forecast->except("time");
        $forecast_date =  collect($full_forecast->first());

        // transforms the forecast values in odds of cancelling
        $percentages_chunk_by_type = collect($forecast->map(function ($forecast_values, $forecast_type) {
            return collect($forecast_values)->map(function ($value) use ($forecast_type) {
                return calculateOdds($value, self::RULES[$forecast_type]);
            });
        }))->values();

        // transforms 4 chunks of 7 values (by type) in 7 chunks of 4 values (by day)
        $percentages_chunk_by_day = $percentages_chunk_by_type->flipMatrix();

        // returns the max of avery chunk of 4
        $max_percentages = $percentages_chunk_by_day->map(function ($percentage_day) {
            return $percentage_day->max();
        });

        // returns the weighted percentages
        $weighted_average_percentages = $max_percentages->map(function ($max, $key) {
            return intval($max / ((4 / 3) ** (1 + $key)));
        });

        // combines the percentages with their respective dates
        $cancelation_percentages = $forecast_date->combine($weighted_average_percentages);

        return $cancelation_percentages;
    }
}
