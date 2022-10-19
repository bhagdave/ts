<?php

namespace App\Listeners;

use App\Events\StreamUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class StreamUpdatedListener
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
     * @param  StreamUpdated  $event
     * @return void
     */
    public function handle(StreamUpdated $event)
    {
        
    }
}
