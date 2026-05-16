@extends('layouts.master')

@section('title','Store Closed')

@section('content')



    <!-- Breadcrumb Start -->

    <div class="bread-crumb">

        <div class="container">

            <div class="matter">

                <h2>Store Closed</h2>

                <ul class="list-inline">

                    <li class="list-inline-item"><a href="{{url('/')}}">HOME</a></li>

                    <li class="list-inline-item"><a href="#">Store is not Closed</a></li>

                </ul>

            </div>

        </div>

    </div>

    <!-- Contact us Start -->
    <div class="contactus">
        <div class="container">
            <div class="row">

                <!-- Title Content Start -->

                <div class="col-sm-12 col-xs-12 commontop text-center">

                    <h4>Thank You For Visiting</h4>

                    <div class="divider style-1 center">

                        <span class="hr-simple left"></span>

                        <i class="icofont icofont-ui-press hr-icon"></i>

                        <span class="hr-simple right"></span>

                    </div>

                    <div class="thanks-content">

                        <h3><i class="icofont icofont-basket"></i> Sorry. <br>You can't order right now.</h3>



                        <p> Store Opens at 11:00 AM
                            Web ordering is currently closed.
                            You may place the order by calling @ 240-513-6020.</p>



                        {{--<p>you can order between 11.30 AM to 08.00 PM and maybe Store have closed</p>--}}

                        <a class="btn btn-theme btn-wide" href="{{url('/')}}">Go to home</a>

                    </div>

                </div>

                <!-- Title Content End -->

            </div>
        </div>
    </div>
    <!-- Contact Us End  -->
    <!-- Service Start  -->

    {{--<div class="page-not-found">--}}

    {{----}}

    {{--</div>--}}

    <!-- Service End   -->





@endsection



