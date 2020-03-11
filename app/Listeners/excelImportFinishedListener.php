<?php

namespace App\Listeners;

use App\Events\excelImportFinishedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class excelImportFinishedListener
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
     * @param  excelImportFinishedEvent  $event
     * @return void
     */
    public function handle(excelImportFinishedEvent $event)
    {
        dump('Import finished');
    }
}
