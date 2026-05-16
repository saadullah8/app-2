@extends('layouts.theme')
@section('title','Add Main Course')

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
         <h3 class="box-title">Add  Main Course</h3>
       </div>
        <div class="box-body">
            <form action="{{url('main-course')}}" method="post" role="form" >

        <input type="hidden" name="_token" value="{{csrf_token()}}" >
        <div class="row">
         <div class="col-md-6 ">
           <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label">Main Course Title</label>
            <input class="form-control" placeholder="Main Course Name" name="name" type="text" value="{{old('name')}}" autocomplete="off">
           @if ($errors->has('name')) <span class="help-block"> <strong>{{ $errors->first('name') }}</strong> </span> @endif
           </div>

        </div>
            <div class="col-md-3 ">
                <div class="form-group {{ $errors->has('sorting') ? ' has-error' : '' }}">
                    <label class="control-label">Sorting Order</label>
                    <input class="form-control" placeholder="sorting order" name="sorting" type="text" value="{{old('sorting')}}" autocomplete="off">
                    @if ($errors->has('sorting')) <span class="help-block"> <strong>{{ $errors->first('sorting') }}</strong> </span> @endif
                </div>

            </div>
            <div class="col-md-3 ">
                <br>
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