<?php


namespace App\Services;

use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Types\This;
use Stripe\Exception\CardException;
use Stripe\Exception\RateLimitException;
use Stripe\Exception\InvalidRequestException;
use Stripe\Exception\AuthenticationException;
use Stripe\Exception\ApiConnectionException;
use Stripe\Exception\ApiErrorException;
use Stripe\Stripe;

class StripeService
{
    public  $stripe;
    public $status=false;
    public $message=null;

    public function __construct()
    {
        Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        $this->stripe= new \Stripe\StripeClient(env('STRIPE_SECRET_KEY'));
    }

    function deleteCustomerCard($data)
    {
        $card= \Stripe\Customer::deleteSource($data['customerId'], $data['cardId']);
    }

    public function customerCreate($email): string
    {
        $customerInfo = $this->stripe->customers->create(array(
            'email' => $email,
        ));

        return $customerInfo['id'];
    }

    function setCustomerCardDefault($data)
    {
        $customer= $this->stripe->customers->update(
            $data['customerId'],
            ['default_source' => $data['cardId']]
        );
        if ($customer) {
            return true;
        }
        return false;
    }

    function customerAddCard($data)
    {
        $cardInfo = new \stdClass();
        try {
            $card = $this->stripe->customers->createSource($data['customerId'],
                ['source' => $data['id']]);
            $cardInfo->status = true;
            $cardInfo->cardId = $card['id'];
            $cardInfo->brand = $card['brand'];
            $cardInfo->lastDigits = $card['last4'];
            $cardInfo->cardId = $card['id'];
        } catch (CardException $e) {
            $cardInfo->status = false;
            $cardInfo->message = $e->getMessage();
        } catch (ApiErrorException $e) {
            $cardInfo->status = false;
            $cardInfo->message = $e->getMessage();
        }
        return $cardInfo;
    }

    public function addCard()
    {
        $token=  $this->stripe->tokens->create([
            'card' => [
                'number' => '4242424242424242',
                'exp_month' => 12,
                'exp_year' => 2024,
                'cvc' => '314',
            ],
        ]);
        return response()->json($token['id']);
    }


    public function charge($customerId, $amount, $order = null)
    {
        try {
            $orderId = $order;
            $charge = $this->stripe->charges->create([
                'customer' => $customerId,
                'currency' => 'USD',
                'amount' => $amount*100,
                'metadata'=>[
                    'OrderId'=>$orderId,
                    'paymentId'=>uniqid(),
                    'Environment'=>env('APP_ENV'),
                ]
            ]);
            $this->status=true;
            return $charge;
        }  catch (CardException $e) {
            $this->message=$e->getMessage();
        } catch (ApiErrorException $e) {
            $this->message=$e->getMessage();
        } catch (RateLimitException $e) {
            $this->message=$e->getMessage();
        } catch (ApiException $e) {
            $this->message=$e->getMessage();
        } catch (InvalidRequestException $e) {
            $this->message=$e->getMessage();
        } catch (AuthenticationException $e) {
            $this->message=$e->getMessage();
        } catch (ApiConnectionException $e) {
            $this->message=$e->getMessage();
        }
        $this->status=false;
        Log::info('Stripe info',['status'=>$this->status,'message'=>$this->message]);
        return false;
    }
    public function generateToken(){
        $connectionToken = \Stripe\Terminal\ConnectionToken::create();
        return $connectionToken->secret;
    }
}
