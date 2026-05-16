@extends('layouts.theme')
@section('title','Ready Order')
@section('style')
    <link href="{{url('plugins/sweetalert2/sweetalert2.min.css')}}" rel="stylesheet">
    <link href="{{url('plugins/datatables/jquery.dataTables.css')}}" rel="stylesheet">
    <link href="{{url('plugins/datatables/extensions/Responsive/css/dataTables.responsive.css')}}" rel="stylesheet">
    <style>
        .swal2-popup{
            font-size: 15px !important;
        }
    </style>
@endsection
@section('content')

    <div class="box">
        <div class="box-header">
            @include('alert')
            <div class="row">
                <div class="col-lg-4">
                    <h3 class="card-title">Ready Order</h3>
                </div>
                <div class="col-lg-8">

                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            {!! $dataTable->table(['class' => 'table table-bordered ', 'id' => 'process-table']) !!}

        </div>
        <!-- /.box-body -->
        <div id="models"></div>

        <form action="{{url('order/status')}}" method="post" id="orderDeleted">
            {{csrf_field()}}
            <input name="status" type="hidden" value="2">
            <input name="id" id="orderIdAdded" type="hidden" value="">
        </form>
        <form action="{{url('sendSms')}}" method="post" id="orderMessage">
            {{csrf_field()}}
            <input name="type" type="hidden" value="pick_up_order_tab">
            <input name="order_id" id="orderSMSField" type="hidden" value="">
        </form>
    </div>
    <!-- /.box -->
@endsection
@section('script')

    <script src="{{url('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{url('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{url('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
    {!! $dataTable->scripts() !!}

    <script>
        $(document).on('click', '.deleted_page', function(e){
            e.preventDefault();
            var id = $(this).data('content');
            $('#orderIdAdded').val(id);
            document.getElementById('orderDeleted').submit();
        });
        $(document).on('click', '.smsOrderBtn', function(e){
            e.preventDefault();
            var id = $(this).data('content');
            $('#orderSMSField').val(id);
            document.getElementById('orderMessage').submit();
        });

    </script>
@endsection

