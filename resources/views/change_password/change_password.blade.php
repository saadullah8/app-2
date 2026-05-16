@extends('layouts.theme')
@section('title','Change Password')

@section('content')
    <div class="row">
        <div class="col-md-12">

                 @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Success! </strong>{{Session::get('success') }}
                    </div>
                    @elseif(Session::has('error'))
                    <div class="alert alert-warning alert-dismissable fade in">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Warning! </strong>{{Session::get('error') }}
                    </div>
                @endif
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Change Password</h3>
                </div>

                <div class="box-body">

                    <form role="form"  method="POST" action="{{url('change/password/') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}
                                <!-- text input -->
                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('oldpassword') ? ' has-error' : '' }}">
                                    <label class="control-label" >Old Password</label>
                                    <input type="password" class="form-control" placeholder="Old Password" value="{{old('oldpassword')}}" name="oldpassword" required  autocomplete="off">
                                    @if ($errors->has('oldpassword')) <span class="help-block"> <strong>{{ $errors->first('oldpassword') }}</strong> </span> @endif
                                </div>
                            </div>


                            <div class="col-md-4">

                                <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label class="control-label" >New Password</label>
                                    <input type="password" class="form-control" placeholder="New Password" value="{{old('password')}}" name="password" required  autocomplete="off">
                                    @if ($errors->has('password')) <span class="help-block"> <strong>{{ $errors->first('password') }}</strong> </span> @endif
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group {{ $errors->has('confirm_password') ? ' has-error' : '' }}">
                                    <label class="control-label" >Confirm Password</label>
                                    <input  type="password" class="form-control" placeholder="Confirm Password" name="confirm_password" value="{{old('confirm_password')}}" required  autocomplete="off">
                                    @if ($errors->has('confirm_password')) <span class="help-block"> <strong>{{ $errors->first('confirm_password') }}</strong> </span> @endif
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-info pull-right">Change Password</button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection