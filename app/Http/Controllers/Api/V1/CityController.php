<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Services\SearchCity;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function __construct(
        private SearchCity $search_city,
    ) {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $cities = City::all();

        return $cities;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $city_found = $this->search_city->search($request->name);

        if ($city_found->get('message')) return $city_found;

        $storage_city = City::where('name', $city_found->get('name'))->first();
        if ($storage_city) return $storage_city;

        $new_city = City::create($city_found->all());

        return $new_city;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function show(City $city)
    {
        return $city;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\City  $city
     * @return \Illuminate\Http\Response
     */
    public function destroy(City $city)
    {
        $city->delete();

        return $city;
    }
}
