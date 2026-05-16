@extends('layouts.theme')
@section('title','Add Category')

@section('content')
<div class="row">
   <div class="col-md-12">
     <div class="box box-info">
       <div class="box-header with-border">
         <h3 class="box-title">Add Product Category</h3>
       </div>
        <div class="box-body">



      <form action="{{url('type')}}" method="post" role="form">

        <input type="hidden" name="_token" value="{{csrf_token()}}" >
        <div class="row">
         <div class="col-md-6 col-lg-offset-3">
           <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label">Category Name</label>
            <input class="form-control" placeholder="Category Name" name="name" type="text" value="{{old('name')}}" autocomplete="off">
           @if ($errors->has('name')) <span class="help-block"> <strong>{{ $errors->first('name') }}</strong> </span> @endif
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
</div>
@endsection