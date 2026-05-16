@extends('layouts.master')

@section('title','Order history')

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

                    <h4>Order Details</h4>

                    <div class="divider style-1 center">

                        <span class="hr-simple left"></span>

                        <i class="icofont icofont-ui-press hr-icon"></i>

                        <span class="hr-simple right"></span>

                    </div>

                </div>

                <div class="col-lg-12 col-md-12 user-profile">

                    <div class="row">

                        <div class="col-md-1 col-lg-1">


                        </div>

                        <div class="col-md-9 col-lg-10">

                            <div class="item-entry">


                                @if($order->remark!=null)
                                    <p>
                                        <strong>
                                            Remark:
                                        </strong>
                                        {{$order->remark}}
                                    </p>
                                @endif

                                @if($order->pickup_date!=null)
                                    <p>
                                        <strong>
                                            Pickup date:
                                        </strong>
                                        {{date('m-d-Y h:i:s A', strtotime($order->pickup_date))}}
                                    </p>

                                @endif

                                <a class="btn btn-info " style="color: #ffffff"
                                   href="{{url('user/dashboard')}}">Back</a><br>
                                <span class="order-id ">Order ID: {{$order->order_no}}</span>
                                <div class="item-content">

                                    <?php $order_detail = $order->details;  ?>

                                    @foreach($order_detail as $key => $detail)

                                        @if($detail->product_type=='fixed')

                                            <div class="item-body">

                                                <div class="row">

                                                    @php ($rows = json_decode($detail->detail))



                                                    @foreach($rows as  $row)

                                                        <?php $product = \App\Models\Product::find($row);?>

                                                        <div class="col-md-2 col-sm-2 text-center">

                                                            <img
                                                                src="{{url('site_images/product_images/'.$product->image)}}"
                                                                alt="{{$detail->product_name}}">

                                                        </div>

                                                        <div class="col-md-6 col-sm-6">

                                                            <h4>{{$detail->product_name}}</h4>

                                                            <p>{{$product->detail}}</p>

                                                        </div>

                                                    @endforeach

                                                    <div class="col-md-4 col-sm-3 text-right">

                                                        <p class="confirmed">Quantity : {{$detail->qty}}</p>

                                                        <p>${{$detail->price}} </p>

                                                    </div>

                                                </div>

                                            </div>

                                        @endif

                                        @if($detail->product_type=='customized')

                                            <div class="item-body">

                                                <div class="row">

                                                    @php ($rows = json_decode($detail->detail))

                                                    <?php $product = \App\Models\Product::where('title', $detail->product_name)->first();?>

                                                    <div class="col-md-2 col-sm-2 text-center">

                                                        <img
                                                            src="{{url('site_images/product_images/'.$product->image)}}"
                                                            alt="{{$detail->product_name}}">

                                                    </div>

                                                    <div class="col-md-6 col-sm-6">

                                                        <h4>{{$detail->product_name}}</h4>

                                                        <p>@foreach($rows as  $row){{$row}},&nbsp&nbsp @endforeach</p>

                                                    </div>

                                                    <div class="col-md-4 col-sm-3 text-right">

                                                        <p class="confirmed">Quantity : {{$detail->qty}}</p>

                                                        <p>${{$detail->price}} </p>

                                                    </div>

                                                </div>

                                            </div>

                                        @endif
                                    @endforeach


                                    <div class="item-footer">

                                        <p><strong>Order Date:</strong>{{date('d M Y',strtotime($order->created_at))}}
                                            @if($order->paymentType=="CashOfCreditCard")
                                                <br><strong>Strip Id :</strong> {{$order->stripPaymentId}}<br>
                                            @endif
                                            <span style="float: right">


<strong>Sub Total:</strong> ${{$order->subTotal}}<br>



<strong>Sales tax :</strong> ${{$order->taxAmount}}<br>
<strong>Discount:</strong> ${{$order->discountAmount}}<br>

<strong> &nbsp &nbsp &nbsp &nbsp &nbsp  Total:</strong> ${{$order->total_amount}}
    </span>


                                        </p>

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
