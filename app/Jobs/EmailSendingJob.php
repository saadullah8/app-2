<?php

namespace App\Jobs;

use App\Mail\MarketingEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailSendingJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $email,$message,$subject;
    /* 3 tries in case of job failed */
    public $tries =1;
    /* 1hour timeout */
    public $timeout = 3600;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($email,$subject,$message)
    {
        $this->email=$email;
        $this->message=$message;
        $this->subject=$subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $message=$this->message;
        $email=$this->email;
        $subject=$this->subject;
        Mail::to($email)->send(new MarketingEmail($subject,$message));

    }
}
