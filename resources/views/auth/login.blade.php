@extends('layouts.master')
@section('title','Login')
@section('content')
    <!-- Breadcrumb Start -->
    <!-- Breadcrumb Start -->
    <div class="bread-crumb">
        <div class="container">
            <div class="matter">
                <h2>Login</h2>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="{{url('/')}}">HOME</a></li>
                    <li class="list-inline-item"><a href="#">Login</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Login Start -->
    <div class="login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 commontop text-center">
                    <h4>Login to Your Account</h4>
                    <div class="divider style-1 center">
                        <span class="hr-simple left"></span>
                        <i class="icofont icofont-ui-press hr-icon"></i>
                        <span class="hr-simple right"></span>
                    </div>
                </div>
                <div class="col-lg-10 col-md-12">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 offset-sm-0 offset-md-3 offset-lg-3">
                            <div class="loginnow ">
                                <h5>Login</h5>
                                <p>Don't have an account? So <a href="{{url('register')}}">Register</a> And starts less than a minute</p>
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    @if ($errors->has('email'))
                                        <span class="error-msg">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                    <div class="form-group">
                                        <i class="icofont icofont-ui-message"></i><input type="email" name="email" value="" placeholder="EMAIL" id="email" class="form-control{{ $errors->has('email') ? ' has-error' : '' }}" />
                                    </div>
                                        @if ($errors->has('password'))
                                            <span class="error-msg">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    <div class="form-group">
                                        <i class="icofont icofont-lock"></i><input type="password" name="password" value="" placeholder="PASSWORD" id="input-password" class="form-control{{ $errors->has('password') ? ' has-error' : '' }}" />
                                    </div>
                                    <div class="form-group">
                                        <div class="links">
                                            <label><input type="checkbox" class="radio-inline" name="remember" {{ old('remember') ? 'checked' : '' }}/>Remember Me</label>
                                             <a class="float-right sign" href="{{url('password/reset')}}">Forgot Password?</a>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="submit" value="SIGN IN" class="btn btn-theme btn-md btn-wide" />
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Login End -->
@endsection
