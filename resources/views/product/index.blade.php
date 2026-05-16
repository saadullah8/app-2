@extends('layouts.theme')
@section('title','Products')
@section('style') 
<!-- DataTables -->
<link rel="stylesheet" href="{{url('plugins/iCheck/all.css')}}">
<link rel="stylesheet" href="{{url('plugins/datatables/dataTables.bootstrap.css')}}">
@endsection

@section('content')
<div class="row">
   <div class="col-md-12">
<div class="box box-info">
  <div class="box-header">
    @if(Session::has('success'))
         <div class="alert alert-success alert-dismissable fade in">
           <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong></strong>{{ Session::get('success') }}
        </div>
   @endif

    @if(Session::has('error'))

    <div class="alert alert-error alert-dismissable fade in">
       <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <strong></strong>{{ Session::get('error') }}
    </div>
    @endif
    <h3 class="box-title">Product Lists</h3>
    <a href="{{url('product/create?type='.$type)}}"   class="btn btn-primary pull-right">Add Product</a> </div>
  <div class="box-body table-responsive">
    <table id="example1" class="table table-bordered table-striped d-table" >
      <thead>
        <tr>
          <th>Sr. #</th>
          <th>Title</th>
      @if($type!=3)
          <th>Category</th>@endif
          <th>Price($)</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
      @php ($i = 1)
      @foreach($products as $value)

      <tr >
       <td>{{$i}}</td>
        <td><a href="{{url('product/'.encrypt($value->id))}}" > {{$value->title}} </a></td>
        @if($type!=3)
        <td>{{$value->getType? $value->getType->name:''}}</td>@endif
        {{--<td>{{$value->category->name}}</td>--}}
        <td>{{$value->price}}</td>
        <td>
            <a href="{{url('product/'.encrypt($value->id).'/edit')}}" class="action_imgs">
            <img src="{{url('images/edit.svg')}}" width="23" height="23"/> </a>
             |
            <a href="javascript:;" class="action_imgs" >
            <img src="{{url('images/remove.svg')}}" width="23" height="23" class="deleted" data-set_id="{{encrypt($value->id)}}" /> </a>
@if($value->category_id==3)
      | <button type="button" class="btn btn-link add_main_course "   data-set_id="{{$value->id}}" style="padding:0px; font-size: 17px">
       <li class="fa fa-plus-square"></li></button>
                     @endif
@if($value->status)
                              |  <button type="button" class="btn btn-link"   onclick="event.preventDefault();document.getElementById('active-{{$value->id}}').submit();" >
                                       <li class="glyphicon glyphicon-ok"></li> Active</button>

                                 {{--<a href="{{url('ingredient/status/')}}"  onclick="event.preventDefault();document.getElementById('active-{{$value->id}}').submit();"> Active </a>--}}
                               <form action="{{url('product/status/')}}" method="post" id="active-{{$value->id}}">
                                 {{csrf_field()}}
                                 <input name="status" type="hidden" value="Active">
                                 <input name="id" type="hidden" value="{{encrypt($value->id)}}">
                               </form>
                                @else
                              |  <button type="button" class="btn btn-link"   onclick="event.preventDefault();document.getElementById('active-{{$value->id}}').submit();" >
                                                                       <li class="glyphicon glyphicon-remove"></li> De Active</button>
                                 {{--<a href="{{url('ingredient/status/')}}" onclick="event.preventDefault();document.getElementById('active-{{$value->id}}').submit();" > De Active </a>--}}
                                       <form action="{{url('product/status/')}}" method="post" id="active-{{$value->id}}">
                                         {{csrf_field()}}
                                         <input name="status" type="hidden" value="De Active">
                                         <input name="id" type="hidden" value="{{encrypt($value->id)}}">
                                       </form>
                                @endif
        </td>
      </tr>
      @php ($i += 1)
      @endforeach
        </tbody>
    </table>
  </div>
  <!-- /.box-body -->

   <div class="modal fade" id="add_main_course">
     <div class="modal-dialog">
       <div class="modal-content">
         <div class="modal-header">
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
             <span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Main Course List</h4>
         </div>
          <form action="{{url('product/add/main_course')}}" method="post" >
         <div class="modal-body">

               {{csrf_field()}}
            <div class="row">
            <input name="product_id" type="hidden" id="product_id" value="">
              <div class="col-md-12">
               <div class="form-group{{ $errors->has('main_course_id') ? ' has-error' : '' }}">
                  <label class="control-label">Select Main Course</label>
                  <select   name="main_course_id" class="form-control" >
                    <option value="">Select</option>
                      @foreach($main_courses as $value)
                        <option {{old('main_course_id')==$value->id?'selected':""}} value="{{$value->id}}">{{$value->name}}</option>
                      @endforeach
                  </select>
                  @if ($errors->has('main_course_id')) <span class="help-block"> <strong>{{ $errors->first('main_course_id') }}</strong> </span> @endif
             </div>
              </div>

               <div class="col-md-6">
                  <div class="form-group">
                    <label>Min Value</label>
                    <input class="form-control " placeholder="Min Limit" name="min_value" type="text"  value="{{old('min_value')}}" autocomplete="off">
                  </div>
               </div>
               <div class="col-md-6">
                  <div class="form-group">
                    <label>Max Value</label>
                    <input class="form-control " placeholder="Max Limit" name="max_value" type="text"  value="{{old('max_value')}}" autocomplete="off">
                  </div>
               </div>
              <div class="col-md-12">
              <div class="form-group">
                <label>Chose Option</label>
                <div class="input-group"> <span class="input-group-addon">
                  <input type="checkbox" class="minimal" name="skip" value="1" />
                  </span>
                  <input type="text" class="form-control" readonly value="Skip" />
                </div>
                <div class="input-group"> <span class="input-group-addon">
                  <input type="checkbox" class="minimal" name="dressing" value="1" />
                  </span>
                  <input type="text" class="form-control" readonly value="Dressings on the side" />
                </div>
                <div class="input-group"> <span class="input-group-addon">
                  <input type="checkbox" class="minimal" name="half" value="1" />
                  </span>
                  <input type="text" class="form-control" readonly value="Half" />
                </div>
                <div class="input-group"> <span class="input-group-addon">
                  <input type="checkbox" class="minimal" name="extra_charge" value="1" />
                  </span>
                  <input type="text" class="form-control" readonly value="Extra Charge" />
                </div>
                </div>
              </div>

              <div class="col-md-6">

                <div class="form-group">
                  <label>Extra Charges</label>
                  <input class="form-control " placeholder="Extra Charges" name="extra_price" type="text"  value="{{old('extra_price')}}" autocomplete="off">
                </div>
              </div>
               {{--<div class="col-md-3">--}}
                {{--<div class="form-group">--}}
                  {{--<label>Min Extra Value</label>--}}
                  {{--<input class="form-control " placeholder="Min Limit" name="min_extra_limit" type="text"  value="{{old('min_extra_limit')}}" autocomplete="off">--}}
                {{--</div>--}}
              {{--</div>--}}
              {{--<div class="col-md-3">--}}
                {{--<div class="form-group">--}}
                  {{--<label>Max Extra Value</label>--}}
                  {{--<input class="form-control " placeholder="Max Limit" name="max_extra_limit" type="text"  value="{{old('max_extra_limit')}}" autocomplete="off">--}}
                {{--</div>--}}
              {{--</div>--}}
              <div class="col-md-12">
              <strong>Note*

              </strong>
              <ul>
                  <li>
                   if Min value leave empty then user select min one ingredient
                  </li>
                  <li>
                   if max value leave empty then user select all ingredient
                  </li>
                </ul>
              </div>
            </div>

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

   <div class="modal fade" id="deleted_model">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span></button>
              <h4 class="modal-title">Delete Confirmation</h4>
            </div>
             <form action="{{route('product.destroy','main')}}" method="post" >
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

</div>
</div>
</div>
@endsection
@section('script') 
<!-- DataTables --> 
<script src="{{url('plugins/datatables/jquery.dataTables.min.js')}}"></script> 
<script src="{{url('plugins/datatables/dataTables.bootstrap.min.js')}}"></script>
<script src="{{url('plugins/iCheck/icheck.min.js')}}"></script>
<script>
$(function () {
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
  });
      $(document).ready(function() {

           $("#category").click(function() {
               $("#category_model").modal('show');
           });

      });
      @if ($errors->has('category'))
              (function($) {
                  $(window).load(function () {
                    $("#category_model").modal('show');
                  });
              })(jQuery);
      @endif
</script>
<script>
  $(document).ready(function() {
      $('#example1').DataTable();

      $("#example1").on("click", ".deleted", function(e) {
           var deleted_id=$(this).data("set_id");
           $('#deleted_item').val(deleted_id);
           $("#deleted_model").modal('show');
       });
       $("#example1").on("click", ".add_main_course", function(e) {
           var deleted_id=$(this).data("set_id");
           $('#product_id').val(deleted_id);
           $("#add_main_course").modal('show');
       });
  });
</script>
@endsection