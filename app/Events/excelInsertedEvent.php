<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class excelInsertedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $job_id;
    public $current_progress;
    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($job_id, $current_progress)
    {
        $this->job_id = $job_id;
        $this->current_progress = $current_progress;
    }

    public function broadcastWith()
    {
        // This must always be an array. Since it will be parsed with json_encode()
        return [
            'job_id' => $this->job_id,
            'current_progress' => $this->current_progress,
            'job_finished' => false,
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
        // return new PrivateChannel('channel-inserted-'.$this->job_id);
        return new Channel('messages');
    }
}
