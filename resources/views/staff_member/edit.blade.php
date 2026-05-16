 @extends('layouts.theme')
 @section('title','Edit Staff')
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-warning">
      <div class="box-header with-border">
        <h3 class="box-title">Edit Staff</h3>
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
                    <input id="password" type="password" class="form-control"  tabindex="3" name="password" autocomplete="off">
                   @if ($errors->has('password')) <span class="help-block"> <strong>{{ $errors->first('password') }}</strong> </span> @endif </div>

              <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                <label class="control-label" >E-Mail Address</label>
                <input id="email" type="email" class="form-control"  name="email" value="{{ $member->email }}" required="" >
                @if ($errors->has('email')) <span class="help-block"> <strong>{{ $errors->first('email') }}</strong> </span> @endif </div>
                <div class="form-group {{ $errors->has('phone') ? ' has-error' : '' }}">
                    <label class="control-label" >Phone Number</label>
                    <input id="phone" type="text" class="form-control" data-mask="999-999-9999" tabindex="7" placeholder="Phone Number" name="phone" value="{{  $member->phone}}" required>
                    @if ($errors->has('phone')) <span class="help-block"> <strong>{{ $errors->first('phone') }}</strong> </span> @endif
                </div>
                <div class="form-group {{ $errors->has('can_sms') ? ' has-error' : '' }}">

                    <input type="checkbox" class="minimal" name="can_sms"  value="1" @if($member->can_sms)checked @endif>
                    <label class="control-label" > Online Order status updates via Sms</label>
                    @if ($errors->has('can_sms')) <span class="help-block"> <strong>{{ $errors->first('can_sms') }}</strong> </span> @endif
                </div>
                <div class="form-group {{ $errors->has('can_marketing') ? ' has-error' : '' }}">

                    <input type="checkbox" class="minimal" name="can_marketing"  value="1" @if($member->can_marketing)checked @endif>
                    <label class="control-label" > Would you like to receive our exclusive promotion updates via Sms?</label>
                    @if ($errors->has('can_marketing')) <span class="help-block"> <strong>{{ $errors->first('can_marketing') }}</strong> </span> @endif
                </div>

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
              <div class="form-group {{ $errors->has('role_id') ? ' has-error' : '' }}">
                  <label class="control-label " >Select Role</label>
                 <select class="form-control" name="role_id">
                    <option value="">Select</option>
                      @foreach($roles as $role)
                          <option 	@if($role->id==$member->role_id) {{'selected="selected"'}} @endif value="{{$role->id}}">{{$role->role}}</option>
                      @endforeach
                 </select>
                  @if ($errors->has('role_id')) <span class="help-block"> <strong>{{ $errors->first('role_id') }}</strong> </span> @endif </div>
              <div class="form-group">
                <button type="submit" class="btn btn-info pull-right">Update Staff</button>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection