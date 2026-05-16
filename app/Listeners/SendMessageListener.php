<?php

namespace App\Listeners;

use App\Events\SendMessageEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Twilio\Rest\Client;
class SendMessageListener
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
     * @param  SendMessageEvent  $event
     * @return void
     */
    public function handle(SendMessageEvent $event)
    {
        $number=$event->number;
        $message=$event->message;
        $client = new Client(env("TWILIO_SID"),env("TWILIO_AUTH_TOKEN"));
        $client->messages->create(
            $number,
            array(
                'from' => env("TWILIO_NUMBER"),
                'body' => $message
            )
        );

    }
}
