@extends('layouts.authentication')

@section('content')

    <div class="az-signin-wrapper">
        <div class="az-card-signin">
            <img src="{{url('logo.png')}}" style="width: 160px"/>@if ($errors->any())
                {!! implode('', $errors->all('<div class="alert-danger">:message</div>')) !!}
            @endif

            <div class="az-signin-header">
                <h2>Welcome To Finanspan!</h2>
                <h4>Please sign in to continue</h4>


                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <div class="form-group">
                        <label>User Name</label>
                        <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Username">
                    </div><!-- form-group -->
                    <div class="form-group">
                        <label>{{ __('Password') }}</label>
                        <input type="password" name="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" placeholder="Enter Password" required autocomplete="current-password">
                        @if ($errors->has('password'))
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                        @endif
                    </div><!-- form-group -->
                    <div class="form-group">
                        <input class="styled-checkbox" id="styled-checkbox-1" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="styled-checkbox-1">{{ __('Remember Me') }}</label>
                    </div>
                    <input type="submit" value="Sign In" class="btn btn-az-primary btn-block">
                </form>
            </div><!-- az-signin-header -->
            <div class="az-signin-footer">
                @if (Route::has('password.request'))
                <p><a href="{{ route('password.request') }}" class="forget">{{ __('Forgot Your Password?') }}</a></p>
                @endif

            </div><!-- az-signin-footer -->
        </div><!-- az-card-signin -->
    </div><!-- az-signin-wrapper -->


@endsection
