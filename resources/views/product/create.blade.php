@extends('layouts.theme')
@section('title','Add Product')
@section('style') 
<!-- DataTables -->
<link rel="stylesheet" href="{{url('plugins/datatables/dataTables.bootstrap.css')}}">
@endsection
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
        <h3 class="box-title">Add Product</h3>
      </div>
      <div class="box-body">

        <form action="{{url('product')}}" method="post" role="form" enctype="multipart/form-data">
          <input type="hidden" value="{{csrf_token()}}" name="_token">
          <input type="hidden" value="{{$cat_id}}" name="category_id">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                <label  class="control-label">Title</label>
                <input class="form-control number" placeholder="Product Name" name="title" type="text"  value="{{old('title')}}">
                @if ($errors->has('title')) <span class="help-block"> <strong>{{ $errors->first('title') }}</strong> </span> @endif </div>
            </div>
             <div class="col-md-6">
                <div class="form-group {{ $errors->has('price') ? ' has-error' : '' }}">
                   <label  class="control-label">Price</label>
                   <input class="form-control number" placeholder="Price" name="price" type="text"  value="{{old('price')}}">
                   @if ($errors->has('price')) <span class="help-block"> <strong>{{ $errors->first('price') }}</strong> </span> @endif
                </div>

             </div>
          </div>
          <div class="row">
            <div class="col-md-6">
            <div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">

                  <label class="control-label">Detail</label>
                  <textarea class="form-control" name="detail" cols="5" rows="5">{{old('detail')}}</textarea>

                @if ($errors->has('detail')) <span class="help-block"> <strong>{{ $errors->first('detail') }}</strong> </span> @endif </div>
            </div>
            @if($cat_id!=3)
            <div class="col-md-6">
            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                <label class="control-label">Select Category</label>
                <select   name="type" class="form-control" >
                  <option value="">Select</option>
                    @foreach($types as $value)
                      <option {{old('type')==$value->id?'selected':""}} value="{{$value->id}}">{{$value->name}}</option>
                    @endforeach
                </select>
           </div>

            </div>
            @endif
            <div class="col-md-3">
               <div class="form-group {{ $errors->has('image') ? ' has-error' : '' }}">

                       <label>Product Image</label>
                       <input type="file" class="btn btn-default upload-image" name="image" id="image">

                @if ($errors->has('image')) <span class="help-block"> <strong>{{ $errors->first('image') }}</strong> </span> @endif
               </div>

            </div>
            <div class="col-md-3">
               <img id="preview_img" class="img-responsive  preview_img" width="150" src="">
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