<?php

namespace App\Listeners;

use App\Events\excelInsertedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class excelInsertedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  excelInsertedEvent  $event
     * @return void
     */
    public function handle(excelInsertedEvent $event)
    {
        /*
            App\Events\excelInsertedEvent {#31139
                +job_id: 5
                +current_progress: "1%"
                +socket: null
            }
        */
        dump('excelInsertedListener');
        dump($event->current_progress);
    }
}
