<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Illuminate\Database\Seeder;

use App\Models\User;
use App\Models\City;
use App\Models\CityTravelType;
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
        $cities = requestCities();

        City::factory()->createMany($cities);

        User::factory(10)->create();

        CityTravelType::factory()->create(['name' => 'Departure']);
        CityTravelType::factory()->create(['name' => 'Arrival',]);

        $travels = Travel::factory(20)->create();

        collect($travels)->map(function ($travel) use ($cities) {
            $departure_city_id = rand(1, $cities->count());
            $arrival_city_id = rand(1, $cities->count());

            $travel->cities()->sync([$departure_city_id => ['type_id' => 1], $arrival_city_id => ['type_id' => 2]]);
        });
    }
}
