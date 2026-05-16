@extends('layouts.theme')

@section('title','Store Timing')

@section('style')



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



             <h3 class="box-title">Store Timing</h3>

                  <a href="{{url('storeTiming/'.$store->id.'/edit')}}" class="btn btn-primary pull-right">Timing Setting</a>

             </div>

            <div class="box-body table-responsive">

              <table id="example1" class="table table-bordered table-striped d-table" >

                            <thead>

                            <tr>



                                <th>Store Opening Time</th>

                                <th>Store closing Time</th>

                                <th>status</th>

                            </tr>

                            </thead>

                            <tbody>
                            <tr>
                                <td>{{date('H:i a',$store->opening_time)}}</td>
                                <td>{{date('H:i a',$store->closing_time)}}</td>
                                @if($store->status)
                                <td>Open</td>
                                @else
                                <td>Closed</td>
                                @endif


                            </tr>

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

                   $('#example1').DataTable();



               });

         </script>

@endsection