@extends('layouts.theme')
@section('title','Edit ingredient')

@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Edit ingredient</h3>
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


      <form role="form" action="{{url('ingredient/'.encrypt($ingredient->id))}}" method="post" enctype="multipart/form-data">
        <input type="hidden" name="_token" value="{{csrf_token()}}" >
        <input type="hidden" name="_method" value="PUT">
        <div class="row">
        <div class="col-md-6">
             <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
              <label >Ingredient Name</label>
             <input class="form-control" value="{{$ingredient->name}}" name="name" type="text" autocomplete="off">
             @if ($errors->has('name')) <span class="help-block"> <strong>{{ $errors->first('name') }}</strong> </span> @endif
          </div>
          </div>
        <div class="col-md-6">

            <div class="form-group  {{ $errors->has('price') ? ' has-error' : '' }}">
                         <label>Extra price, If select this option otherwise leave</label>
                               <input type="text" class="form-control" placeholder=" Extra Price" name="price"  value="{{$ingredient->price}}" autocomplete="off">
                         @if ($errors->has('price')) <span class="help-block"> <strong>{{ $errors->first('price') }}</strong> </span> @endif
                        </div>
         </div>


        </div>
        <div class="row">
          <div class="col-md-6">
               <div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">

                  <label class="control-label">Detail</label>
                  <textarea class="form-control" name="detail" cols="3" rows="2">{{$ingredient->detail}}</textarea>

                @if ($errors->has('detail')) <span class="help-block"> <strong>{{ $errors->first('detail') }}</strong> </span> @endif
                </div>
             <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                 <div >
                     <label>Ingredient Image</label>
                     <input type="file" class="btn btn-default upload-image" name="image" id="image">
                 </div>

             </div>
          </div>
          <div class="col-md-6">
              <div class="form-group {{ $errors->has('short_code') ? ' has-error' : '' }}">
                  <label class="control-label">Ingredient short code</label>
                  <input class="form-control" placeholder="Ingredient short code" name="short_code" type="text" value="{{$ingredient->short_code}}" autocomplete="off">
                  @if ($errors->has('short_code')) <span class="help-block"> <strong>{{ $errors->first('short_code') }}</strong> </span> @endif
              </div>

              <img id="preview_img" class="img-responsive img-thumbnail preview_img" src="{{url('/site_images/ingredient_images/'.$ingredient->image)}}" width="150">
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