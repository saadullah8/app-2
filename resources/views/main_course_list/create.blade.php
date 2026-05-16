@extends('layouts.theme')
@section('title','Add Ingredient in Main Course')

@section('content')
<div class="row">
   <div class="col-md-12">
     <div class="box box-info">
       <div class="box-header with-border">
       @if(Session::has('success'))
                    <div class="alert alert-success alert-dismissable fade in">
                      <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                         <strong>Success! </strong>{{ Session::get('success') }}
                   </div>
              @endif

               @if(Session::has('error'))

               <div class="alert alert-error alert-dismissable fade in">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                     <strong>Error! </strong>{{ Session::get('error') }}
               </div>
               @endif
         <h3 class="box-title">Add Ingredient Main Course</h3>
       </div>
        <div class="box-body">
            <form action="{{url('main-course-list')}}" method="post" role="form">

        <input type="hidden" name="_token" value="{{csrf_token()}}" >
        <input type="hidden" name="main_course_id" value="{{encrypt($id)}}" >
        <div class="row">
         <div class="col-md-6 {{ $errors->has('ingredient') ? ' has-error' : '' }}">
            <label class="control-label">Choose Ingredient</label>
            <?php $cout=1;
            $check=$course_name->MainCourseList; ?>
            @foreach($ingredients as $value)
                <div class="input-group">
                        <span class="input-group-addon">
                        @if(count($check)>0)

                            @foreach($check as $ingredient_already)
                                @if($ingredient_already->ingredient_id==$value->id)
                                    <?php $cout=0; ?>
                                 <input type="checkbox" class="minimal " name="ingredient[]" value="{{encrypt($value->id)}}" checked>
                                    </span>
                                <input type="text" class="form-control" readonly value="{{$value->name}}">
                                <input name="sorting[{{$value->id}}]" type="number" autocomplete="off" class="form-control" value="{{$ingredient_already->sorting}}" placeholder="enter sorting number">
                                @endif
                            @endforeach

                             @if($cout)
                                <input type="checkbox" class="minimal" name="ingredient[]" value="{{encrypt($value->id)}}">
                                </span>
                                <input type="text" class="form-control" readonly value="{{$value->name}}">
                                <input name="sorting[{{$value->id}}]" type="number" autocomplete="off" class="form-control" placeholder="enter sorting number">
                             @else
                                <?php $cout=1; ?>
                             @endif
                        @else
                          <input type="checkbox" class="minimal" name="ingredient[]" value="{{encrypt($value->id)}}">
                            </span>
                            <input type="text" class="form-control" readonly value="{{$value->name}}">
                            <input name="sorting[{{$value->id}}]" type="number" autocomplete="off" class="form-control" placeholder="enter sorting number">
                        @endif


                  </div>
            @endforeach
            @if ($errors->has('ingredient')) <span class="help-block"> <strong>{{ $errors->first('ingredient') }}</strong> </span> @endif
        </div>

         <div class="col-md-6">
         <div class="form-group">
            <label class="control-label">Main Course name</label>
            <input class="form-control"   type="text" readonly value="{{$course_name->name}}" >
          </div>
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
<script src="{{url('plugins/iCheck/icheck.min.js')}}"></script>
<script>
//  $(function () {
//    //iCheck for checkbox and radio inputs
//    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
//      checkboxClass: 'icheckbox_minimal-blue',
//      radioClass   : 'iradio_minimal-blue'
//    })
//  })
</script>

@endsection
@section('style')
<link rel="stylesheet" href="{{url('plugins/iCheck/all.css')}}">
@endsection