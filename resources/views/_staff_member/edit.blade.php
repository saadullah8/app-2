 @extends('layouts.theme')
 @section('title','Edit Customer')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Edit Customer</h3>
      </div>
      <div class="box-body">
       @if(Session::has('error'))
         <div class="alert alert-warning alert-dismissable fade in">
                       <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
            <strong>Warning!</strong>{{ Session::get('error') }}
          </div>
       @endif

        <form role="form"  method="POST" action="{{url('staff/'.encrypt($member->id)) }}">
          {{ csrf_field() }}
          <input type="hidden" name="_method" value="PUT">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group {{ $errors->has('first_name') ? ' has-error' : '' }}">
                <label class="control-label" >First Name</label>
                <input id="first_name" type="text" class="form-control"  tabindex="1" name="first_name" value="{{ $member->first_name }}" required autofocus>
                @if ($errors->has('first_name')) <span class="help-block"> <strong>{{ $errors->first('first_name') }}</strong> </span> @endif </div>

                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                    <label class="control-label" >Password</label>
                    <input id="password" type="password" class="form-control" placeholder="Password" tabindex="3" name="password" >
                   @if ($errors->has('password')) <span class="help-block"> <strong>{{ $errors->first('password') }}</strong> </span> @endif </div>

              <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="control-label" >E-Mail Address</label>
                <input id="email" type="email" class="form-control"  name="email" value="{{ $member->email }}" required="" >
                @if ($errors->has('email')) <span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span> @endif </div>


            </div>
            <div class="col-md-6">
              <div class="form-group {{ $errors->has('last_name') ? ' has-error' : '' }}">
                <label class="control-label" >Last Name</label>
                <input id="last_name" type="text" class="form-control" name="last_name" value="{{ $member->last_name }}" required autofocus>
                @if ($errors->has('last_name')) <span class="help-block"> <strong>{{ $errors->first('last_name') }}</strong> </span> @endif </div>

                <div class="form-group {{ $errors->has('password-confirm') ? ' has-error' : '' }}">
                    <label class="control-label" >Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control" tabindex="4" placeholder="Confirm Password" name="password_confirmation" >
                    @if ($errors->has('password-confirm')) <span class="help-block"> <strong>{{ $errors->first('password-confirm') }}</strong> </span> @endif
                </div>

              <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Submit</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection