<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class excelImportFinishedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $job_id;
    public $total_rows;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($job_id, $total_rows)
    {
        $this->job_id = $job_id;
        $this->total_rows = $total_rows
    }

    public function broadcastWith()
    {
        // This must always be an array. Since it will be parsed with json_encode()
        return [
            'job_id' => $this->job_id,
            'total_rows' => $this->total_rows,
            'job_finished' => true,
        ];
    }

    public function broadcastAs()
    {
        return 'newMessage';
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        //return new PrivateChannel('channel-import-finished');
        return new Channel('messages');
    }
}
