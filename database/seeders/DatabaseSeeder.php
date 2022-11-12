<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;

use App\Models\User;
use App\Models\City;
use App\Models\Travel;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $cities_response = Http::get("https://raw.githubusercontent.com/lutangar/cities.json/master/cities.json")
            ->collect()
            ->splice(1074, 150);

        $cities = collect($cities_response)
            ->map(function ($city) {
                return  [
                    'country_code' => $city['country'],
                    'name' => $city['name'],
                    'latitude' => $city['lat'],
                    'longitude' => $city['lng'],
                ];
            });

        User::factory(10)->create();
        City::factory()->createMany($cities);

        $travels = Travel::factory(20)->create();

        collect($travels)->map(function ($travel) use ($cities) {
            $travel->cities()->attach([
                rand(0, $cities->count()),
                rand(0, $cities->count()),
            ]);
        });
    }
}
