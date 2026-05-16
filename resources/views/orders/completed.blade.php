@extends('layouts.theme')
@section('title','Completed Orders')
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

            <h3 class="box-title">Completed Order  List</h3>

        </div>
        <div class="box-body table-responsive">
            <table id="pending_orders" class="table table-bordered table-striped d-table" >
                <thead>
                <tr>
                    <th>Sr. #</th>
                    <th>Order Number</th>
                    <th>Customer Name</th>
                        <th>Status</th>
                    <th>Print</th>

                </tr>
                </thead>
                <tbody>
                @foreach($orders as $key=>$order)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td><a href="{{url('orderDetail/'.encrypt($order->id))}}">{{$order->order_no}}</a> </td>

                        <td>{{$order->userDetail->first_name.' '.$order->userDetail->last_name}}</td>
                        <td>Completed Order</td>
                        <td>  <a href="{{url('order/printer/'.encrypt($order->id))}}" class="action_imgs" >
                           <i class="fa fa-print" ></i> </a>
                             | <a href="{{url('order/print/'.encrypt($order->id))}}" target="_blank" class="ac" style="letter-spacing:0px">
                                                                    kitchen receipt </a>
                           </td>

                    </tr>

                @endforeach
                </tbody>

            </table>
        </div>
        <!-- /.box-body -->
    </div>

@endsection
@section('script')
    <!-- DataTables -->
    <script src="{{url('plugins/datatables/jquery.dataTables.min.js')}}"></script>
    <script src="{{url('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
    <script>
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