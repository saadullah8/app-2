@extends('layouts.theme')
@section('title','Staff List')
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
                    <h3 class="card-title">Staff User</h3>
                </div>
                <div class="col-lg-8">
                    <a href="{{url('staff/create')}}" class="btn btn-primary pull-right">Create Employee</a>

                </div>
            </div>
        </div>
        <!-- /.box-header -->
        <div class="box-body table-responsive">
            {!! $dataTable->table(['class' => 'table table-bordered ', 'id' => 'user-table']) !!}

        </div>
        <!-- /.box-body -->
    </div>
    <!-- /.box -->
@endsection
@section('script')

    <script src="{{url('plugins/sweetalert2/sweetalert2.min.js')}}"></script>
    <script src="{{url('plugins/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{url('plugins/datatables/extensions/Responsive/js/dataTables.responsive.min.js')}}"></script>
    {!! $dataTable->scripts() !!}
    <script src="{{url('js/deletedItem.js')}}"></script>

    <script>
        $(document).on('click', '.deleted', function(e){
            var uid = $(this).data('id');
            tr = $(this).closest('tr');
            var deletedPath ="{{url('staff').'/'}}"+uid;
            var tableId="user-table";
            deleteItem(deletedPath,tableId,tr);
        });
    </script>
@endsection

