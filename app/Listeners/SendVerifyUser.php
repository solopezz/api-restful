<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Mail\UserVeryfy;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVerifyUser
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
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {

        retry(5, function() use($event) {
            Mail::to($event->user)->send(new UserVeryfy($event->user));
        },1000);

    }
}
