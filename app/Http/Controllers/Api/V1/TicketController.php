<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\Travel;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $tickets = Ticket::where('user_id', Auth::id())->get();

        return $tickets;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function index(Travel $travel)
    {
        $tickets = $travel
            ->ticket
            ->where('user_id', Auth::id()) ->all();

        return $tickets;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Models\Travel  $travel
     * @return \Illuminate\Http\Response
     */
    public function store(Travel $travel)
    {
        if ($travel->available_passengers === 0) return response()->json(['message' => 'No tickets left']);

        $new_ticket = $travel
            ->ticket()
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
     * @param  \App\Models\Travel  $travel
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Travel $travel, Ticket $ticket)
    {
        $found_ticket = $travel
            ->ticket
            ->where('id', $ticket->id)
            ->where('user_id', Auth::id())
            ->firstorfail();

        return $found_ticket;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Travel  $travel
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy(Travel $travel, Ticket $ticket)
    {
        $ticket_to_delete = $travel
            ->ticket
            ->where('id', $ticket->id)
            ->where('user_id', Auth::id())
            ->firstorfail();

        $ticket_to_delete->delete();

        $travel->increment('available_passengers');

        return $ticket_to_delete;
    }
}
