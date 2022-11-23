<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Travel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())->get();

        $all = collect($tickets)->map(function ($ticket) {
            $travel = Travel::where('id', $ticket->travel_id)->with('cities')->first();
            return collect($ticket)->merge(compact('travel'));
        });

        return $all;
        return $tickets;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $travel = Travel::find($request->travel_id);

        if ($travel->available_passengers === 0) return response()->json(['message' => 'No tickets left.']);

        $new_ticket = $travel
            ->tickets()
            ->create([
                'user_id' => Auth::id(),
                'seat_number' => $travel->available_passengers,
            ]);

        $travel->decrement('available_passengers');

        return $new_ticket;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) return response()->json(['message' => 'This ticket belongs to another user.']);

        return $ticket;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) return response()->json(['message' => 'This ticket belongs to another user.']);

        $travel = Travel::where('id', $ticket->travel_id)->with('cities')->first();
        $travel->increment('available_passengers');

        $ticket->delete();

        return $ticket;
    }
}
