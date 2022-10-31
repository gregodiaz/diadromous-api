<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Travel;
use App\Services\OddsOfCancelling;
use App\Services\ValidateTravel;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TravelController extends Controller
{
    public function __construct(
        private OddsOfCancelling $percentages,
        private ValidateTravel $validator,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $travels = Travel::all();

        return response()->json($travels);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $new_travel = Travel::create($request->all());

        return response()->json($new_travel);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function show(Travel $travel)
    {
        // fake a city coordenates
        $lat = floatval(rand(-90, 90));
        $long = floatval(rand(-180, 180));
        $days = rand(1, 7);
        $validated = $this->validator->validate($lat, $long);
        $forecast = $this->percentages->calculate($lat, $long, Carbon::parse($travel->departure_time)->setTimezone('UTC'));

        $total = collect($travel)->merge(compact('validated', 'forecast'));

        return response()->json($total);
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
        $travel->update($request->all());

        return response()->json($travel);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function destroy(Travel $travel)
    {
        $travel->delete();

        return response()->json($travel);
    }
}
