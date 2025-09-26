<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Pekerja;

class PekerjaUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pekerja;

    public function __construct(Pekerja $pekerja)
    {
        $this->pekerja = $pekerja;
    }

    public function broadcastOn()
    {
        return new Channel('pekerja-channel');
    }

    public function broadcastAs()
    {
        return 'PekerjaUpdated';
    }
}
