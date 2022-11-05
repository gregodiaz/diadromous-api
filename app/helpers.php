<?php

/**
* Calculates the odds of cancelling with and exponential calculus
*
* @param float $forecast_value    
* @param float $max    
* @return int $percentage    
*/
function calculateOdds(float $forecast_value, float $max): int
{
    if($forecast_value > $max) return 100;

    $a = $max / 5;
    $b = 1000;

    $percentage = intval(100 * exp(- ($a / $b) * ($forecast_value - $max) ** 2));

    return $percentage;
}
