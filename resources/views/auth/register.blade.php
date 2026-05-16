@extends('layouts.master')
@section('title','Sign Up')
@section('content')
    <!-- Breadcrumb Start -->
    <div class="bread-crumb">
        <div class="container">
            <div class="matter">
                <h2>Register</h2>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="{{url('/')}}">HOME</a></li>
                    <li class="list-inline-item"><a href="#">Register</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Login Start -->
    <div class="login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 commontop text-center">
                    <h4>Create an Account</h4>
                    <div class="divider style-1 center">
                        <span class="hr-simple left"></span>
                        <i class="icofont icofont-ui-press hr-icon"></i>
                        <span class="hr-simple right"></span>
                    </div>

                </div>

                <div class="col-lg-10 col-md-12 ">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 offset-sm-0 offset-md-3 offset-lg-3">
                            <div class="loginnow">
                                <h5>Register</h5>
                                <p>Do You have an account? So <a href="{{url('login')}}">login</a> And starts less than a minute</p>
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf
                                    @error('first_name')
                                    <span class="error-msg" role="alert">
                                        <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                    <div class="form-group">
                                        <i class="icofont icofont-ui-user"></i>
                                        <input type="text" name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus placeholder="First name" id="input-name" class="form-control  @error('name') is-invalid @enderror" />


                                    </div>
                                    @error('name')
                                    <span class="error-msg" role="alert">
                                        <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                    <div class="form-group">
                                        <i class="icofont icofont-ui-user"></i>
                                        <input type="text" name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name" placeholder="Last Name" id="input-name" class="form-control  @error('name') is-invalid @enderror" />
                                    </div>
                                    @error('email')
                                    <span class="error-msg" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror

                                    <div class="form-group">
                                        <i class="icofont icofont-ui-message"></i><input type="email" name="email" value="{{ old('email') }}" placeholder="EMAIL" id="email" class="form-control @error('email') is-invalid @enderror" />
                                    </div>
                                    @error('phone')
                                    <span class="error-msg" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="form-group">
                                        <i class="icofont icofont-phone"></i>
                                        <input type="text" name="phone" data-mask="999-999-9999"
                                               required value="{{ old('phone') }}" placeholder="Mobile Number"
                                               id="phone" class="form-control @error('phone') is-invalid @enderror" />
                                    </div>
                                    @error('address')
                                    <span class="error-msg" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="form-group">
                                        <i class="icofont icofont-home"></i>
                                        <input type="text" name="address" value="{{ old('address') }}" placeholder="Address" id="phone"
                                               class="form-control @error('address') is-invalid @enderror" />
                                    </div>
                                    @error('password')
                                    <span class="error-msg" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                    <div class="form-group">
                                        <i class="icofont icofont-lock"></i><input type="password" name="password" value="" placeholder="PASSWORD" id="input-password" class="form-control @error('password') is-invalid @enderror" />
                                    </div>
                                    <div class="form-group">
                                        <i class="icofont icofont-lock"></i><input type="password" name="password_confirmation" value="" placeholder="REPEAT PASSWORD" id="input-confirm" class="form-control" />
                                    </div>

                                    @error('can_sms')
                                    <span class="error-msg" role="alert">
                                        <strong>{{ $can_sms }}</strong>
                                    </span>
                                    @enderror
                                    <div class="form-group" style="margin-bottom: 10px;">
                                        <div class="links">
                                            <label>
                                                Online Order status updates via Sms
                                            </label>
                                                <br>
                                            <label>
                                                <input type="radio" name="can_sms" value="1" checked class="checkbox-inline">
                                                Yes
                                            </label>
                                            &nbsp &nbsp
                                            <label>
                                                <input type="radio" name="can_sms" value="0" class="checkbox-inline"> No
                                            </label>

                                        </div>
                                    </div>

                                    @error('can_marketing')
                                    <span class="error-msg" role="alert">
                                        <strong>{{ $can_marketing }}</strong>
                                    </span>
                                    @enderror

                                    <div class="form-group" style="margin-bottom: 10px;">
                                        <div class="links">
                                            <label>
                                                Would you like to receive our exclusive promotion updates via Sms?
                                            </label>
                                            <label>
                                                <input type="radio" name="can_marketing"  checked value="1"  class="checkbox-inline"> Yes
                                            </label>
                                            &nbsp&nbsp
                                            <label>
                                                <input type="radio" name="can_marketing"   value="0" class="checkbox-inline"> No
                                            </label>
                                        </div>
                                    </div>


                                    @if($errors->has('term_condition'))
                                    <span class="error-msg" role="alert">
                                        <strong> {{ $errors->first('term_condition') }}</strong>
                                    </span>
                                    @endif

                                    <div class="form-group">
                                        <div class="links">
                                            <label><input type="checkbox" class="checkbox-inline" name="term_condition" value="1" checked>
                                                By signing up I agree with <a href="javascript:;" data-toggle="modal" data-target="#terms_conditions">terms &amp; conditions.</a> </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <input type="submit" value="SIGN UP" class="btn btn-theme btn-md btn-wide" />
                                    </div>
                                </form>
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Breakfast Menu End -->
    <div class="modal fade" id="terms_conditions" role="dialog">
        <div class="modal-dialog second-cart add-cart2">

            <div class="modal-content slider-model">
                <div class="main-bodypopup">
                    <div class="modal-header ">

                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="col-sm-12 col-xs-12   text-center">
                        <br>
                        <br>
                        <h4> Terms & Conditions. </h4>

                        <div class="divider style-1 center">

                            <span class="hr-simple left"></span>

                            <i class="icofont icofont-ui-press hr-icon"></i>

                            <span class="hr-simple right"></span>

                        </div>

                        <div class="thanks-content">
                            <h4>Terms of Use</h4>
                            <p>Welcome to the Gyro Joint! We built our website and app to share information
                            about our company, our restaurants, and our foods; to make guides available that
                            show you how to make delicious food at home;
                                to provide updates related to our company; and to allow you to purchase our food.</p>
                            <h4>Privacy</h4>
                            <p> We value your privacy. Please review our privacy policy,
                                which is incorporated into these terms, to understand our privacy practices.</p>
                            <h4>Trademarks</h4>
                            <p>
                                Gyro Joint™ and the Gyro Joint logo are registered trademarks of Gyro Joint Group, Inc.
                                You may not use, copy, reproduce, republish, upload, post, transmit,
                                distribute, or modify these trademarks in anyway, including in
                                advertising or publicity pertaining to distribution of materials
                                on the Sites, without our prior written permission. All other names,
                                logos, product and service names, designs and slogans that may appear
                                on the Sites are the trademarks of their respective owners and may not be used,
                                copied, reproduced, republished, uploaded, posted, transmitted, distributed,
                                or modified without express permission from the respective owner. The use of
                                Gyro Joint trademarks on any other website is not allowed. We prohibit the use of
                                any of our trademarks as a
                                “hot” link on or to any other website unless establishment of such a link is approved in advance.
                            </p>
                            <h4>Other Intellectual Property Rights</h4>
                            <p>
                                The Sites and all content, features and functionality
                                (including but not limited to all information, software, text, displays,
                                images, graphics, photographs, illustrations, video and audio, and the design,
                                selection and arrangement thereof) (“Content”) are owned by Gyro Joint Group, Inc.,
                                its licensors, agents, or Content providers. The Sites and all Content are protected
                                by U.S. and international copyright, trademark, trade dress, patent, trade secret and
                                other intellectual property or proprietary rights laws.
                            </p>
                            <h4>You also agree not to:</h4>
                                <p>
                                Use the Sites in any manner or take any action that could, in our sole discretion, disable, overburden, impose an unreasonable or disproportionately large load, damage, or impair the Sites or interfere with any other party's use of the Sites.
                                Use any robot, spider, scraper or other automatic device, process or means to access the Sites for any purpose, including monitoring or copying any of the material on the Sites.
                                Use any device, software, routine, or take any other action that interferes or attempts to interfere with the proper working of the Sites or any activities conducted on or through the Sites.
                                Introduce any viruses, trojan horses, worms, logic bombs or other material which is malicious or technologically harmful.
                                Bypass, or attempt to bypass, any measures we may use to prevent or restrict access to the Sites or otherwise gain unauthorized access to, interfere with, damage or disrupt any parts of the Sites, the server on which the Sites are stored, or any server, computer or database connected to the Sites.
                                Attack our Sites via a denial-of-service attack or a distributed denial-of-service attack.</p>
                            <h4>Text Messaging</h4>
                            <p> By providing a cell phone to us you represent and warrant
                                that you are the owner or authorized primary user of the
                                device and you expressly consent to receiving text messages from
                                Gyro Joint from time to time, including messages about products that you
                                have ordered and promotional messages. If you no longer wish to receive
                                these messages, please contact us at [insert email address] or reply “STOP”
                                to the text message. Your carrier may charge you standard text messaging rates
                                for each message sent or received.</p>
                            <h4>Feedback</h4>
                            <p>We welcome your comments and suggestions regarding our Sites and the information,
                            products and services we make available here.
                            Contact us at info@gyrojoint.net to provide feedback.</p>
                        </div>
                        <br>

                    </div>
                </div>



            </div>
        </div>
    </div>

    <!-- Login End -->
@endsection
