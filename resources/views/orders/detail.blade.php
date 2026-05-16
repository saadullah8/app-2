@extends('layouts.theme')

@section('title','Order Details')

@section('style')

    <!-- DataTables -->

    <link rel="stylesheet" href="{{url('plugins/datatables/dataTables.bootstrap.css')}}">

@endsection



@section('content')



    <div class="box box-info">

        <div class="box-header ">

            <h3 class="box-title">Order Detail</h3>


            @if($order->order_status==0)

                <a href="{{url('order/status/'.encrypt($order->id))}}"

                   onclick="event.preventDefault(); document.getElementById('order-{{$order->id}}').submit();"
                   class="btn btn-primary pull-right">Order Process</a>



                <form action="{{url('order/status')}}" method="post" id="order-{{$order->id}}">

                    {{csrf_field()}}

                    <input name="status" type="hidden" value="1">

                    <input name="id" type="hidden" value="{{encrypt($order->id)}}">

                </form>

            @endif

            @if($order->order_status==1)

                <a href="{{url('order/status/'.encrypt($order->id))}}"

                   onclick="event.preventDefault(); document.getElementById('order-{{$order->id}}').submit();"
                   class="btn btn-primary pull-right">Order Delete</a>

                <form action="{{url('order/status')}}" method="post" id="order-{{$order->id}}">

                    {{csrf_field()}}

                    <input name="status" type="hidden" value="2">

                    <input name="id" type="hidden" value="{{encrypt($order->id)}}">

                </form>

            @endif


        </div>

        <div class="box-body">

            <a class="btn btn-primary pull-right print_page" data-content="{{encrypt($order->id)}}" href="javascript:;">

                <i class="fa fa-print"></i> </a>

            <div class="row">

                <div class="col-md-6">


                    @foreach($order->details as $detail)

                        @if($detail->product_type=='fixed')

                            <h3>{{$detail->product_name}}(${{$detail->price}})</h3>
                            <ul>
                                <li><strong>Quantity : {{$detail->qty}}</strong></li>
                            </ul>
                        @endif

                        @if($detail->product_type=='customized')

                            <h3>{{$detail->product_name}}(${{$detail->price}})<br></h3>

                            @if($detail->name!='')

                                <p><strong>Specific Person Name : </strong>{{$detail->name}}</p>

                            @endif

                            @php ($rows = json_decode($detail->detail))

                            <ul>

                                <li><strong>Quantity : {{$detail->qty}}</strong></li>

                                @foreach($rows as $row)

                                    <li style="margin-left: 15px;">{{$row}}</li>

                                @endforeach

                            </ul>

                            @if($detail->note!='')

                                <p><strong>Special instructions: </strong>{{$detail->note}} </p>

                            @endif

                        @endif


                    @endforeach


                </div>

                <div class="col-md-6">

                    <h3>Customer Detail </h3>

                    <p>

                        <strong> Name:</strong> {{$order->userDetail->first_name.' '.$order->userDetail->last_name}}<br>

                        <strong> Address:</strong> {{$order->userDetail? $order->userDetail->address:'No address'}}<br>

                        <strong> Phone: </strong>{{$order->userDetail? $order->userDetail->phone:'No Phone'}}<br>

                        <strong> email:</strong> {{$order->userDetail->email}}
                    </p>
                    @if(count($order->details)>0)

                        <h4 style="font-family: -webkit-body;" class="text-centers text-info"><strong>Sub Total: &nbsp
                                ${{$order->subTotal}}</strong></h4>
                        <h4 style="font-family: -webkit-body;" class="text-centers text-info"><strong>Sales Tax: &nbsp
                                &nbsp ${{$order->taxAmount}}</strong></h4>
                        <h4 style="font-family: -webkit-body;" class="text-centers text-info"><strong>Discount: &nbsp
                                &nbsp ${{$order->discountAmount}}</strong></h4>

                        <h4 style="font-family: -webkit-body;" class="text-centers text-info"><strong>Total:
                                &nbsp&nbsp&nbsp<span class="">${{$order->total_amount}}</span></strong></h4>

                    @endif
                    <br>
                    <br>
                    @if($order->remark!=null)
                        <p>
                            <strong>
                                Remark:
                            </strong>
                            {{$order->remark}}
                        </p>
                    @endif

                    @if($order->is_pickup)
                        <p>
                            <strong>
                                Pickup date:
                            </strong>
                            {{date('m-d-Y h:i:s A', strtotime($order->pickup_date))}}
                        </p>

                    @endif
                    @if($order->paymentType=="CashOfCreditCard")
                        <p>
                            <strong>
                                Online Pay
                            </strong>
                            Stripe id: <a href="{{$order->stripInvoiceURL}}" target="_blank"
                                          class="link"> {{$order->stripPaymentId}}</a>
                        </p>
                    @else
                        <p>
                            <strong>
                                Pay at store
                            </strong>
                        </p>
                    @endif


                </div>

            </div>

        </div>

        <!-- /.box-body -->

    </div>

    <div id="models"></div>

@endsection

@section('script')

    <!-- DataTables -->

    <script src="{{url('plugins/datatables/jquery.dataTables.min.js')}}"></script>

    <script src="{{url('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>

    <script>

        $(document).ready(function () {

            $(".deleted").click(function () {

                var deleted_id = $(this).data("set_id");

                $('#deleted_item').val(deleted_id);

                $("#deleted_model").modal('show');

            });

        });

    </script>

@endsection
