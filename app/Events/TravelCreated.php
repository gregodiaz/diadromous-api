<?php

namespace App\Events;

use App\Models\Travel;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;

class TravelCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Travel  $travel
     * @param Collection  $cities
     * @return void
     */
    public function __construct(
        private Travel $travel,
        private Collection $cities
    ) {
    }

    public function getTravel(): Travel
    {
        return $this->travel;   
    }
    
    public function getCities(): Collection
    {
        return $this->cities;   
    }
    
    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
