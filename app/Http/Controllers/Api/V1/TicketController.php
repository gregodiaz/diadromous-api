<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Travel;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Travel $travel)
    {
        $tickets = Ticket::where('travel_id', $travel->id)->get();

        return response()->json($tickets);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Travel $travel)
    {
        if ($travel->available_passengers === 0) return response()->json(['message' => 'No tickets left']);

        $travels_params = ['travel_id' => $travel->id, 'seat_number' => $travel->available_passengers,];
        $travel->decrement('available_passengers');

        $new_ticket = Ticket::create($request->all() + $travels_params);

        return response()->json($new_ticket);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Travel $travel, Ticket $ticket)
    {
        $found_ticket = Ticket::where('travel_id', $travel->id)
            ->where('seat_number', $ticket->id)
            ->firstorfail();

        return response()->json($found_ticket);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Travel $travel, Ticket $ticket)
    {
        $updated_ticket = Ticket::where('travel_id', $travel->id)
            ->where('seat_number', $ticket->id)
            ->firstorfail();

        $updated_ticket->update($request->all());

        return response()->json($updated_ticket);
    }
}
