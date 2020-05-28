<?php

namespace App\Listeners;

use App\Events\UserMailCahnged;
use App\Mail\UserMailChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendVerifyUserChangeEmail
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
     * @param  UserMailCahnged  $event
     * @return void
     */
    public function handle(UserMailCahnged $event)
    {
        if ($event->user->isDirty('email')) {
            retry(5, function() use($event) {
                Mail::to($event->user)->send(new UserMailChanged($event->user));
            },1000);
        }
    }
}
