@extends('layouts.master')
@section('content')


    <div class="bread-crumb">

        <div class="container">

            <div class="matter">

                <h2>Session Expire</h2>

                <ul class="list-inline">

                    <li class="list-inline-item"><a href="{{url('/')}}">Home</a></li>

                </ul>

            </div>

        </div>

    </div>

    <!-- Service Start  -->

    <div class="contactus">

        <div class="container">

            <div class="row ">

                <div class="col-sm-12 col-xs-12">



                </div>



                <!-- Title Content Start -->

                <div class="col-sm-12 col-xs-12 commontop text-center">

                    <h4>Session Expire!</h4>

                    <div class="divider style-1 center">

                        <span class="hr-simple left"></span>

                        <i class="icofont icofont-ui-press hr-icon"></i>

                        <span class="hr-simple right"></span>

                    </div>

                    <div class="error-content">

                        <h2>419</h2>

                        <h3>Session Expire</h3>

                        {{--<p>We can’t seem to find the page you’re looking for make sure that you have typed the correct URL</p>--}}

                        <a class="btn btn-theme btn-wide" href="{{url('/')}}">Go to home</a>

                    </div>

                </div>

                <!-- Title Content End -->


            </div>

        </div>

    </div>

@endsection

