<?php

namespace App\Jobs;

use App\Models\Travel;
use App\Services\ManageTravel;
use App\Services\ValidateTravel;
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
    public function handle(ValidateTravel $validate_travel)
    {
        $travels = Travel::with('cities')->where('done', false)->get();

        foreach ($travels as $travel) {
            $departure_city = $travel->cities->where('type_name', 'Departure')->first();
            if (Carbon::now()->lessThan($departure_city->port_call)) continue;

            $validation = $validate_travel->validate(
                $departure_city->latitude,
                $departure_city->longitude,
                Carbon::parse($departure_city->port_call)->setTimezone('UTC'),
            );

            if ($validation) $travel->delete();
        }
    }
}
