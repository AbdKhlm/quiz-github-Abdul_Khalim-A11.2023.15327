<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QueueUpdated implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public int $jadwalId,
        public int $antrianSekarang
    ) {}

    public function broadcastOn(): array
    {
        return [
            new Channel('antrean.' . $this->jadwalId),
        ];
    }

    public function broadcastAs(): string
    {
        return 'antrean.updated';
    }

    public function broadcastWith(): array
    {
        return [
            'jadwal_id' => $this->jadwalId,
            'antrian_sekarang' => $this->antrianSekarang,
        ];
    }
}