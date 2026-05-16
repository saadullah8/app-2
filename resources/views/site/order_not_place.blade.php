@extends('layouts.master')



@section('content')

    <!-- Breadcrumb Start -->

    <!-- Breadcrumb Start -->

    <div class="bread-crumb">

        <div class="container">

            <div class="matter">

                <h2>System Error</h2>

                <ul class="list-inline">

                    <li class="list-inline-item"><a href="{{url('/')}}">HOME</a></li>

                    <li class="list-inline-item"><a href="#">System error</a></li>

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

                    <h4>Oops!</h4>

                    <div class="divider style-1 center">

                        <span class="hr-simple left"></span>

                        <i class="icofont icofont-ui-press hr-icon"></i>

                        <span class="hr-simple right"></span>

                    </div>

                    <div class="error-content">



                        @if( $error )
                            <p>{{ $error }}</p>
                        @endif

                        <a class="btn btn-theme btn-wide" href="{{url('/checkout')}}">Try again</a>

                    </div>

                </div>

                <!-- Title Content End -->



            </div>

        </div>

    </div>





@endsection

