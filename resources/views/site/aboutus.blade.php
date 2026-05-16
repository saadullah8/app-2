@extends('layouts.master')
@section('title','About Us')
@section('content')
    <!-- Breadcrumb Start -->
    <!-- Breadcrumb Start -->
    <div class="bread-crumb">
        <div class="container">
            <div class="matter">
                <h2>About Us</h2>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="{{url('/')}}">HOME</a></li>
                    <li class="list-inline-item"><a href="#">About us</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- About Start -->
    <div class="about">
        <div class="container">
            <div class="row">
                <!-- Title Content Start -->
                <div class="col-sm-12 col-md-12 col-lg-6 col-xs-12 commontop text-left">
                    <h4>about our restaurant food & drinkes</h4>
                    <div class="divider style-1 left">
                        <i class="icofont icofont-ui-press hr-icon left"></i>
                        <span class="hr-simple right"></span>
                    </div>

                    <p class="des">{{env('APP_NAME')}} has been serving the Johnstown community with the best Chicken/Lamb Shawarma's, Wraps and Desserts. At Gyro Joint, every dish is created using only the freshest and the finest ingredients. All of our Shawarma's and Wraps are topped with only the freshest toppings.</p>
                    <p>Call Us Today!<br/>Find out why our customers rave about us. We have been a part of the Johnstown community since 2022. We use only the best ingredients, it must be fresh in order to be used in our dishes.</p>
                </div>
                <!-- Title Content End -->
                <div class="col-sm-12 col-md-12 col-lg-6 col-xs-12">
                    <div class="owl-carousel owl-theme owl-about">
                        <!--  Slider image Start  -->
                        <div class="item" >
                            <img src="{{url('assets/images/about/about_1.jpg')}}" alt="restaurant" class="h450">
                        </div>
                        <div class="item" >
                            <img src="{{url('assets/images/about/about_2.jpg')}}" alt="restaurant" class="h450">
                        </div>

                        <!--  Slider image End  -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- About End -->


@endsection
