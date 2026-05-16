@extends('layouts.theme')

@section('title','Setting Time')
@section('style')
 <link rel="stylesheet" href="{{url('plugins/iCheck/all.css')}}">
 <link href="{{url('css/clockpicker/clockpicker.css')}}" rel="stylesheet">
@endsection



@section('content')

<div class="row">

   <div class="col-md-12">

     <div class="box box-info">

       <div class="box-header with-border">

         <h3 class="box-title">Time Setting</h3>

       </div>

        <div class="box-body">







      <form action="{{url('storeTiming/'.encrypt($store->id))}}" method="post" role="form">



        <input type="hidden" value="PUT" name="_method">

        <input type="hidden" value="{{csrf_token()}}" name="_token">

        <div class="row">

         <div class="col-md-6">

            <!-- time Picker -->
              <div class="form-group {{ $errors->has('opening_time') ? ' has-error' : '' }}">
                <label>Opening Time:</label>

                <div class="input-group">
                  <input type="text" name="opening_time" class="form-control timepicker" autocomplete="off"
                         value="{{date('H:i',$store->opening_time)}}"  data-autoclose="true">

                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                </div>
                <!-- /.input group -->
              </div>
        </div>
         <div class="col-md-6">
         <div class="form-group {{ $errors->has('closing_time') ? ' has-error' : '' }}">
                <label>Closing Time:</label>

                <div class="input-group">
                  <input type="text" name="closing_time" class="form-control timepicker"
                         autocomplete="off" value="{{date('H:i',$store->closing_time)}}" data-placement="left" data-align="top" data-autoclose="true">

                  <div class="input-group-addon">
                    <i class="fa fa-clock-o"></i>
                  </div>
                </div>
                <!-- /.input group -->
              </div>
        </div>
        </div>
        <div class="row">

            <div class="col-md-6">
                <div class="form-group {{ $errors->has('status') ? ' has-error' : '' }}">

                 <label>
                    <input type="checkbox" class="flat-red" name="status" value="true" {{$store->status? "checked":''}}>
                    is Open?
                 </label>

                  <!-- /.input group -->
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">

                 <button type="submit" class="btn btn-info pull-right">Submit</button>

               </div>

            </div>
        </div>




      </form>

    </div>

  </div>

      </div>

    </div>



@endsection
@section('script')

<script src="{{url('js/bootstrap.min.js')}}"></script>
<script src="{{url('js/clockpicker/clockpicker.js')}}"></script>
<script src="{{url('plugins/iCheck/icheck.min.js')}}"></script>
<script>
    $(document).ready(function () {

        $('.timepicker').clockpicker();
  })
</script>

@endsection