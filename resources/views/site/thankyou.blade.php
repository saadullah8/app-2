@extends('layouts.master')@section('title','Thank You')@section('content')    <!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter"><h2>Thank You</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{url('/')}}">HOME</a></li>
                <li class="list-inline-item"><a href="#">Thank You</a></li>
            </ul>
        </div>
    </div>
</div> <!-- Contact us Start -->
<div class="contactus">
    <div class="container">
        <div class="row"><!-- Title Content Start -->
            <div class="col-sm-12 col-xs-12 commontop text-center"><h4>Thank You</h4>
                <div class="divider style-1 center"><span class="hr-simple left"></span> <i
                            class="icofont icofont-ui-press hr-icon"></i> <span class="hr-simple right"></span></div>
                <div class="thanks-content"><h3><i class="icofont icofont-tick-mark"></i> Congratulations. <br>Your
                        order was Completed Successfully.</h3> @if(\Illuminate\Support\Facades\Auth::check())
                        <p>
                            <strong>Hi {{\Illuminate\Support\Facades\Auth::user()->first_name." ". \Illuminate\Support\Facades\Auth::user()->last_name}}
                                ,</strong></p>                        @else                            <p><strong>Hi
                                Guest,</strong></p>                        @endif <p>We have received your order.<br>
                        You Can Pick your order in 15 to 30 min.</p>
                    <p>Your Order ID: <strong>{{$order_id}}</strong></p>                        <a
                            class="btn btn-theme btn-wide" href="{{url('/')}}">Go to home</a></div>
            </div>                <!-- Title Content End -->            </div>
    </div>
</div>    <!-- Contact Us End  -->    <!-- Service Start  -->    {{--<div class="page-not-found">--}}         {{----}}    {{--</div>--}}    <!-- Service End   -->@endsection