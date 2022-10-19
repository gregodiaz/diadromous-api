<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Ticket;
use App\Models\Travel;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        /* $this->call([ */
        /*     TravelSeeder::class */
        /* ]); */

        \App\Models\User::factory(10)->create();

        $travels = Travel::factory(20)->create();

        foreach ($travels as $travel) {
            Ticket::factory($travel->total_passengers)
                ->for($travel)
                ->create([
                    'price' => $travel->price
                ]);
        }
    }
}
