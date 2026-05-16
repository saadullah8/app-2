@extends('layouts.theme')
@section('title','Edit Category')

@section('content')
<div class="row">
   <div class="col-md-12">
     <div class="box box-info">
       <div class="box-header with-border">
         <h3 class="box-title">Edit Product Category</h3>
       </div>
        <div class="box-body">



      <form action="{{url('category/'.encrypt($cat->id))}}" method="post" role="form">

        <input type="hidden" value="PUT" name="_method">
        <input type="hidden" value="{{csrf_token()}}" name="_token">
        <div class="row">
         <div class="col-md-6">
           <div class="form-group {{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label">Category Name</label>
            <input class="form-control" placeholder="Category E.g Pizza, Burger, Drinks" name="name" type="text" value="{{$cat->name}}" autocomplete="off">
           @if ($errors->has('name')) <span class="help-block"> <strong>{{ $errors->first('name') }}</strong> </span> @endif
           </div>

        </div>
        <div class="col-md-6">
           <div class="form-group {{ $errors->has('category_id') ? ' has-error' : '' }}">

           <label class="control-label">Select Type of Category</label>
           <select   name="category_id" class="form-control" >
             <option value="">Select</option>
               @foreach($types as $value)
                 <option {{$cat->category_id==$value->id?'selected':""}} value="{{$value->id}}">{{$value->name}}</option>
               @endforeach
           </select>
           {{--@if ($errors->has('category_id')) <span class="help-block"> <strong>{{ $errors->first('category_id') }}</strong> </span> @endif--}}
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