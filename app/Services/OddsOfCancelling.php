<?php

namespace App\Services;

use App\Services\ForecastApi;
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
     * Create and array with the weighteds odds of cancelling based on max forecasts variables a week from now
     *
     * @param float $latitude    
     * @param float $longitude    
     * @return Collection $cancelation_percentages In form [DateTime => float] a week from now
     */
    public function calculate(float $latitude, float $longitude): Collection
    {
        $full_forecast = $this->forecast->makeRequest($latitude, $longitude)->get("daily");
        $forecast = collect($full_forecast)->except("time");
        $forecast_date = collect($full_forecast)->only("time")->values()->first();

        // transform the forecasts values in percentages with calculateOdds
        $percentages_chunk_by_type = collect($forecast->map(function ($forecast_type, $key) {
            return collect($forecast_type)->map(function ($value) use ($key) {
                return calculateOdds($value, self::RULES[$key]);
            });
        }))->values();

        // get percentages 4 chunks of 7 (by type) and return 7 chunks of 4 (by day)
        for ($i = 0; $i < $percentages_chunk_by_type->first()->count(); $i++) {
            $percentages_day = $percentages_chunk_by_type->map(function ($day_percentage) use ($i) {
                return $day_percentage[$i];
            });
            $percentages_chunk_by_day[] = $percentages_day;
        }
        $max_percentage_by_day = collect($percentages_chunk_by_day);

        // return the max of avery chunk of 4
        $max_percentages = collect($max_percentage_by_day->map(function ($percentages_day) {
            return $percentages_day->max();
        }));

        // return the weighted percentages
        $weighted_average_percentages = collect($max_percentages->map(function ($max, $key) {
            return intval($max / ((4 / 3) ** (1 + $key)));
        }));

        $cancelation_percentages = collect($forecast_date)->combine($weighted_average_percentages);

        return collect($cancelation_percentages);
    }
}
