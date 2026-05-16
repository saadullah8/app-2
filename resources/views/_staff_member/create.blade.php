@extends('layouts.theme')
@section('title','Create Customer')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Create Customer</h3>
      </div>
      <div class="box-body">
      @if(Session::has('error'))
           <div class="alert alert-warning alert-dismissable fade in">
              <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Warning! </strong>{{Session::get('error') }}
           </div>
      @endif

        <form role="form"  method="POST" action="{{url('staff') }}">
          {{ csrf_field() }}
          <!-- text input -->
          <div class="row">
            <div class="col-md-6">
              <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                <label class="control-label" >First Name</label>
                <input id="first_name" type="text" autocomplete="off" class="form-control" tabindex="1" placeholder="First Name" name="first_name" value="{{ old('first_name') }}" required autofocus>
                @if ($errors->has('first_name')) <span class="help-block"> <strong>{{ $errors->first('first_name') }}</strong> </span> @endif </div>
              <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="control-label" >Password</label>
                <input id="password" type="password" placeholder="Password" autocomplete="off"autocomplete="off" class="form-control" tabindex="3" name="password" required>
                @if ($errors->has('password')) <span class="help-block"> <strong>{{ $errors->first('password') }}</strong> </span> @endif </div>
              <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="control-label" >E-Mail Address</label>
                <input id="email" type="email" class="form-control" tabindex="5" autocomplete="off" placeholder="E-Mail Address" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email')) <span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span> @endif </div>
              </div>
            <div class="col-md-6">
              <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                <label class="control-label" >Last Name</label>
                <input id="last_name" type="text" class="form-control" autocomplete="off" tabindex="2" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}" required autofocus>
                @if ($errors->has('last_name')) <span class="help-block"> <strong>{{ $errors->first('last_name') }}</strong> </span> @endif </div>
              <div class="form-group {{ $errors->has('password-confirm') ? ' has-error' : '' }}">
                <label class="control-label" >Confirm Password</label>
                <input id="password-confirm" type="password" autocomplete="off" class="form-control" tabindex="4" placeholder="Confirm Password" name="password_confirmation" required>
              </div>


              <br>
              <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Add Customer</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection