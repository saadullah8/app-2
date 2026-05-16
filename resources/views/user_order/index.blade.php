@extends('layouts.master')
@section('title','Dashboard')
    @section('style')
        <style>
            .add-btn{
                color:red ;
            }
        </style>
    @endsection
@section('content')
<!-- Breadcrumb Start -->
<div class="bread-crumb">
    <div class="container">
        <div class="matter">
            <h2>Dashboard</h2>
            <ul class="list-inline">
                <li class="list-inline-item"><a href="{{url('/')}}">HOME</a></li>
                <li class="list-inline-item"><a href="{{url('user/dashboard')}}">Dashboard</a></li>
            </ul>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Login Start -->
<div class="dashboard">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-sm-12 commontop text-center">
                <h4>User Dashboard</h4>
                <div class="divider style-1 center">
                    <span class="hr-simple left"></span>
                    <i class="icofont icofont-ui-press hr-icon"></i>
                    <span class="hr-simple right"></span>
                </div>
            </div>
            <div class="col-lg-12 col-md-12 user-profile">
                <div class="row">
                    <div class="col-md-3 col-lg-2">
                        <div class="user-profile-tabs">
                            <!--  Menu Tabs Start  -->
                            <ul class="nav nav-tabs flex-column">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#overview" aria-expanded="true">
                                        <i class="icofont icofont-dashboard-web"></i>
                                        <span>Profile</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " data-toggle="tab" href="#orderstab" aria-expanded="true">
                                        <i class="icofont icofont-ui-user"></i>
                                        <span>Order History</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#creditcard" aria-expanded="true">
                                        <i class="icofont icofont-ui-v-card"></i>
                                        <span>Credit Cards</span>
                                    </a>
                                </li>

                            </ul>
                            <!--  Menu Tabs Start  -->
                        </div>
                    </div>
                    <div class="col-md-9 col-lg-10">

                        <div class="tab-content">
                            <div id="overview" class="tab-pane fade active show">
                            <div class="row">
                            <div class="col-md-12">
                            @if(Session::has('success'))
                            <div class="alert alert-success alert-dismissible">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                             {{ Session::get('success') }}
                            </div>
                            @endif
                            @if(Session::has('error'))
                            <div class="alert alert-danger alert-dismissible">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                                {{ Session::get('error') }}
                            </div>
                            @endif
                            @if(count($errors)> 0)
                            <div class="alert alert-danger alert-dismissible">
                              <button type="button" class="close" data-dismiss="alert">&times;</button>
                             @if (count($errors) > 0)

                                     <ul>
                                         @foreach ($errors->all() as $error)
                                             <li>{{ $error }}</li>
                                         @endforeach
                                     </ul>

                             @endif

                            </div>
                            @endif
                            </div>
                            </div>
                                <div class="row">

                                    <div class="col-lg-12">
                                     <h5>Profile</h5>
                                        <div class="brief-info">
                                            <div class="media">
                                                <img class="mr-3" src="{{url('/site_images/profile_images/'.\Illuminate\Support\Facades\Auth::user()->image)}}" alt="User Profile" />
                                                <div class="media-body">
                                                    <h4>{{\Illuminate\Support\Facades\Auth::user()->first_name." ".\Illuminate\Support\Facades\Auth::user()->last_name}}</h4>
                                                    <p><i class="icofont icofont-envelope"></i>{{\Illuminate\Support\Facades\Auth::user()->email}}</p>
                                                    <p><i class="icofont icofont-phone"></i> {{\Illuminate\Support\Facades\Auth::user()->phone}}</p>
                                                    <p><i class="icofont icofont-location-pin"></i>{{\Illuminate\Support\Facades\Auth::user()->address}}</p>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="user-personal-info">
                                            <h5>Personal Information</h5>
                                            <div class="user-info-body">
                                                <form action="{{url('profile/update')}}" enctype="multipart/form-data" method="post">
                                                    {{csrf_field()}}
                                                    <div class="form-row">
                                                        <div class="form-group col-md-6">
                                                            <input name="first_name"  value="{{\Illuminate\Support\Facades\Auth::user()->first_name}}" placeholder="First Name" class="form-control" type="text">
                                                        </div>
                                                        <div class="form-group col-md-6">
                                                            <input name="last_name"  value="{{\Illuminate\Support\Facades\Auth::user()->last_name}}" placeholder="Last Name" class="form-control" type="text">
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-12">
                                                            <input name="phone"  value="{{\Illuminate\Support\Facades\Auth::user()->phone}}" data-mask="999-999-9999" placeholder="Contact Number" class="form-control" type="text">
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-12">
                                                            <input type="checkbox" name="can_sms" value="1" @if(\Illuminate\Support\Facades\Auth::user()->can_sms)checked @endif class="checkbox-inline">
                                                            Online Order status updates via Sms
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group col-12">
                                                            <input type="checkbox" name="can_marketing" value="1" @if(\Illuminate\Support\Facades\Auth::user()->can_marketing)checked @endif class="checkbox-inline">
                                                            Would you like to receive our exclusive promotion updates via Sms?
                                                        </div>
                                                    </div>


                                                    <div class="form-row">
                                                        <div class="form-group col-12">
                                                            <textarea name="address" placeholder="Your Current Address" id="current-address" class="form-control" rows="3">{{\Illuminate\Support\Facades\Auth::user()->address}}</textarea>
                                                        </div>
                                                    </div>

                                                    <div class="form-row">
                                                        <div class="form-group col-12">
                                                            <label>Upload Photo</label>
                                                            <input name="image" class="upload-pic form-control-file" type="file" >
                                                        </div>
                                                    </div>
                                                    <div class="form-row">
                                                        <div class="form-group mb-0 pt-4 col-12 text-center">
                                                            <button class="btn btn-theme btn-md" type="submit">SAVE CHANGES</button>

                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="user-change-password">
                                            <h5>Change Password</h5>
                                            <div class="change-password-body">
                                                <form   method="POST" action="{{url('change/password/') }}" enctype="multipart/form-data">
                                                    {{csrf_field()}}
                                                    <div class="form-group">
                                                        <input placeholder="Old Password" class="form-control" name="oldpassword" type="password">
                                                    </div>
                                                    <div class="form-group">
                                                        <input placeholder="New Password" class="form-control" name="password" type="password">
                                                    </div>
                                                    <div class="form-group">
                                                        <input placeholder="Confirm Password" class="form-control" name="confirm_password" type="password">
                                                    </div>
                                                    <div class="form-group mb-0 pt-4 text-center">
                                                        <button class="btn btn-theme btn-md" type="submit">SAVE CHANGES</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="orderstab" class="tab-pane fade  show">
                                <div class="row">

                                    <div class="col-lg-12">
                                        <div class="most-recent-order" style="margin:0px">
                                            <h5>Recent Orders</h5>
                                            @foreach($orders as $value)
                                                <div class="field-entry">
                                                    <div class="row">
                                                        <div class="col-3">
                                                            <p>{{$value->order_no}}</p>
                                                        </div>
                                                        <div class="col-5">
                                                            @if($value->order_status==0)
                                                                <p class="text-info">
                                                                    <i class="icofont icofont-eye"></i>
                                                                    Pending</p>
                                                            @elseif($value->order_status==1)
                                                                <p class="text-primary">
                                                                    <i class="icofont icofont-history"></i>
                                                                    Ready To Pickup</p>
                                                            @else
                                                                <p class="failed"><i class="icofont icofont-close"></i>Cancel</p>
                                                            @endif
                                                        </div>
                                                        <div class="col text-right">

                                                            <a href="javascript:;" class="btn btn-warning" style="color: #ffffff"  onclick="event.preventDefault();document.getElementById('reorder-{{$value->id}}').submit();"> Re Order </a>
                                                            <form action="{{url('reorder')}}" method="post" id="reorder-{{$value->id}}">
                                                                @csrf
                                                                <input name="order_id" type="hidden" value="{{encrypt($value->id)}}">
                                                            </form>
                                                        </div>
                                                        <div class="col text-right">
                                                            <a class="btn btn-info " style="color: #ffffff" href="{{url('order/detail/'.encrypt($value->id))}}">View</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div id="creditcard" class="tab-pane fade  show">
                                <div class="row">

                                    <div class="col-lg-12">
                                        <div class="most-recent-order" style="margin:0px">
                                            <h5>Credit Cards</h5>
                                            @foreach($cards as $card)
                                                <div class="field-entry">
                                                    <div class="row">
                                                        <div class="col-3">
                                                                <img src="{{url('img/credit/'.str_replace(' ','-',strtolower($card->brandImageURL)).'.png')}}" class="img-thumbnail">
                                                        </div>
                                                        <div class="col-3">
                                                            <p>****{{$card->lastDigits}}</p>
                                                        </div>
                                                        <div class="col-3 text-right">
                                                            <a href="javascript:;" class="btn btn-danger" style="color: #ffffff"  onclick="event.preventDefault();document.getElementById('deleteCard-{{$card->id}}').submit();"> Delete </a>
                                                            <form action="{{url('cards/delete')}}" method="post" id="deleteCard-{{$card->id}}">
                                                                @csrf
                                                                <input name="cardId" type="hidden" value="{{encrypt($card->id)}}">
                                                            </form>
                                                        </div>
                                                        <div class="col-3 text-right">
                                                            @if(!$card->isDefault)
                                                            <a class="btn btn-info " style="color: #ffffff" href="{{url('cards/marked/'.encrypt($card->id))}}">Mark as default</a>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach

                                        </div>
                                    </div>


                                </div>
                            </div>


                        </div>
                    </div>



                </div>
            </div>
        </div>
    </div>
</div>
<!-- Login End -->
@endsection
@section('script')
<script>
 setInterval(function() {
  window.location.reload();
}, 60000);
</script>
@endsection
