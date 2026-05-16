@extends('layouts.theme')
@section('title')
Orders total: {{$counter}}
@endsection

@section('style')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{url("plugins/datatables-bs4/css/dataTables.bootstrap4.min.css")}}">
    <link rel="stylesheet" href="{{url("plugins/datatables-responsive/css/responsive.bootstrap4.min.css")}}">
    <link rel="stylesheet" href="{{url("plugins/datatables-buttons/css/buttons.bootstrap4.min.css")}}">
<style>
    .buttons-pdf{
        background: #f39c12;
    } .buttons-excel{
        background: #00a65a;
    }
      .btn{
          margin: 0px 17px 0px 0px;
      }
</style>
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
            <h4 class="card-title">Filter Orders</h4>
            <form  method="post" action="{{url('export/orders')}}">
                @csrf
                <div class="row">

                    <div class="col-lg-3">
                        <label>From date</label>
                        <input type="date"  required name="fromDate" class="form-control" value="{{$fromDate}}">
                    </div>
                    <div class="col-lg-3">
                        <label>To Date</label>
                        <input type="date" required name="toDate" class="form-control" value="{{$toDate}}">
                    </div>
                    <div class="col-lg-3">
                        <label>Order Status</label>
                        <select class="form-control" required name="status">
                            <option value="active" @if($status=='active') selected @endif>New Order Order</option>
                            <option value="completed" @if($status=='completed') selected @endif>Ready Order</option>
                            <option value="canceled" @if($status=='canceled') selected @endif>Canceled Order</option>
                        </select>
                    </div>
                    <div class="col-lg-2">
                        <br>
                        <button type="submit" class="btn btn-default text-right btn-flat btn-md">Submit</button>
                    </div>
                </div>
            </form>
            <hr>
            <h4 class="text-info"> Order Total: {{$counter}}</h4>
        </div>

        <div class="box-body table-responsive">
            <table id="orders" class="table table-bordered table-striped d-table" >
                <thead>
                <tr>
                    <th>Sr. #</th>
                    <th>Order Number</th>
                    <th>Customer Name</th>
                    <th>Order Type</th>
                    <th>total price</th>
                    <th>Date & Time</th>

                </tr>
                </thead>
                <tbody>


                @foreach($orders as $key=>$order)
                    <tr>
                        <td>{{$key+1}}</td>
                        <td>{{$order->order_no}} </td>
                        <td>{{$order->userDetail?$order->first_name.' '.$order->userDetail->last_name:"N/A"}}</td>
                        <td>
                            @if($order->order_status==0)
                                New Order
                            @elseif($order->order_status==1)
                                Ready Order
                            @else
                                Canceled Order
                            @endif
                        </td>
                        <td>{{$order->total_amount}} </td>
                        <td>{{date('d-M-Y h:i:s', strtotime($order->created_at))}}</td>
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
    <script src="{{url("plugins/datatables/jquery.dataTables.min.js")}}"></script>
    <script src="{{url("plugins/datatables-bs4/js/dataTables.bootstrap4.min.js")}}"></script>
    <script src="{{url("plugins/datatables-responsive/js/dataTables.responsive.min.js")}}"></script>
    <script src="{{url("plugins/datatables-responsive/js/responsive.bootstrap4.min.js")}}"></script>
    <script src="{{url("plugins/datatables-buttons/js/dataTables.buttons.min.js")}}"></script>
    <script src="{{url("plugins/datatables-buttons/js/buttons.bootstrap4.min.js")}}"></script>
    <script src="{{url("plugins/jszip/jszip.min.js")}}"></script>
    <script src="{{url("plugins/pdfmake/pdfmake.min.js")}}"></script>
    <script src="{{url("plugins/pdfmake/vfs_fonts.js")}}"></script>
    <script src="{{url("plugins/datatables-buttons/js/buttons.html5.min.js")}}"></script>
    <script src="{{url("plugins/datatables-buttons/js/buttons.print.min.js")}}"></script>
    <!-- AdminLTE App -->
    <script>
        $("#orders").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": [ "csv", "excel", "pdf", "print"]
        }).buttons().container().appendTo('#orders_wrapper .col-md-6:eq(0)');
    </script>
@endsection
