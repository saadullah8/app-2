@extends('layouts.master')

@section('title','401 Error')

@section('content')

    <!-- Breadcrumb Start -->

    <div class="bread-crumb">

        <div class="container">

            <div class="matter">

                <h2>Access Denied</h2>

                <ul class="list-inline">

                    <li class="list-inline-item"><a href="{{url('/')}}">HOME</a></li>

                    <li class="list-inline-item"><a href="#">Access Denied</a></li>

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

                    <h4>Access Denied!</h4>

                    <div class="divider style-1 center">

                        <span class="hr-simple left"></span>

                        <i class="icofont icofont-ui-press hr-icon"></i>

                        <span class="hr-simple right"></span>

                    </div>

                    <div class="error-content">

                        <h2>401</h2>

                        <h3>Oops! Looks like something going wrong</h3>

                        <p>
                            We will work on fixing that right away.
                            Meanwhile, you may <a href="{{url('/')}}">return to dashboard</a> or contact to support team.
                        </p>

                    </div>

                </div>

                <!-- Title Content End -->



            </div>

        </div>

    </div>





@endsection

