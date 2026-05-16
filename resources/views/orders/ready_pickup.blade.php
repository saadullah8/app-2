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
                        <td>{{$order->userDetail? $order->userDetail->first_name.' '.$order->userDetail->last_name:''}}</td>
                       <td>{{date('d-M-Y h:m:s', strtotime($order->updated_at))}}</td>
                        <td class="font-Size">
                            <a href="{{url('orderDetail/'.encrypt($order->id))}}" class="action_imgs">
                                <i class="fa fa-eye" ></i>
                            </a> |
                            <a href="{{url('order/status/'.encrypt($order->id))}}" class="action_imgs print_page"
                            data-content="{{encrypt($order->id)}}"
                            {{--onclick="event.preventDefault();--}}
                                 {{--document.getElementById('order-status-{{$order->id}}').submit();"--}}
                                 > <i class="fa fa-spinner" ></i> </a>

                           | <a href="{{url('order/status/'.encrypt($order->id))}}"
                           class="action_imgs" onclick="event.preventDefault();
                                                                   document.getElementById('order-deleted-{{$order->id}}').submit();">
                                                                    <i class="fa fa-trash" ></i> </a>
                           | <a href="{{url('order/printer/'.encrypt($order->id))}}" class="action_imgs" target="_blank" >
                                         <i class="fa fa-print" ></i> </a>
                            | <a href="{{url('order/print/'.encrypt($order->id))}}" target="_blank" class="ac" style="letter-spacing:0px">
                                                                   kitchen receipt </a>
                            <form action="{{url('order/status')}}" method="post" id="order-deleted-{{$order->id}}">
                              {{csrf_field()}}
                              <input name="status" type="hidden" value="4">
                              <input name="id" type="hidden" value="{{encrypt($order->id)}}">
                            </form>
                            <form action="{{url('order/status')}}" method="post" id="order-status-{{$order->id}}">
                             {{csrf_field()}}
                             <input name="status" type="hidden" value="3">
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
        $(document).ready(function() {
            $('#pending_orders').DataTable({response:true});
            $(".deleted").click(function() {
                var deleted_id=$(this).data("set_id");
                $('#deleted_item').val(deleted_id);
                $("#deleted_model").modal('show');
            });
        });


        $(".print_page").click(function(e){
             e.preventDefault();
             var id = $(this).data('content');

            $.ajax({url: base_url + '/formPrint/' + id}).done(function (response) {


                    $('#models').empty().append(response);
                   // $('#print_model').modal('show');

                    var divToPrint=document.getElementById("printReceipt");
                    newWin= window.open("");
                    newWin.document.write(divToPrint.innerHTML);
                    newWin.print();
                    newWin.close();
                    location.href =  '{{URL::to("orders/confirmed")}}';

                }).fail(function(error){
                      alert('error');
                  });



        });
    </script>
@endsection