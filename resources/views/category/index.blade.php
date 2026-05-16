@extends('layouts.theme')
@section('title','Category')
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

             <h3 class="box-title">Category List</h3>
                  {{--<a href="{{url('category/create')}}" class="btn btn-primary pull-right">Add Category</a>--}}
                </div>
            <div class="box-body table-responsive">
              <table id="example1" class="table table-bordered table-striped d-table" >
                            <thead>
                            <tr>
                                <th>Sr. #</th>
                                <th>Category Name</th>
                                <th>Action</th>

                            </tr>
                            </thead>
                            <tbody>

                            @php ($i = 1)
                            @foreach($categoryName as $value)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>{{$value->name}}</td>
                                    <td>
                                    <a href="{{url('type/'.encrypt($value->id).'/edit')}}" class="action_imgs">
                                            <img src="{{url('images/edit.svg')}}" width="23" height="23"/>
                                        </a>

                                        {{--<a href="javascript:;" class="action_imgs">--}}
                                            {{--<img src="{{url('images/remove.svg')}}" width="23" height="23" class="deleted" data-set_id="{{encrypt($value->id)}}">--}}
                                        {{--</a>--}}

                                    </td>
                                </tr>

                                @php ($i += 1)
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                                <!-- /.box-body -->
            </div>
            <div class="modal fade" id="deleted_model">
               <div class="modal-dialog">
                 <div class="modal-content">
                   <div class="modal-header">
                     <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                       <span aria-hidden="true">&times;</span></button>
                     <h4 class="modal-title">Delete Confirmation</h4>
                   </div>
                    <form action="{{route('type.destroy','cat')}}" method="post" >
                   <div class="modal-body">
                     <h3 class="text-center text-danger">Are you sure you want to delete this?</h3>

                         {{csrf_field()}}
                         <input name="id" type="hidden" id="deleted_item" value="">
                         <input name="_method" type="hidden" value="DELETE">

                   </div>
                   <div class="modal-footer">
                     <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>

                     <button type="submit" class="btn btn-primary ">Submit</button>
                   </div>
                  </form>
                 </div>
                 <!-- /.modal-content -->
               </div>
               <!-- /.modal-dialog -->
             </div>
@endsection
@section('script')
        <!-- DataTables -->
          <script src="{{url('plugins/datatables/jquery.dataTables.min.js')}}"></script>
          <script src="{{url('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
          <script>
                $(document).ready(function() {
                    $('#example1').DataTable();
                    $("#example1").on("click", ".deleted", function(e) {
                         var deleted_id=$(this).data("set_id");
                         $('#deleted_item').val(deleted_id);
                         $("#deleted_model").modal('show');
                     });
                });
          </script>

@endsection