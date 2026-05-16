@extends('layouts.theme')
@section('title','Edit Product')
@section('style')
<link rel="stylesheet" href="{{url('plugins/iCheck/all.css')}}">
@endsection
@section('content')
<div class="row">
  <div class="col-md-12">
    <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title">Edit Product</h3>
      </div>
      <div class="box-body">
         <form action="{{url('product/'.encrypt($product->id))}}" method="post" role="form" enctype="multipart/form-data">
                  <input type="hidden" value="PUT" name="_method">
                  <input type="hidden" value="{{csrf_token()}}" name="_token">
                  <div class="row">
                      <div class="col-md-6">
                        <div class="form-group {{ $errors->has('title') ? ' has-error' : '' }}">
                          <label  class="control-label">Title</label>
                          <input class="form-control number" placeholder="Product Name" name="title" type="text"  value="{{$product->title}}">
                          @if ($errors->has('title')) <span class="help-block"> <strong>{{ $errors->first('title') }}</strong> </span> @endif </div>
                      </div>
                       <div class="col-md-6">
                          <div class="form-group {{ $errors->has('price') ? ' has-error' : '' }}">
                             <label  class="control-label">Price</label>
                             <input class="form-control number" placeholder="Price" name="price" type="text"  value="{{$product->price}}">
                             @if ($errors->has('price')) <span class="help-block"> <strong>{{ $errors->first('price') }}</strong> </span> @endif </div></div>
                    </div>

                    <div class="row">
                      <div class="col-md-6">
                      <div class="form-group{{ $errors->has('detail') ? ' has-error' : '' }}">
                    <?php  $option=$product->CustomizedProduct->pluck('main_course_id')->toArray();?>
                        <label class="control-label">Detail</label>
                        <textarea class="form-control" name="detail" cols="5" rows="5">{{$product->detail}}</textarea>

                      @if ($errors->has('detail')) <span class="help-block"> <strong>{{ $errors->first('detail') }}</strong> </span> @endif </div>
                      @if($product->category_id==2)
                         @if(count($option)>0)
                            <div class="row">
                                <div class="col-md-6">

                                  <div class="form-group{{ $errors->has('main_course') ? ' has-error' : '' }}">
                                          <label class="control-label">Select Main Course</label>
                                          <select   name="main_course[]" class="form-control" multiple>
                                            <option value="">Select Main Course</option>
                                                  @foreach($main_courses as $value)
                                                    <option {{in_array($value->id,$option)? 'selected':""}} value="{{$value->id}}">{{$value->name}}</option>
                                                  @endforeach
                                          </select>


                                  @if ($errors->has('main_course')) <span class="help-block"> <strong>{{ $errors->first('main_course') }}</strong> </span> @endif
                                     </div>
                            </div>
                            </div>
                         @endif

                      @endif
                      </div>
                      @if($product->category_id!=3)
                      <div class="col-md-6">
                          <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                              <label class="control-label">Select Type of Product</label>
                              <select   name="type" class="form-control" required="">
                                <option value="">Select</option>
                                  @foreach($types as $value)
                                    <option {{$product->type_id==$value->id?'selected':""}} value="{{$value->id}}">{{$value->name}}</option>
                                  @endforeach
                              </select>
                              @if ($errors->has('type')) <span class="help-block"> <strong>{{ $errors->first('type') }}</strong> </span> @endif
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
                         <img id="preview_img" class="img-responsive  preview_img" width="150" src="{{url('/site_images/product_images/'.$product->image)}}">
                         <br>

                         <div class="form-group">
                           <button type="submit" class="btn btn-info pull-right">Submit</button>
                         </div>
                      </div>
                    </div>
         </form>
                    <!-- end above section form -->
                    @if($product->category_id==3)

                         <button type="button" class="btn btn-info add_main_course pull-right"   data-set_id="{{$product->id}}"><li class="fa fa-plus-square"></li></button>
                                 @endif
                    <?php  $option=$product->CustomizedProduct; ?>
                  @if($product->category_id==3)
                     @if(count($option)>0)
                     @foreach($option as $values)
                    <hr>
                    <div class="row">
                      <div class="col-md-6">
                           <div class="form-group{{ $errors->has('main_course_id') ? ' has-error' : '' }}">
                              <label class="control-label">Main Course</label>
                              <select   name="main_course_id" class="form-control" disabled>
                                <option value="">Main Course</option>
                                  @foreach($main_courses as $value)
                                    <option {{$values->main_course_id==$value->id?'selected':""}} value="{{$value->id}}">{{$value->name}}</option>
                                  @endforeach
                              </select>
                              @if ($errors->has('main_course_id')) <span class="help-block"> <strong>{{ $errors->first('main_course_id') }}</strong> </span> @endif
                         </div>
                          </div>

                           <div class="col-md-3">
                              <div class="form-group">
                                <label>Min Value</label>
                                <input class="form-control " placeholder="Min Limit" name="min_value" type="text"  value="{{$values->min_value}}" autocomplete="off" disabled>
                              </div>
                           </div>
                           <div class="col-md-3">
                              <div class="form-group">
                                <label>Max Value</label>
                                <input class="form-control " placeholder="Max Limit" name="max_value" type="text"  value="{{$values->max_value==100? 'All':$values->max_value}}" autocomplete="off" disabled>
                              </div>
                           </div>
                                  <div class="col-md-6">
                                  <div class="form-group">
                                    <label>Chose Option</label>
                                    <div class="input-group"> <span class="input-group-addon">
                                      <input type="checkbox" class="minimal" name="skip" value="1" {{$values->skip? 'checked':''}} disabled/>
                                      </span>
                                      <input type="text" class="form-control" readonly value="Skip" />
                                    </div>
                                    <div class="input-group"> <span class="input-group-addon">
                                      <input type="checkbox" class="minimal" name="dressing" value="1" {{$values->dressing? 'checked':''}} disabled/>
                                      </span>
                                      <input type="text" class="form-control" readonly value="Dressings on the side" />
                                    </div>
                                    <div class="input-group"> <span class="input-group-addon">
                                      <input type="checkbox" class="minimal" name="half" value="1"  {{$values->half? 'checked':''}} disabled/>
                                      </span>
                                      <input type="text" class="form-control" readonly value="Half" />
                                    </div>
                                    <div class="input-group"> <span class="input-group-addon">
                                      <input type="checkbox" class="minimal" name="extra_charge" value="1" {{$values->extra_charge? 'checked':''}} disabled/>
                                      </span>
                                      <input type="text" class="form-control" readonly value="Extra Charge" />
                                    </div>
                                    </div>
                                  </div>

                                  <div class="col-md-6">

                                    <div class="form-group">
                                      <label>Extra Charges</label>
                                      <input class="form-control" placeholder="Extra Charges" name="extra_price" type="text"  value="{{$values->extra_price}}" disabled autocomplete="off">
                                    </div>
                                  </div>
                                   {{--<div class="col-md-3">--}}
                                    {{--<div class="form-group">--}}
                                      {{--<label>Min Extra Value</label>--}}
                                      {{--<input class="form-control" placeholder="Min Limit" name="min_extra_limit" type="text"  value="{{$values->min_extra_limit}}" disabled autocomplete="off">--}}
                                    {{--</div>--}}
                                  {{--</div>--}}
                                  <div class="col-md-3">
                                    {{--<div class="form-group">--}}
                                      {{--<label>Max Extra Value</label>--}}
                                      {{--<input class="form-control" placeholder="Max Limit" name="max_extra_limit" type="text"  value="{{$values->max_extra_limit==100? 'All':$values->max_extra_limit}}" disabled autocomplete="off">--}}
                                    {{--</div>--}}

                                    <button type="button" class="btn btn-info deleted"   data-set_id="{{encrypt($values->id)}}">Remove</button>
                                    <button type="button" class="btn btn-info edit"  data-id="{{"#modal_edit_".$values->id}}"> Edit </button>

                                    <div class="modal fade" id="{{"modal_edit_".$values->id}}">
                                                  <div class="modal-dialog">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span></button>
                                                        <h4 class="modal-title">Main Course List</h4>
                                                      </div>
                                                       <form action="{{url('product/edit/main_course')}}" method="post" id="form_id_{{$values->id}}">
                                                      <div class="modal-body">

                                                            {{csrf_field()}}
                                                         <div class="row">
                                                         <input name="edit_id" type="hidden" id="edit_id" value="{{encrypt($values->id)}}">

                                                           <div class="col-md-12">
                                                            <div class="form-group{{ $errors->has('main_course_id') ? ' has-error' : '' }}">
                                                               <label class="control-label">Select Main Course</label>
                                                               <select   name="main_course_id" class="form-control" >
                                                                 <option value="">Select</option>
                                                                   @foreach($main_courses as $value)
                                                                      <option {{$values->main_course_id==$value->id?'selected':""}} value="{{$value->id}}">{{$value->name}}</option>
                                                                    @endforeach
                                                               </select>
                                                               @if ($errors->has('main_course_id')) <span class="help-block"> <strong>{{ $errors->first('main_course_id') }}</strong> </span> @endif
                                                          </div>
                                                           </div>

                                                            <div class="col-md-6">
                                                               <div class="form-group">
                                                                 <label>Min Value</label>
                                                                    <input class="form-control " placeholder="Min Limit" name="min_value" type="text"  value="{{$values->min_value}}" autocomplete="off">
                                                               </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                               <div class="form-group">
                                                                 <label>Max Value</label>
                                                                  <input class="form-control " placeholder="Max Limit" name="max_value" type="text"  value="{{$values->max_value==100? '':$values->max_value}}" autocomplete="off">

                                                               </div>
                                                            </div>
                                                           <div class="col-md-12">
                                                           <div class="form-group">
                                                               <label>Chose Option</label>
                                                               <div class="input-group"> <span class="input-group-addon">
                                                                 <input type="checkbox" class="minimal" name="skip" value="1" {{$values->skip? 'checked':''}}/>
                                                                 </span>
                                                                 <input type="text" class="form-control" readonly value="Skip" />
                                                               </div>
                                                               <div class="input-group"> <span class="input-group-addon">
                                                                 <input type="checkbox" class="minimal" name="dressing" value="1" {{$values->dressing? 'checked':''}}/>
                                                                 </span>
                                                                 <input type="text" class="form-control" readonly value="Dressings on the side" />
                                                               </div>
                                                               <div class="input-group"> <span class="input-group-addon">
                                                                 <input type="checkbox" class="minimal" name="half" value="1"  {{$values->half? 'checked':''}} />
                                                                 </span>
                                                                 <input type="text" class="form-control" readonly value="Half" />
                                                               </div>
                                                               <div class="input-group"> <span class="input-group-addon">
                                                                 <input type="checkbox" class="minimal" name="extra_charge" value="1" {{$values->extra_charge? 'checked':''}}/>
                                                                 </span>
                                                                 <input type="text" class="form-control" readonly value="Extra Charge" />
                                                               </div>
                                                               </div>
                                                           </div>

                                                           <div class="col-md-6">

                                                             <div class="form-group">
                                                               <label>Extra Charges</label>
                                                               <input class="form-control " placeholder="Extra Charges" name="extra_price" type="text"  value="{{$values->extra_price}}" autocomplete="off">
                                                             </div>
                                                           </div>
                                                            {{--<div class="col-md-3">--}}
                                                             {{--<div class="form-group">--}}
                                                               {{--<label>Min Extra Value</label>--}}
                                                                {{--<input class="form-control" placeholder="Min Limit" name="min_extra_limit" type="text"  value="{{$values->min_extra_limit}}" autocomplete="off">--}}
                                                             {{--</div>--}}
                                                           {{--</div>--}}
                                                           {{--<div class="col-md-3">--}}
                                                             {{--<div class="form-group">--}}
                                                               {{--<label>Max Extra Value</label>--}}
                                                                 {{--<input class="form-control" placeholder="Max Limit" name="max_extra_limit" type="text"  value="{{$values->max_extra_limit==100? '':$values->max_extra_limit}}" autocomplete="off">--}}
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
                                  </div>
                    </div>
                    @endforeach
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
                    @endif
                  @endif


      </div>
    </div>
  </div>
  <div class="modal fade" id="deleted_model">
           <div class="modal-dialog">
             <div class="modal-content">
               <div class="modal-header">
                 <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                   <span aria-hidden="true">&times;</span></button>
                 <h4 class="modal-title">Delete Confirmation</h4>
               </div>
                <form action="{{url('product/remove/main_course')}}" method="post" >
               <div class="modal-body">
                 <h3 class="text-center text-danger">Are you sure you want to remove this?</h3>

                     {{csrf_field()}}
                     <input name="id" type="hidden" id="deleted_item" value="">

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
</div>

@endsection
@section('script')
<script src="{{url('plugins/iCheck/icheck.min.js')}}"></script>
<script>
$(function () {
    //iCheck for checkbox and radio inputs
    $('input[type="checkbox"].minimal, input[type="radio"].minimal').iCheck({
      checkboxClass: 'icheckbox_minimal-blue',
      radioClass   : 'iradio_minimal-blue'
    })
  })
      $(document).ready(function() {

           $(".deleted").click(function() {
               var deleted_id=$(this).data("set_id");
               $('#deleted_item').val(deleted_id);
               $("#deleted_model").modal('show');
           });

           $(".edit").click(function() {
            var edit_id=$(this).data("id");
               $(edit_id).modal('show');
           });

           $(".add_main_course").click(function() {
                var deleted_id=$(this).data("set_id");
                $('#product_id').val(deleted_id);
               $("#add_main_course").modal('show');
           });
      });

</script>
@endsection
