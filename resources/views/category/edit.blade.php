@extends('layouts.theme')
@section('title','Edit Category')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Edit Category</h3>
      </div>
<div class="box-body">
      @if(Session::has('success'))
      <div class="alert alert-success alert-dismissable fade in">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
       {{ Session::get('success') }}
      </div>
      @endif

      @if(Session::has('error'))
      <div class="alert alert-warning alert-dismissable fade in">
                             <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                   {{ Session::get('error') }}
                </div>
      @endif


      <form role="form" action="{{url('type/'.encrypt($category->id))}}" method="post">
        <input type="hidden" name="_token" value="{{csrf_token()}}" >
        <input type="hidden" name="_method" value="PUT">
        <div class="row">
        <div class="col-md-6 col-lg-offset-3">
             <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label">Category Name</label>
            <input class="form-control" value="{{$category->name}}" name="name" type="text" autocomplete="off">
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
@endsection