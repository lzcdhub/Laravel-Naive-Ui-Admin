<?php

namespace App\Listeners\Modules\WxShop\Listeners;

use App\Events\Modules\WxShop\Events\CreateChat;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendMessage2
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
     * @param  \App\Events\Modules\WxShop\Events\CreateChat  $event
     * @return void
     */
    public function handle(CreateChat $event)
    {
        //
    }
}
