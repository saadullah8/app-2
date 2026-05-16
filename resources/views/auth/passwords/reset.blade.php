@extends('layouts.master')
@section('title','Reset Password')
@section('content')
    <!-- Breadcrumb Start -->
    <!-- Breadcrumb Start -->
    <div class="bread-crumb">
        <div class="container">
            <div class="matter">
                <h2>Reset Password</h2>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="{{url('/')}}">HOME</a></li>
                    <li class="list-inline-item"><a href="#">Reset Password</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Login Start -->
    <div class="login">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-sm-12 commontop text-center">
                    <h4>Reset Password</h4>
                    <div class="divider style-1 center">
                        <span class="hr-simple left"></span>
                        <i class="icofont icofont-ui-press hr-icon"></i>
                        <span class="hr-simple right"></span>
                    </div>
                </div>
                <div class="col-lg-10 col-md-12">
                    <div class="row">
                        <div class="col-sm-12 col-md-6 offset-sm-0 offset-md-3 offset-lg-3">
                        @if (session('status'))
                            <div class="alert alert-success" role="alert">
                                {{ session('status') }}
                            </div>
                        @endif

                            <div class="loginnow ">
                                <h5>Reset Password</h5>
                                    <form method="POST" action="{{ route('password.update') }}">
                                    @csrf
                                     <input type="hidden" name="token" value="{{ $token }}">

                                    @if ($errors->has('email'))
                                        <span class="error-msg">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                    <div class="form-group">
                                        <i class="icofont icofont-ui-message"></i>
                                        <input type="email" name="email" value="{{ $email ?? old('email') }}" placeholder="EMAIL" id="email" class="form-control{{ $errors->has('email') ? ' has-error' : '' }}" />
                                    </div>

                                    @if ($errors->has('password'))
                                            <span class="error-msg">
                                                <strong>{{ $errors->first('password') }}</strong>
                                            </span>
                                        @endif
                                    <div class="form-group">
                                        <i class="icofont icofont-lock"></i>
                                        <input type="password" name="password" value="" placeholder="new-password" id="input-password"
                                        class="form-control{{ $errors->has('password') ? ' has-error' : '' }}" />
                                    </div>

                                    <div class="form-group">
                                        <i class="icofont icofont-lock"></i>
                                        <input type="password" name="password_confirmation" value="" placeholder="Confirm password" id="password-confirm"
                                        class="form-control{{ $errors->has('password') ? ' has-error' : '' }}" />
                                    </div>


                                    <div class="form-group">
                                        <input type="submit" value="Reset Password" class="btn btn-theme btn-md btn-wide" />
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