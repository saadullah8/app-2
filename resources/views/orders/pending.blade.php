@extends('layouts.theme')
@section('title','Order list')
@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{url('plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')

    <div class="box box-info">
        <div class="box-header">

            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('success') }}
                </div>
            @endif

            @if(Session::has('error'))

                <div class="alert alert-error alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('error') }}
                </div>
            @endif

            <h3 class="box-title">Order  List</h3>

        </div>
        <div class="box-body table-responsive">
            <table id="pending_orders" class="table table-bordered table-striped d-table" >
                <thead>
                <tr>
                    <th>Sr. #</th>
                    <th>Order Number</th>
                    <th>Customer Name</th>
                    <th>Date & Time</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>


                @foreach($orders as $key=>$order)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td><a href="{{url('orderDetail/'.encrypt($order->id))}}">{{$order->order_no}}</a> </td>
                        <td>{{$order->userDetail->first_name.' '.$order->userDetail->last_name}}</td>

                        <td>{{date('d-M-Y h:i:s', strtotime($order->created_at))}}</td>
                        <td class="font-Size">

                            <a href="{{url('orderDetail/'.encrypt($order->id))}}" class="action_imgs btn btn-app view_btn" >
                                <i class="fa fa-eye" ></i> view
                            </a>
                            @if($order->count_confirm==0)
                            <a href="{{url('edit-order/'.encrypt($order->id))}}" class="action_imgs btn btn-app view_btn" style="background: #767471">
                                <i class="fa fa-edit" ></i> Edit
                            </a>
                            @endif
                            <a href="{{url('order/status/'.encrypt($order->id))}}" class="action_imgs btn btn-app ready_btn"
                               data-content="{{encrypt($order->id)}}"
                                    onclick="event.preventDefault();
                                    document.getElementById('order-status-{{$order->id}}').submit();">
                                <i class="fa fa-spinner" ></i>Ready
                            </a>
                            {{--{{url('order/printer/'.encrypt($order->id))}}--}}
                            <a href="javascript:;"   class="action_imgs btn btn-app print_page print_btn" data-content="{{encrypt($order->id)}}">
                                <i class="fa fa-print" ></i> Print
                            </a>
                             <a href="{{url('order/status/'.encrypt($order->id))}}" class="action_imgs btn btn-app remove_btn"
                                onclick="event.preventDefault();  document.getElementById('order-deleted-{{$order->id}}').submit();">
                                 <i class="fa fa-trash" ></i> Delete
                             </a>

                            @if($order->count_confirm!=1)
                            <a href="{{url('order/status/'.encrypt($order->id))}}" class=" btn btn-app sms_btn btn-info"
                               onclick="event.preventDefault();  document.getElementById('order-sms-{{$order->id}}').submit();">
                                <i class="fa fa-envelope" ></i> Confirm Order
                            </a>
                                <form action="{{url('sendSms')}}" method="post" id="order-sms-{{$order->id}}">
                                    {{csrf_field()}}
                                    <input name="type" type="hidden" value="new_order_tab">
                                    <input name="order_id" type="hidden" value="{{encrypt($order->id)}}">
                                </form>

                                @else
                                <button type="button" class="btn btn-app sms_btn disabled btn-warning"><i class="fa fa-envelope" ></i>Confirm Order</button>
                            @endif

                            <form action="{{url('order/status')}}" method="post" id="order-deleted-{{$order->id}}">
                              {{csrf_field()}}
                              <input name="status" type="hidden" value="2">
                              <input name="id" type="hidden" value="{{encrypt($order->id)}}">
                            </form>

                            <form action="{{url('order/status')}}" method="post" id="order-status-{{$order->id}}">
                             {{csrf_field()}}
                             <input name="status" type="hidden" value="1">
                             <input name="id" type="hidden" value="{{encrypt($order->id)}}">
                           </form>




                        </td>
                    </tr>

                @endforeach
                </tbody>

            </table>
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
    setInterval(function() {
                      window.location.reload();
                    }, 30000);

        $(document).ready(function() {
            $('#pending_orders').DataTable({response:true});

            $(".deleted").click(function() {
                var deleted_id=$(this).data("set_id");
                $('#deleted_item').val(deleted_id);
                $("#deleted_model").modal('show');
            });
        });


    </script>
@endsection
