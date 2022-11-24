<?php

namespace App\Listeners;

use App\Events\TravelCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AttachCitiesToTravel
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TravelCreated $event)
    {
        $travel = $event->getTravel();
        $cities = $event->getCities();

        $sync_data = [];
        for ($i = 0; $i < count($cities); $i++) {
            $type_id = $cities[$i]['city_travel_type_id'];
            $city_id = $cities[$i]['city_id'];
            $port_call = $cities[$i]['port_call'];

            $sync_data[$city_id] = compact('type_id', 'port_call');
        }

        $travel->cities()->sync($sync_data);
    }
}
