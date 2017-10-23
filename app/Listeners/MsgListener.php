<?php

namespace App\Listeners;

use App\Events\PusherEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class MsgListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Handle the event.
     *
     * @param  PusherEvent  $event
     * @return void
     */
    public function handle(PusherEvent $event)
    {
        //
    }
}
