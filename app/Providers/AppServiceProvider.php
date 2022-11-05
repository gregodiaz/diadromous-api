<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Http
        Http::macro('forecast', function () {
            return Http::acceptJson()
                ->baseUrl('https://api.open-meteo.com/v1/forecast');
        });

        // Collections
        Collection::macro('flipMatrix', function () {
            for ($i = 0; $i < $this->first()->count(); $i++) {
                $flipped_row = $this->map(function ($row) use ($i) {
                    return $row[$i];
                });
                $flipped_collection[] = $flipped_row;
            }

            return collect($flipped_collection);
        });
    }
}
