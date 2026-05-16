@extends('layouts.theme')
@section('title','Add Ingredient')

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
         <h3 class="box-title">Add Ingredient</h3>
       </div>
        <div class="box-body">
            <form action="{{url('ingredient')}}" method="post" role="form" enctype="multipart/form-data">

        <input type="hidden" name="_token" value="{{csrf_token()}}" >
        <div class="row">
         <div class="col-md-6">
           <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label">Ingredient name</label>
            <input class="form-control" placeholder="Ingredient Name" name="name" type="text" value="{{old('name')}}" autocomplete="off">
           @if ($errors->has('name')) <span class="help-block"> <strong>{{ $errors->first('name') }}</strong> </span> @endif
           </div>
        </div>
         <div class="col-md-6">
            <div class="form-group  {{ $errors->has('price') ? ' has-error' : '' }}">
             <label>Extra price, If select this option otherwise leave</label>
                   <input type="text" class="form-control" placeholder=" Extra Price" name="price"  value="{{old('price')}}" autocomplete="off">
             @if ($errors->has('price')) <span class="help-block"> <strong>{{ $errors->first('price') }}</strong> </span> @endif
            </div>

         </div>

        </div>
        <div class="row">
              <div class="col-md-6">
               <div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">

                <label class="control-label">Detail</label>
                <textarea class="form-control" name="detail" cols="3" rows="2">{{old('detail')}}</textarea>

              @if ($errors->has('detail')) <span class="help-block"> <strong>{{ $errors->first('detail') }}</strong> </span> @endif
              </div>
                 <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">

                         <label>Ingredient Image</label>
                         <input type="file" class="btn btn-default upload-image" name="image" id="image">

                  @if ($errors->has('image')) <span class="help-block"> <strong>{{ $errors->first('image') }}</strong> </span> @endif
                 </div>

              </div>
            <div class="col-md-6">
                <div class="form-group {{ $errors->has('short_code') ? ' has-error' : '' }}">
                    <label class="control-label">Ingredient short code</label>
                    <input class="form-control" placeholder="Ingredient short code" name="short_code" type="text" value="{{old('short_code')}}" autocomplete="off">
                    @if ($errors->has('short_code')) <span class="help-block"> <strong>{{ $errors->first('short_code') }}</strong> </span> @endif
                </div>

                 <img id="preview_img" class="img-responsive  preview_img" width="150" src="">
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