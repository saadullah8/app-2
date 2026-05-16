@extends('layouts.master')

@section('title','Cart')
@section('style')
    <style>
        /**
    * Shows how you can use CSS to style your Element's container.
    * These classes are added to your Stripe Element by default.
    * You can override these classNames by using the options passed
    * to the `card` elemenent.
    * https://stripe.com/docs/js/elements_object/create_element?type=card#elements_create-options-classes
    */

        .StripeElement {
            height: 40px;
            padding: 10px 12px;
            width: 100%;
            color: #32325d;
            background-color: white;
            border: 1px solid #0c0c0c;
            border-radius: 4px;

            box-shadow: 0 1px 3px 0 #e6ebf1;
            -webkit-transition: box-shadow 150ms ease;
            transition: box-shadow 150ms ease;
        }

        .StripeElement--focus {
            box-shadow: 0 1px 3px 0 #cfd7df;
        }

        .StripeElement--invalid {
            border-color: #fa755a;
        }

        .StripeElement--webkit-autofill {
            background-color: #fefde5 !important;
        }
    </style>
    <link rel="stylesheet" href="{{url('datetime/css/bootstrap-datetimepicker.css')}}">
      <style>
          .promoAlertError{
              background: #dc3545;
          }
          .promoAlertSuccess{
              background: green;
          }
          .promoAlert{
              color: #ffffff!important;
              padding: 8px;
              border-radius: 5px;

          }
        .quantity input[type="number"]{
            width: 80px;
            margin: 4px;
            padding: 2px;
        }
        .summary .placeorder tr td{
            vertical-align: middle;
        }
        .summary .placeorder tr:first-child td {
            border-top: 0px;
        }
        .placeorder-container{
            padding: 80px;
        }
        .placeorder_div{
            border: 1px solid #dee2e6;
            padding-top: 15px;
        }
        .order_form{
            padding: 20px;
            border-top: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
            border-bottom: 1px solid #dee2e6;
        }
        .summary .placeorder thead {
            background: #f1f1f1;
            font-size: 14px;
            font-weight: 600;
            text-transform: uppercase;
            color: #000;
        }
        .summary .placeorder .item-name{
            display: inline-block;
            vertical-align: middle;
        }


        @media only screen and (max-width: 991px) {
            .lg-border-left{
                border-left: 1px solid #dee2e6;
            }
            .order_form {
                border-top: 0px;
            }
        }

        @media only screen and (max-width: 767px) {
            .summary .placeorder tr td p{
                font-size: 12px;
            }
            .summary .placeorder tr td:nth-child(1),
            .summary .placeorder tr td:nth-child(5){
                padding: 4px;
            }
            .summary .placeorder tr td:nth-child(5) button{
                padding: 2px;
            }
            .order_from_inner{
                display: none;
            }
            .order_form table tr:first-child td{
                border-top: 0px;
            }
            .order_form{
                padding-top: 0px;
            }
            .placeorder tbody tr:last-child {
                display: none;
            }
        }
    </style>
@endsection
@section('content')

    <!-- Breadcrumb Start -->
    <div class="bread-crumb">
        <div class="container">
            <div class="matter">
                <h2>Cart</h2>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="{{url('/')}}">HOME</a></li>
                    <li class="list-inline-item"><a href="#">Cart</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="contactus">

        <div class="container placeorder-container " >

            <a href="{{url('/')}}" class=" btn btn-theme btn-wide">
                <i class="icofont icofont-arrow-left"></i>
                go to back
            </a>
            {{--<div class="row">--}}

                {{--<div class="col-md-12 ">--}}
                   {{----}}
                {{--</div>--}}
            {{--</div>--}}
                    <div class="row">

                <div class="col-md-12 col-sm-12 col-lg-8 placeorder_div">
                    <form action="{{url('checkout')}}" method="post"  id="cart_item_form">
                        @csrf
                        <input type="hidden" value="1" name="is_cart_form">
                        <input type="hidden" value="{{old('cardSessionId')}}" id="cardSessionId" name="cardSessionId">
                        <input type="hidden" value="{{old('discountCode')}}" id="discountCode" name="discountCode">

                    <div class="row mb-2  pb-2 border-bottom">
                        <div class="col-md-3">
                            <label>Method</label>
                        </div>
                        <div class="col-md-9">
                            <input type="radio" id="pickup"  checked>
                            <label for="pickup">Pickup</label>
                        </div>
                    </div>
                    <div class="row mb-2  pb-2 border-bottom">
                        <div class="col-md-3">
                            <label>Pickup Time</label>
                        </div>
                        <div class="col-md-9">
                            <?php
                            $store = \App\Models\StoreOpening::first();
                            $currentTime = date('H:i: a', strtotime(\Carbon\Carbon::now()));
                            $startTime = date('H:i: a', $store->opening_time);
                            $endTime = date('H:i: a', $store->closing_time);

                            ?>
                                @if($store->status)

                                    @if($currentTime > $startTime && $currentTime < $endTime)
                                        <div>
                                            <input type="radio" id="asap" value="0"  name="pickup_time" @if(old('pickup_time')==0)  checked @endif>
                                            <label for="asap">15 to 30 min</label>
                                        </div>
                                        <div>
                                            <input type="radio" id="later" value="1"   name="pickup_time"  @if(old('pickup_time')==1)  checked @endif>
                                            <label for="later">Schedule for later</label>
                                        </div>
                                        @else
                                        <div>
                                            <input type="radio" id="later" value="1"   name="pickup_time"  checked>
                                            <label for="later">Schedule for later</label>
                                        </div>
                                    @endif


                                @endif


                            <div class="row" id="schedule_detail">
                                <div class="col-md-7 col-lg-7 col-sm-7">
                                    <div class="input-append  date form_datetime">
                                        <input size="16" type="text" placeholder="Click here" class="form-control" name="pickup_date" autocomplete="off"  value="{{old('pickup_date')}}" >
                                        <span class="add-on"><i class="icon-remove"></i></span>
                                        <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div>
                                </div>
                                @if ($errors->has('pickup_date'))
                                    <span class="text-danger">
                                        {{ $errors->first('pickup_date') }}
                                    </span>
                                @endif
                                @if ($errors->has('time'))
                                    <span class="text-danger"> The time must be between 11:00 AM to 10:00 PM </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2  pb-2 border-bottom">
                        <div class="col-md-3">
                            <label>Get order update text message</label>
                        </div>
                        <div class="col-md-9">
                            <div>
                                <label for="yes">
                                <input type="radio" value="1"  name="sms_update" @if( old('sms_update') == '1')  checked @endif @if(is_null(old('sms_update')))  checked @endif>
                                Yes</label>
                            </div>
                            <div>
                                <label for="no">
                                <input type="radio" value="0"    name="sms_update" @if( old('sms_update') == '0')  checked @endif>
                                No</label>
                            </div>
                            <span class="text-info">Message & data rates may apply </span>
                        </div>
                    </div>

                    <div class="row mb-2 pb-2 border-bottom">
                        <div class="col-md-3">
                            <label for="">Cell Number</label>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6">
                                    <input type="text" name="phone" data-mask="999-999-9999"
                                            value="{{ \Illuminate\Support\Facades\Auth::user()->phone }}" placeholder="Mobile Number"
                                           id="phone" class="form-control"  />
                                    <span class="text-info"> Click on cell number to update </span>
                                    {{--<input min="0" type="number" data-mask="999-999-9999" placeholder="Phone Numbe" name="phone" class="form-control">--}}
                                </div>

                            </div>
                        </div>
                    </div>
                    <div class="row mb-2 pb-2 border-bottom">
                        <div class="col-md-3">
                            <label for="">Special instructions</label>
                        </div>
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-12">
                                    <textarea name="remark"  class="form-control"   style="height: 130px"  >{{old('remark')}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                        <div class="row mb-2  pb-2 border-bottom">
                            <div class="col-md-3">
                                <label>Payment</label>
                            </div>
                            <div class="col-md-9">
                               <!-- <input type="radio" class="paymentMethod" id="COP" value="COP" name="paymentMethod" @if(is_null(old('paymentMethod')))checked @endif @if(old('paymentMethod')=="COP")  checked @endif>
                                <label for="COP">Pay at restaurant</label><br> -->

                                <input type="radio" class="paymentMethod" id="CODC" value="CODC" name="paymentMethod" @if(old('paymentMethod')=="CODC")  checked @endif @if(!$card) disabled @endif>
                                <label for="CODC">Pay existing Credit Card</label><br>

                                <input type="radio" class="paymentMethod" value="COC" name="paymentMethod" @if(old('paymentMethod')=="COC")  checked @endif id="cardCheckout" >
                                <label for="cardCheckout">Pay online from new credit card</label>

                            </div>

                        </div>
                        <div class="row mb-2  pb-2 border-bottom" id="cardDev" style="display: none">
                            <div class="col-md-3">
                                <label>Credit Card</label>
                            </div>
                            <div class="col-md-9">
                                @if(is_null(old('cardSessionId')))
                                <label style="display: block;">
                                    <!-- placeholder for Elements -->
                                    <div id="card-element"></div>
                                    <span class="text-danger" id="cardError" style="display: none">  </span>

                                </label>
                                <br>
                                    <input type="checkbox" id="savedCard" value="savedCard" @if(old('isSavedCard')=="savedCard") checked @endif name="isSavedCard" >
                                    <label for="savedCard">Card saved for future use</label>
                                @else
                                    <div id="card-element" style="display:none;"></div>
                                <?php
                                     $cardSession=\App\Models\Card::where('id',decrypt(old('cardSessionId')))->first();
                                    ?>
                                    @if($cardSession)
                                        <p class="text-left">
                                            {{$cardSession->brand}}: ****{{$cardSession->lastDigits}}
                                        </p>
                                        <input type="checkbox" id="savedCard" value="savedCard" @if(old('isSavedCard')=="savedCard") checked @endif name="isSavedCard" >
                                        <label for="savedCard">Card saved for future use</label>
                                        @endif
                                @endif
                            </div>
                        </div>
                        @if($card)
                        <div class="row mb-2  pb-2 border-bottom" id="existingCardDev" style="display:none;">
                            <div class="col-md-3">
                                <label>Default Credit Card Selected</label>
                            </div>
                            <div class="col-md-9">
                                <p class="text-left">
                                    {{$card->brand}}: ****{{$card->lastDigits}}
                                </p>
                                <a href="{{url('user/dashboard')}}" class="link"> You can change default credit Card</a>
                            </div>
                        </div>
                        @endif
                    </form>
                    <div class="row mb-2 ">
                        <div class="col-md-12 col-sm-12">
                            <label for="">Summary</label>
                        </div>
                        <div class="col-md-12 col-sm-12 summary p-0">
                            <table class="table placeorder mb-0" id="myModal">
                                <thead>
                                <tr>
                                    <td class="text-center">Name</td>
                                    <td class="text-center">Price</td>
                                    <td class="text-center">Total</td>
                                    <td></td>
                                </tr>
                                </thead>
                                <tbody>
                                <?php $sum=0; ?>
                                @if(Session::get('orders'))
                                    <?php $data=Session::get('orders'); ?>
                                    @foreach($data as $key => $values)
                                        <?php $price_per=\App\Models\Product::select('price')->where('title',$values['product_name'])->first(); ?>
                                        @if($values['type']=="meal")
                                            <tr>
                                                <td>
                                                    <p class="m-0">
                                                        {{$values['product_name']}}<br>
                                                        <span>
                                                            <small>
                                                                (Sr. {{$values['serial_number']}})
                                                                @if($values['name'] !='')
                                                                    ( {{$values['name']}} )
                                                                @endif
                                                            </small>
                                                        </span>
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    ${{$values['price']/$values['qty']}}x{{$values['qty']}}
                                                </td>

                                                <td class="text-center">
                                                    ${{$values['price']}}
                                                    <?php $sum +=$values['price'];?>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" class="btn btn-info btn-sm remove  alert_removed change-btn" data-remove_id="{{$key}}" >
                                                        <i class="icofont icofont-close-line"></i>
                                                    </a>
                                                    {{--<button type="button" class="btn btn-info"></button>--}}
                                                </td>
                                            </tr>

                                        @endif
                                        @if($values['type']=="fixed")
                                            <tr>
                                                <td>
                                                    <p class="m-0">
                                                        {{$values['product_name']}}

                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    ${{$price_per->price}}x{{$values['qty']}}
                                                </td>

                                                <td class="text-center">
                                                    ${{$values['price']}}
                                                    <?php $sum +=$values['price'];?>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" class="btn btn-info remove alert_removed change-btn" data-remove_id="{{$key}}" >
                                                        <i class="icofont icofont-close-line"></i>
                                                    </a>
                                                    {{--<button type="button" class="btn btn-info"></button>--}}
                                                </td>
                                            </tr>

                                        @endif
                                        @if($values['type']=="customized")

                                            <tr>
                                                <td>
                                                    <p class="m-0">
                                                        {{$values['product_name']}}<br>
                                                        <span>
                                                            <small>
                                                                @if($values['name'] !='')
                                                                    ( {{$values['name']}} )
                                                                @endif
                                                            </small>
                                                        </span>
                                                    </p>
                                                </td>
                                                <td class="text-center">
                                                    ${{$price_per->price+$values['extraPrice']}}x{{$values['qty']}}
                                                </td>

                                                <td class="text-center">
                                                    ${{$values['price']}}
                                                    <?php $sum +=$values['price']; ?>
                                                </td>
                                                <td>
                                                    <a href="javascript:;" class="btn btn-info remove alert_removed change-btn" data-remove_id="{{$key}}" >
                                                        <i class="icofont icofont-close-line"></i>
                                                    </a>
                                                    {{--<button type="button" class="btn btn-info"></button>--}}
                                                </td>
                                            </tr>

                                        @endif
                                    @endforeach
                                @endif

                                <tr>
                                    <td colspan="3" class="text-center">
                                        <button  type="submit"  class="btn btn-theme btn-wide placeOrderbtn" >Place order</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="2" >
                                        <p class="promoAlert promoAlertError" id="errorPromo" style="display: none">  </p>
                                        <p class="promoAlert promoAlertSuccess" id="successPromo" style="display: none">  </p>

                                        <form class="form-inline"  method="post" action="{{url('customer/promo-code')}}">
                                            @csrf
                                            <div class="form-group mb-2">
                                                <label for="staticEmail2" class="sr-only">Email</label>
                                                <input type="text" readonly class="form-control-plaintext"   value="Promo code">
                                            </div>
                                            <div class="form-group mx-sm-3 mb-2">

                                                <label for="inputPassword2" class="sr-only">Promo code</label>
                                                <input type="text" onchange="isValidPromoCode()" class="form-control" name="promocode" value="{{old('discountCode')}}" id="inputPassword2"   placeholder="Enter promo code">


                                            </div>
                                        </form>
                                    </td>
                                    <td><span id="discountValue">0%</span></td>
                                </tr>
                                <tr>
                                    <td><b>Sub Total</b></td>
                                    <td colspan="2" class="text-right pr-4">${{$sum}}</td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-12 col-sm-12  order_form lg-border-left ">
                    <div class="order_from_inner">
                        <p>  Order From </p>
                        <p><b>Gyro Joint</b></p>
                        <div class="text-center p-3">
                            <button type="submit" class="btn btn-theme btn-wide placeOrderbtn">Place order</button>
                        </div>
                    </div>
                    <table class="table mb-0">
                        <tr>
                            <td>Subtotal</td>
                            <td>${{$sum}}</td>
                        </tr>
                        <?php
                        $tax=6/100; // 6% tax
                        $amount_=$sum*$tax;
                        ?>
                        <tr>
                            <td>Taxes</td>
                            <td>${{round($amount_,2)}}</td>
                        </tr>
                        <tr>
                            <td>Discount</td>
                            <td>$<span id="displayDiscount">0</span> </td>
                        </tr>
                        <tr>
                            <td>Total</td>
                            <td>$<span id="totalOrderPrice">{{round($amount_,2)+$sum}}</span></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://cdn.jsdelivr.net/npm/gasparesganga-jquery-loading-overlay@2.1.7/dist/loadingoverlay.min.js"></script>

    <script type="text/javascript" src="{{url('datetime/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script type="text/javascript" src="{{url('js/promo/customer-verification.js')}}"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var  subtotalValue= {{round($amount_,2)+$sum}}

        const stripe = Stripe('{{env('STRIPE_TEST_KEY')}}');
        const elements = stripe.elements();
        // Set up Stripe.js and Elements to use in checkout form
        const style = {
            base: {
                color: "#0c0c0c",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#0c0c0c"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            },
        };

        const cardElement = elements.create('card', {style});
        cardElement.mount('#card-element');

        const form = document.getElementById('cart_item_form');
        const btn = document.getElementsByClassName('placeOrderbtn');
        $( ".placeOrderbtn" ).click(async function(e) {
            $.LoadingOverlay("show");
            e.preventDefault();
            $(".placeOrderbtn").prop('disabled', true);
            if ($('#cardCheckout').is(":checked")){
               var CardIds= $('#cardSessionId').val();
                if(CardIds == null || CardIds == ""){
                    const result  = await stripe.createToken(cardElement);
                    stripePaymentMethodHandler(result);
                }else{
                    document.getElementById('cart_item_form').submit();
                }
            }else{
                document.getElementById('cart_item_form').submit();
            }

        })
        const stripePaymentMethodHandler = async (result) => {
            if (result.error) {
                // Show error in payment form
                $('#cardError').html(result.error.message).show()
                $(".placeOrderbtn").prop('disabled', false);
                $.LoadingOverlay("hide");
            } else {
                // Otherwise send paymentMethod.id to your server (see Step 4)
                const res = await fetch('/pay', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    body: JSON.stringify({
                        token: result.token.id,
                    }),
                })
                const paymentResponse = await res.json();
                handleServerResponse(paymentResponse);
            }
        }
        const handleServerResponse = async (response) => {

            if (response.response.status) {
                // Show success message
                $('#cardSessionId').val(response.response.cardId)
                document.getElementById('cart_item_form').submit();
            }   else {
                $(".placeOrderbtn").prop('disabled', false);
                $.LoadingOverlay("hide");
                $('#cardError').html(response.response.message).show()
                // Show error from server on payment form
            }
        }
    </script>
    <script type="text/javascript">

         $(".date").datetimepicker({
            format: "yyyy-mm-dd HH:ii p",
            autoclose: true,
             showMeridian: true,
            minuteStep: 10,
            startDate: new Date(),
        });
    </script>
    <script type="text/javascript">
        // define the function
        var toggleElements = function () {
            var paymentMethod='';
            if($('#cardCheckout').is(":checked")){
                paymentMethod=$('#cardCheckout').val();
            }
            if($('#CODC').is(":checked")){
                paymentMethod=$('#CODC').val();
            }
            if($('#COP').is(":checked")){
                paymentMethod=$('#COP').val();
            }
            if (paymentMethod=="CODC"){
                $('#cardDev').hide(2000)
                $('#existingCardDev').show(1000)
            }else if(paymentMethod=="COC"){
                $('#existingCardDev').hide(2000)
                $('#cardDev').show(1000)
            }else{
                //COP
                $('#cardDev').hide(2000)
                $('#existingCardDev').hide(2000)
            }
        };
        // set the handler
        $('.paymentMethod').on('change', toggleElements);
        $('.inputPassword2').on('change', isValidPromoCode());
        // execute the function when the page loads
        $(document).ready(toggleElements);

        $( ".alert_removed" ).click(function(e) {
            $.LoadingOverlay("show");
            e.preventDefault();
            var result= confirm("Are you sure want to remove item?");
            if(result){
                var deleted_id=$(this).data("remove_id");
                window.location.href =base_url+'/remove/meal/'+deleted_id;
            }
        });

    </script>
@endsection
