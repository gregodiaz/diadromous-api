<?php

namespace App\Jobs;

use App\Models\Travel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class TravelPortCallJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Gets all travels not done, and set it to true if the departure date is already past.
     *
     * @return void
     */
    public function handle()
    {
        $travels = Travel::with('cities')->where('done', false)->get();

        foreach ($travels as $travel) {
            $departure_city = $travel->cities->where('type_name', 'Departure')->first();

            if (Carbon::now()->greaterThanOrEqualTo($departure_city->port_call))
                $travel->update(['done' => true]);
        }
    }
}
