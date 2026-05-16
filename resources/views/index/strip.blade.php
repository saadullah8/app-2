@extends('layouts.master')

@section('title','Card')
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
        border: 1px solid transparent;
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
            <div class="row">

                <div class="col-md-12 col-sm-12 col-lg-8 placeorder_div">


                    <form id='payment-form'>
                        <label style="display: block;">
                            Card details
                            <!-- placeholder for Elements -->
                            <div id="card-element"></div>
                        </label>
                        <button type="submit" >Submit Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('script')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        const stripe = Stripe('{{env('STRIPE_TEST_KEY')}}');
        const elements = stripe.elements();
        // Set up Stripe.js and Elements to use in checkout form
        const style = {
            base: {
                color: "#32325d",
                fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
                fontSmoothing: "antialiased",
                fontSize: "16px",
                "::placeholder": {
                    color: "#aab7c4"
                }
            },
            invalid: {
                color: "#fa755a",
                iconColor: "#fa755a"
            },
        };

        const cardElement = elements.create('card', {style});
        cardElement.mount('#card-element');

        const form = document.getElementById('payment-form');
        form.addEventListener('submit', async (event) => {
            // We don't want to let default form submission happen here,
            // which would refresh the page.
            event.preventDefault();
            const result  = await stripe.createToken(cardElement);
            stripePaymentMethodHandler(result);
        });
        const stripePaymentMethodHandler = async (result) => {
            if (result.error) {
                console.log(result.error)
                // Show error in payment form
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
                console.log(response.response.message)
            }   else {
                // Show error from server on payment form
                console.log(response.response.message)
            }
        }
    </script>
@endsection
