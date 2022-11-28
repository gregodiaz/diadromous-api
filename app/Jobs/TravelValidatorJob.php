<?php

namespace App\Jobs;

use App\Events\TravelInquiry;
use App\Models\Travel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class TravelValidatorJob implements ShouldQueue
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
     * Execute the job.
     *
     * @return void
     */
    public function handle(TravelInquiry $travel_inquiry)
    {
        $travels = Travel::with('cities')->where('done', false)->get();

        foreach ($travels as $travel) {
            $departure_city = $travel->cities->where('type_name', 'Departure')->first();
            $departure_date = Carbon::parse($departure_city->port_call)->setTimezone('UTC');

            if (!$departure_date->isToday()) continue;

            $validation = $travel_inquiry->dispatch($travel);
            if (!$validation) $travel->delete();
        }
    }
}
