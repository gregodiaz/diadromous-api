<?php

namespace App\Http\Controllers\Api\V1;

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
        $travels = Travel::with('cities')->where('done', false)->get();

        return $travels;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated_travel = collect($this->manageTravel->store($request));

        if ($validated_travel->get('message')) return response()->json($validated_travel);

        $new_travel = Travel::create($validated_travel->get('travel'));
        $new_travel->cities()->sync(
            [
                $validated_travel->get('departure_city')['id'] => ['type_id' => 1],
                $validated_travel->get('arrival_city')['id'] => ['type_id' => 2],
            ]
        );

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
        $latitude = $travel->cities->first()->latitude;
        $longitude = $travel->cities->first()->longitude;
        $departure_date = Carbon::parse($travel->departure_date)->setTimezone('UTC');

        $validation = $this->manageTravel->show($latitude, $longitude, $departure_date);

        $travel_with_validation = collect($travel)->merge(compact('validation'));

        return $travel_with_validation;
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
        $travel->delete();

        return $travel;
    }
}
