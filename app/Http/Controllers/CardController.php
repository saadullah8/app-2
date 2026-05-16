<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\Card\UserCardRequest;
use App\Http\Resources\Api\Card\CardResource;
use App\Models\Card;
use App\Models\User;
use App\Services\StripeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }
    public function index(){
        return view('index.strip');
    }
    public function store(UserCardRequest $request, StripeService $stripe)
    {
        try {
            $stripCustomerId=Auth::user()->stripeCustomerId;
            if($stripCustomerId==null){
                $stripCustomerId= $stripe->customerCreate(Auth::user()->email);
                User::where('id',Auth::id())->update(['stripeCustomerId'=>$stripCustomerId]);
            }
            $customerCardInfo = $stripe->customerAddCard([
                'id' => $request->token,
                'customerId' => $stripCustomerId,
            ]);
            if (!$customerCardInfo->status) {
                return response()->json(['response' => ['status' => false, 'message' => $customerCardInfo->message]], JsonResponse::HTTP_BAD_REQUEST);
            }
            DB::beginTransaction();
            $isDefaultCard=false;
            $checkDefaultCard=Card::where('userId',Auth::id())->where('isDefault',1)->first();
            if(!$checkDefaultCard){
                $isDefaultCard=true;
                $stripe->setCustomerCardDefault([
                    'cardId' => $customerCardInfo->cardId,
                    'customerId' => $stripCustomerId,
                ]);
            }
            $userCard['userId'] = Auth::id();
            $userCard['isDefault'] = $isDefaultCard;
            $userCard['cardId'] = $customerCardInfo->cardId;
            $userCard['lastDigits'] = $customerCardInfo->lastDigits;
            $userCard['brand'] = $customerCardInfo->brand;
            $userCard['brandImageURL'] = str_replace(' ','-',strtolower($customerCardInfo->brand));
            $userCard = Card::create($userCard);
            DB::commit();
            if ($userCard) {
                return response()->json(['response' => ['status' => true,'message'=>'Card added successfully','cardId'=>encrypt($userCard->id)]], JsonResponse::HTTP_CREATED);
            }

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['response' => ['status' => false, 'message' => $e->getMessage()]], JsonResponse::HTTP_BAD_REQUEST);
        }
    }


    public function delete(Request $request, StripeService $stripe)
    {
        try {
            $cardId=decrypt($request->cardId);
            $cardInfo = Card::where('userId',Auth::id())->where('id',$cardId)->first();
            if ($cardInfo) {
                DB::beginTransaction();
                $stripeCardId=$cardInfo->cardId;
                if ($cardInfo->delete()) {
                    $stripe->deleteCustomerCard(['customerId' => Auth::user()->stripeCustomerId, 'cardId' => $stripeCardId]);
                    $cards = Auth::user()->cards;
                    if ($cards->isNotEmpty()) {
                        //check user has not already default card.
                        if (!Card::where('userId', Auth::id())->where('isDefault', 1)->exists()) {
                            $defaultCardId = $cards->random()->id;
                            $defaultCard= Card::where('userId',Auth::id())->where('id',$defaultCardId)->first();
                            if ($defaultCard) {
                                $setCardDefault = $stripe->setCustomerCardDefault([
                                    'cardId' => $defaultCard->cardId,
                                    'customerId' => $cardInfo->user->stripeCustomerId,
                                ]);
                                if ($setCardDefault) {
                                    Card::where('isDefault',1)->where('userId',Auth::id())->update(['isDefault'=>0]);
                                    $defaultCard->isDefault = 1;
                                    $defaultCard->save();
                                    DB::commit();
                                    return redirect()->back()->with('success',"Credit card deleted successfully");
                                } else {
                                    return redirect()->back()->with('error',"Error is default credit card setup in stripe");
                                }
                            }
                        }
                    }

                    DB::commit();
                    return redirect()->back()->with('success',"Credit card deleted successfully");

                } else {
                    return redirect()->back()->with('error',"Card deleting error");
                }
            }
            return redirect()->back()->with('error',"Card id is not valid");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }

    public function setCardDefault($cardId,   StripeService $stripe)
    {
        try {
            $cardId=decrypt($cardId);
            $cardInfo = Card::where('userId',Auth::id())->where('id',$cardId)->first();
            if ($cardInfo) {
                $setCardDefault = $stripe->setCustomerCardDefault([
                    'cardId' => $cardInfo->cardId,
                    'customerId' => $cardInfo->user->stripeCustomerId,
                ]);
                if ($setCardDefault) {
                    Card::where('isDefault',1)->where('userId',Auth::id())->update(['isDefault'=>0]);
                    DB::beginTransaction();
                    $cardInfo->isDefault = 1;
                    $cardInfo->update();
                    DB::commit();
                    return redirect()->back()->with('success',"Credit card marked as default for payment");
                } else {
                    return redirect()->back()->with('error',"Error is default credit card setup in stripe");
                }
            }
            return redirect()->back()->with('error',"Card id is not valid");
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error',$e->getMessage());
        }
    }
    public function dummyCard(){
        return response()->json((new StripeService)->addCard()->getData());
    }
    public function generateToken(){
        return (new StripeService)->generateToken();
    }
}

