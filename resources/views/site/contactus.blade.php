@extends('layouts.master')
@section('title','Contact Us')
@section('content')
    <!-- Breadcrumb Start -->
    <!-- Breadcrumb Start -->
    <div class="bread-crumb">
        <div class="container">
            <div class="matter">
                <h2>Contact Us</h2>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="{{url('/')}}">HOME</a></li>
                    <li class="list-inline-item"><a href="#">Contact us</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Contact us Start -->
    <div class="contactus">
        <div class="container">
            <div class="row">
                <!-- Title Content Start -->
                <div class="col-sm-12 commontop text-center">
                    <h4>Get In Touch</h4>
                    <div class="divider style-1 center">
                        <span class="hr-simple left"></span>
                        <i class="icofont icofont-ui-press hr-icon"></i>
                        <span class="hr-simple right"></span>
                    </div>

                </div>
                <!-- Title Content End -->

                <div class="col-md-5 col-12">
                    <!--  Contact form Start  -->
                    @if(Session::has('message'))
                        <div class="alert alert-info alert-dismissable ">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            {{ Session::get('message') }}
                        </div>
                    @endif

                    <form method="post" action="{{url('contact/user')}}"  enctype="multipart/form-data" class="form-horizontal">
                    @csrf
                        <div class="form-group row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <i class="icofont icofont-ui-user"></i>
                                <input type="text" name="name" value="" id="input-name" class="form-control" placeholder="name"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <i class="icofont icofont-ui-message"></i>
                                <input type="text" name="email" value="" id="input-email" class="form-control" placeholder="email"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <i class="icofont icofont-phone"></i>
                                <input type="text" name="phone" value="" id="input-phone" class="form-control" placeholder="mobile number"/>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-sm-12">
                                <i class="icofont icofont-pencil-alt-5"></i>
                                <textarea name="message" id="input-enquiry" class="form-control" rows="5" placeholder="message"></textarea>
                            </div>
                        </div>
                        <div class="buttons">
                            <input class="btn btn-theme btn-block" type="submit" value="Send Message" />
                        </div>
                    </form>
                    <!--  Contact form End  -->
                </div>
                <div class="col-md-7 col-12">
                    <!--  Map Start  -->
                    <div class="map">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12171.800886923826!2d-78.9102371!3d40.2990642!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x9f837560a1361b6!2sGyro%20Joint!5e0!3m2!1sen!2s!4v1673368849589!5m2!1sen!2s"                 width="600" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
                    </div>
                    <!--  Map End  -->
                </div>

            </div>
        </div>
    </div>
    <!-- Contact Us End  -->

@endsection
