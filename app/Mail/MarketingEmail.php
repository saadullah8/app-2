<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MarketingEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $message,$subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($subject,$message)
    {
        $this->message= $message;
        $this->subject= $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $msg=$this->message;
        $subject=$this->subject;
        return $this->subject($subject)->view('emails.marketing',compact('msg'));
    }
}
