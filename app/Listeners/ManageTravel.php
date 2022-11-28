<?php

namespace App\Listeners;

use App\Models\Travel;
use App\Services\OddsOfCancellingTravel;
use App\Services\ValidateTravel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Carbon;

class ManageTravel
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(
        private OddsOfCancellingTravel $percentages,
        private ValidateTravel $validate_travel,
    ) {
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        $travel = $event->getTravel();

        $departure_city = $travel->cities->where('type_name', 'Departure')->first();
        $departure_date = Carbon::parse($departure_city->port_call)->setTimezone('UTC');

        $validation = $departure_date->isPast() ?
            $this->validate_travel->validate($departure_city->latitude, $departure_city->longitude):
            $this->percentages->calculate($departure_city->latitude, $departure_city->longitude, $departure_date) ;

        return $validation;
    }
}
