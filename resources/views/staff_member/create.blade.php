@extends('layouts.theme')
@section('title','Create Staff')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">Create Staff</h3>
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
                <input id="first_name" type="text" class="form-control" tabindex="1" placeholder="First Name" name="first_name" value="{{ old('first_name') }}" required autofocus>
                @if ($errors->has('first_name')) <span class="help-block"> <strong>{{ $errors->first('first_name') }}</strong> </span> @endif </div>
              <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                <label class="control-label" >Password</label>
                <input id="password" type="password" placeholder="Password" class="form-control" tabindex="3" name="password" required>
                @if ($errors->has('password')) <span class="help-block"> <strong>{{ $errors->first('password') }}</strong> </span> @endif </div>
              <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="control-label" >E-Mail Address</label>
                <input id="email" type="email" class="form-control" tabindex="5" placeholder="E-Mail Address" name="email" value="{{ old('email') }}" required>
                @if ($errors->has('email')) <span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span> @endif
              </div>
              <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                <label class="control-label" >Phone Number</label>
                <input id="phone" type="text" class="form-control" data-mask="999-999-9999" tabindex="7" placeholder="Phone Number" name="phone" value="{{ old('phone') }}" required>
                @if ($errors->has('phone')) <span class="help-block"> <strong>{{ $errors->first('phone') }}</strong> </span> @endif
              </div>
              <div class="form-group {{ $errors->has('can_sms') ? ' has-error' : '' }}">

                <input type="checkbox" class="minimal" name="can_sms"  value="1" @if(old('can_sms'))checked @endif> <label class="control-label" > Online Order status updates via Sms</label>
                @if ($errors->has('can_sms')) <span class="help-block"> <strong>{{ $errors->first('can_sms') }}</strong> </span> @endif
              </div>
              <div class="form-group {{ $errors->has('can_marketing') ? ' has-error' : '' }}">

                <input type="checkbox" class="minimal" name="can_marketing"  value="1" @if(old('can_marketing'))checked @endif> <label class="control-label" > Would you like to receive our exclusive promotion updates via Sms?</label>
                @if ($errors->has('can_marketing')) <span class="help-block"> <strong>{{ $errors->first('can_marketing') }}</strong> </span> @endif
              </div>
              </div>


            <div class="col-md-6">
              <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                <label class="control-label" >Last Name</label>
                <input id="last_name" type="text" class="form-control" tabindex="2" placeholder="Last Name" name="last_name" value="{{ old('last_name') }}" required autofocus>
                @if ($errors->has('last_name')) <span class="help-block"> <strong>{{ $errors->first('last_name') }}</strong> </span> @endif </div>
              <div class="form-group {{ $errors->has('password-confirm') ? ' has-error' : '' }}">
                <label class="control-label" >Confirm Password</label>
                <input id="password-confirm" type="password" class="form-control" tabindex="4" placeholder="Confirm Password" name="password_confirmation" required>
              </div>

                <div class="form-group {{ $errors->has('role') ? ' has-error' : '' }}">
                    <label class="control-label " >Select Role</label>
                   <select class="form-control" tabindex="10" name="role_id">
                      <option value="">Select</option>
                        @foreach($roles as $role)
                            <option	value="{{$role->id}}" {{old('role_id')==$role->id ?'selected="selected"':""}}>{{$role->role}}</option>
                        @endforeach
                   </select>
                    @if ($errors->has('role_id')) <span class="help-block"> <strong>{{ $errors->first('role_id') }}</strong> </span> @endif </div>

              <br>
              <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Create Staff</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
  <script src="{{url('plugins/iCheck/icheck.min.js')}}"></script>
  <script>
      $(function () {
          //iCheck for checkbox and radio inputs
          $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
              checkboxClass: 'icheckbox_minimal-blue',
              radioClass   : 'iradio_minimal-blue'
          })
      })
  </script>

@endsection
@section('style')
  <link rel="stylesheet" href="{{url('plugins/iCheck/all.css')}}">
@endsection