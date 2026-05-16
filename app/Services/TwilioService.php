<?php


namespace App\Services;

use App\Traits\loggerExceptionTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioService
{
    public $twilio_number,$client;
    use loggerExceptionTrait;
    function __construct()
    {
        $account_sid = env("TWILIO_SID");
        $auth_token = env("TWILIO_AUTH_TOKEN");
        $this->twilio_number = env("TWILIO_NUMBER");
        $this->client = new Client($account_sid, $auth_token);
    }

    public function sendMessage($message, $recipients)
    {
        try {
           $this->client->messages->create($recipients,
                ['from' => $this->twilio_number, 'body' => $message]);
            return true;
        } catch (\Exception $e) {
           $this->saveExceptionLog($e,'Twilio Exception');
            return $e;
        }
    }
    public function bulkMessages($recipients,$message)
    {
        try {
            // $recipients="+92302-722-6074";
            $serviceSid="MGb49145c90d62305ff5be5d067ab8d064";
            $this->client->notify->services($serviceSid)
                ->notifications->create([
                    "toBinding" => '{"binding_type":"sms", "address":"'.$recipients.'"}',
                     'body' => $message
                ]);
            return true;
        } catch (\Exception $e) {
            $this->saveExceptionLog($e,'Twilio Exception');
            return false;
        }
    }
}
