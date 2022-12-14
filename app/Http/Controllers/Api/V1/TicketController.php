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
        $tickets = Ticket::where('user_id', Auth::id())->with('travel')->get();

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
        $travel = Travel::find($request->get('travel_id'));
        if ($travel->available_passengers === 0) return response()->json(['message' => 'No tickets left.']);

        $new_ticket = $travel
            ->tickets()
            ->create([
                'user_id' => Auth::id(),
            ]);

        $travel->decrement('available_passengers');
        $new_ticket->travel;

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
        $ticket->travel;

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

        $ticket->travel->increment('available_passengers');
        $ticket->delete();

        return $ticket;
    }
}
