<?php

namespace App\Http\Controllers\Api\V1;

use App\Events\TravelCreated;
use App\Events\TravelInquiry;
use App\Http\Controllers\Controller;
use App\Models\Travel;
use Illuminate\Http\Request;

class TravelController extends Controller
{
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
    public function show(Travel $travel, TravelInquiry $travel_inquiry)
    {
        $forecast = $travel_inquiry->dispatch($travel);

        return collect($travel)->put('forecast', $forecast[0]);
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
