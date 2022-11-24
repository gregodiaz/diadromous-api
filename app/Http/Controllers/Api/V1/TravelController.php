<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\TravelCreated;
use App\Http\Controllers\Controller;
use App\Models\Travel;
use App\Services\ManageTravel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TravelController extends Controller
{
    public function __construct(
        private ManageTravel $manageTravel,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $travels = Travel::with('cities')->where('done', false)->orderBy('id', 'asc')->get();

        return $travels;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, TravelCreated $travel_created)
    {
        $new_travel = Travel::create($request->get('travel'));
        $cities = collect($request->get('cities'));

        $travel_created->dispatch($new_travel, $cities);
        $new_travel->cities;

        return $new_travel;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function show(Travel $travel)
    {
        $departure_city = $travel->cities->where('type_name', 'Departure')->first();

        $validation = $this->manageTravel->validator(
            $departure_city->latitude,
            $departure_city->longitude,
            Carbon::parse($departure_city->port_call)->setTimezone('UTC'),
        );

        return collect($travel)->merge(compact('validation'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Travel $travel)
    {
        $travel->cities;
        $travel->update($request->all());

        return $travel;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Travel $travel)
    {
        $travel->cities;
        $travel->delete();

        return $travel;
    }
}
