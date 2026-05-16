@extends('layouts.master')
@section('title','Meal Create')
@section('content')
  <style>
    .hide {
      display: none;
    }
    .btnRemoveItem{
      color: #0a0a0a;
      padding: 5px;
      width: 15% !important;
      text-align: center !important;
      border-radius: 50%;
      border: 2px solid #f0c713;
    }

  </style>
  <div class="bread-crumb">
    <div class="container"> </div>
  </div>
  @if(Session::get('orders') && $id!='')
      <?php $meal_detail = Session::get('orders');
      $data = $meal_detail[$id];
      $price = $data['price'];
      $items = $data['item'];
      $store_meal = $data['data'];
      $note = $data['note'];
      $name = $data['name'];
      $qty = $data['qty'];
      ?>
  @else
      <?php
      $price = '';
      $name = '';
      $items = '';
      $note = '';
      $store_meal = array();
      $qty=1;
      ?>
  @endif
  <div class="add-menu">
    <div class="menu-header">
      <form action="{{url('add_cart')}}" method="post" id="cart_form">
        @csrf
        <input type="hidden" name="note" id="note" value="{{$note}}">
        <input type="hidden" name="name" id="name_customer" value="{{$name}}">
        <input type="hidden" name="qty" id="qtycustomer" value="{{$qty}}">
        <input type="hidden" value="{{$price!=''? $price:'' }}" name="total_price" id="total_amount">
        <input type="hidden" value="{{$items!=''? $items:'0' }}" name="total_item" id="total_items">
        <input type="hidden" value="{{$meals->title}}" name="product_name">
        @if($id!='')
          <input type="hidden" value="{{$id}}" name="edit_id">
        @endif
        @foreach($store_meal as $values)
          <input type="hidden" value="{{$values['ingredient_id']}}" class="form-control form_id_{{$values['ingredient_id']}}" name="ingredient[{{"product_".$values['ingredient_id']}}][ingredient_id]">
          <input type="hidden" value="{{$values['product_name']}}" class="form-control form_name_{{$values['ingredient_id']}}" name="ingredient[{{"product_".$values['ingredient_id']}}][product_name]">

          <input type="hidden" value="{{$values['product_code']}}" class="form-control form_code_{{$values['ingredient_id']}}" name="ingredient[{{"product_".$values['ingredient_id']}}][product_code]">

          <input type="hidden" value="{{$values['span_value']}}" class="form-control form_ingredient_{{$values['ingredient_id']}} {{$values['main_course']}} " name="ingredient[{{"product_".$values['ingredient_id']}}][span_value]">
          <input type="hidden" value="{{$values['main_course']}}" class="form-control cat_title_{{$values['ingredient_id']}}" name="ingredient[{{"product_".$values['ingredient_id']}}][main_course]">
          <input type="hidden" value="{{$values['image']}}" class="form-control image_{{$values['ingredient_id']}}" name="ingredient[{{"product_".$values['ingredient_id']}}][image]">
          <input type="hidden" value="{{$values['main_course_id']}}" class="form-control main_course_id_{{$values['ingredient_id']}}" name="ingredient[{{"product_".$values['ingredient_id']}}][main_course_id]">
          <input type="hidden" value="{{$values['ingredient_price']}}" class="form-control ingredient_price_{{$values['ingredient_id']}}" name="ingredient[{{"product_".$values['ingredient_id']}}][ingredient_price]">
        @endforeach
      </form>
      <h2>{{$meals->title}} <br>Sr. {{$serial_number}}</h2>
      <ul>
        <li class="clearfix"> <span class="float-left span-left">Items</span> <span class="pull-right span-right" id="total_item">({{$items!=''? $items:'0' }})</span> </li>
        <li class="clearfix"> <span class="float-left span-left">Total Price</span> $<span class="pull-right span-right" id="total_price">{{$price!=''? $price:$meals->price }} </span> </li>
      </ul>
      <button type="button" data-toggle="modal" data-target="#model_add_name" class="mobile btn btn-theme btn-wide add-bag">Add To Bag</button>
    </div>
    <div class="menu-body">
      <ul id="add_list_cart">
        @if(empty($store_meal))
          <li class="clearfix" id="remove_text"> <span class="float-left span-left"> Add some ingredients to create your meal. </span> </li>
        @else
          @foreach($store_meal as $values)
            <li class="clearfix" id="delete-{{$values['ingredient_id']}}"> <span class="float-left span-left"> <img src="{{url('site_images/ingredient_images/'.$values['image'])}}" alt="{{$values['product_name']}}"> {{$values['product_name']}}<br>
                {{$values['ingredient_price']==0?'':$values['ingredient_price']}} </span>
              <span class="btnRemoveItem pull-right span-right  deleted_cart" data-cat="{{$values['main_course']}}" data-id="{{$values['ingredient_id']}}"
                    data-price="{{$values['ingredient_price']}}" id="delete-{{$values['ingredient_id']}}">X</span> </li>
          @endforeach
        @endif
      </ul>
    </div>
    <div class="menu-footer">
      <button type="button" data-toggle="modal" data-target="#model_add_name" class="desktop btn btn-theme btn-wide add-bag">Add To Bag</button>
    </div>
  </div>

  <!------------------------------end sticky menu---------------------->
  <?php $mainCourses = $meals->CustomizedProduct;
  $class = 0; ?>
  @php($course_names = '')
  <!-- Breakfast Menu Start -->
  @foreach($mainCourses as $main_course)
    @php($course_names.=strtolower(str_replace(' ', '_', $main_course->getMainCourse->name)).",")
    <div class="menu menu-2 {{$class==0? 'gray-bg':'white-bg'}}">
      <div class="menu-inner">
        <div class="container"> @php($course_name = strtolower(str_replace(' ', '_', $main_course->getMainCourse->name)))
          <div class="row " id="{{$course_name}}_div">
            <!-- Title Content Start -->

            <div class="col-sm-10 col-xs-12 commontop text-center">
              <h4>{{$main_course->getMainCourse->name}} </h4>
              <div class="divider style-1 center"> <span class="hr-simple left"></span> <i class="icofont icofont-ui-press hr-icon"></i> <span class="hr-simple right"></span> </div>
              <p style="color: red;display: none" id="{{strtolower(str_replace(' ', '_', $main_course->getMainCourse->name))}}_msg">*Please Select At Least One Item</p>

            </div>
            <!-- Title Content End -->

              <?php $ingredients = $main_course->getMainCourse->MainCourseList;   ?>
            <div class="col-sm-10 col-xs-12">
              <div class="row"> @if($store_meal!='')
                  @foreach($ingredients as $ingredient)
                          <?php $check_ok = 1;
                          $skip_check = 'Not';
                          $dressing_table = "No"; ?>
                    @foreach($store_meal as $values)

                      @if($values['ingredient_id']!='skip_'.$main_course->id)
                        @if($values['ingredient_id']=='dressing_'.$main_course->id)
                                      <?php $dressing_table = "yes"; ?>
                        @endif
                        @if($values['main_course']!=$course_name.'_extra')
                          @if($ingredient->id==$values['ingredient_id'])
                            <div class="col-md-6 col-sm-6 col-xs-12 ">
                              <!-- box box2 Start -->
                              <diV class="box box2 selected deleted {{strtolower(str_replace(' ', '_', $main_course->getMainCourse->name))}}"
                                   id="{{"product_".$ingredient->id}}" data-price="{{$ingredient->Ingredients->price}}" data-id="{{$ingredient->id}}"
                                   data-image="{{$ingredient->Ingredients->image}}" data-ingredient_id="{{$ingredient->Ingredients->id}}"
                                   data-title="{{$ingredient->Ingredients->name}}"
                                   data-code="{{$ingredient->Ingredients->short_code!=null? $ingredient->Ingredients->short_code:$ingredient->Ingredients->name}}"
                                   data-min_value="{{$main_course->min_value}}"
                                   data-max_value="{{$main_course->max_value}}" data-main_course_id="{{$main_course->id}}"
                                   data-half="{{$main_course->half}}" data-skip="{{$main_course->skip}}"
                                   data-dressing="{{$main_course->dressing}}" data-extra_charge="{{$main_course->extra_charge}}"
                                   data-extra_charge_min="{{$main_course->min_extra_limit}}" data-extra_charge_max="{{$main_course->max_extra_limit}}"
                                   data-extra_charge_price="{{$main_course->extra_price}}" data-ingredient_status="{{$ingredient->Ingredients->status}}"
                                   data-cat="{{ strtolower(str_replace(' ', '_', $main_course->getMainCourse->name))}}">
                                <div class="image dish-img">
                                  <div class="bg-img" style="background-image:url({{url('site_images/ingredient_images/'.$ingredient->Ingredients->image)}})"></div>
                                <!-- <img src="{{url('site_images/ingredient_images/'.$ingredient->Ingredients->image)}}" alt="{{$ingredient->Ingredients->name}}" title="{{$ingredient->Ingredients->name}}" class="img-responsive " width="130" />   -->
                                  <div class="img-overlay  " id="overlay_{{$ingredient->id}}"> <span id="span_{{$ingredient->id}}">{{$values['span_value']}}</span> </div>
                                </div>
                                <div class="caption caption2">
                                  <h4>{{$ingredient->Ingredients->name}}</h4>
                                  <span>
                    <p>{{$ingredient->Ingredients->detail}}</p>
                  </span> @if($ingredient->Ingredients->price!=0)
                                    <div class="price">+ ${{$ingredient->Ingredients->price}}</div>
                                  @endif
                                </div>
                              </div>
                              <!-- Box End -->
                            </div>
                                          <?php $check_ok = 0; ?>
                          @endif
                        @endif
                      @else
                                  <?php $skip_check = strtolower(str_replace(' ', '_', $main_course->getMainCourse->name)); ?>
                      @endif
                    @endforeach

                    @if($check_ok)
                      <div class="col-md-6 col-sm-6 col-xs-12 ">
                        <!-- box box2 Start -->
                        <diV class="box box2 add_cart {{strtolower(str_replace(' ', '_', $main_course->getMainCourse->name))}}"
                             id="{{"product_".$ingredient->id}}" data-price="{{$ingredient->Ingredients->price}}" data-id="{{$ingredient->id}}"
                             data-image="{{$ingredient->Ingredients->image}}"
                             data-ingredient_id="{{$ingredient->Ingredients->id}}"
                             data-title="{{$ingredient->Ingredients->name}}"
                             data-code="{{$ingredient->Ingredients->short_code!=null? $ingredient->Ingredients->short_code:$ingredient->Ingredients->name}}"
                             data-min_value="{{$main_course->min_value}}" data-max_value="{{$main_course->max_value}}" data-main_course_id="{{$main_course->id}}" data-half="{{$main_course->half}}" data-skip="{{$main_course->skip}}" data-dressing="{{$main_course->dressing}}" data-extra_charge="{{$main_course->extra_charge}}" data-extra_charge_min="{{$main_course->min_extra_limit}}" data-extra_charge_max="{{$main_course->max_extra_limit}}" data-extra_charge_price="{{$main_course->extra_price}}" data-ingredient_status="{{$ingredient->Ingredients->status}}" data-cat="{{ strtolower(str_replace(' ', '_', $main_course->getMainCourse->name))}}">
                          <div class="image dish-img">
                            <div class="bg-img" style="background-image:url({{url('site_images/ingredient_images/'.$ingredient->Ingredients->image)}})"></div>
                          <!-- <img src="{{url('site_images/ingredient_images/'.$ingredient->Ingredients->image)}}" alt="{{$ingredient->Ingredients->name}}" title="{{$ingredient->Ingredients->name}}" class="img-responsive " width="130" /> -->
                            <div class="img-overlay  hide" id="overlay_{{$ingredient->id}}"> <span id="span_{{$ingredient->id}}"></span> </div>
                          </div>
                          <div class="caption caption2">
                            <h4>{{$ingredient->Ingredients->name}}</h4>
                            <span>
                    <p>{{$ingredient->Ingredients->detail}}</p>
                  </span> @if($ingredient->Ingredients->price!=0)
                              <div class="price">+ ${{$ingredient->Ingredients->price}}</div>
                            @endif
                          </div>
                        </div>
                        <!-- box box2 End -->
                      </div>
                    @endif
                  @endforeach
                  <div class="col-md-6 col-sm-12 col-xs-12">
                    <!-- box box2 Start -->
                    <div class="box box2 button-box clearfix">
                      @if($main_course->extra_charge)
                        <div class="button-plus button-new">
                          <button type="button" class="btn btn-theme btn-wide plus-btn" data-toggle="modal" data-target="#modal_{{$course_name}}">
                            <i class="icofont icofont-plus"></i>

                          </button>
                          <span>Add Extra </span>
                        </div>
                      @endif
                      @if($main_course->skip)
                        <div class="button-cross button-new">
                          <button type="button" class="btn btn-theme btn-wide skip_others    @if($skip_check!='Not')    selected deleted_skip {{$skip_check}} @endif" data-cat="{{$course_name}}" id="{{"product_skip_".$main_course->id}}" data-price="0" data-id="skip_{{$main_course->id}}" data-image="cross.png" data-ingredient_id="s_{{$main_course->id}}" data-title="Skip {{str_replace('Choose','',$main_course->getMainCourse->name)}}" data-min_value="1" data-max_value="2" data-main_course_id="{{$main_course->id}}" data-half="{{$main_course->half}}" data-skip="{{$main_course->skip}}" data-dressing="{{$main_course->dressing}}" data-extra_charge="{{$main_course->extra_charge}}" data-extra_charge_min="{{$main_course->min_extra_limit}}" data-extra_charge_max="{{$main_course->max_extra_limit}}" data-extra_charge_price="{{$main_course->extra_price}}" data-ingredient_status="{{$ingredient->Ingredients->status}}">
                            <i class="icofont icofont-close"></i>

                          </button>
                          <span>Skip </span>
                        </div>
                      @endif
                      @if($main_course->dressing)
                        <div class="button-dish button-new">
                          <button type="button" class="btn btn-theme btn-wide dressing_others  @if($dressing_table!='No')    selected deleted @else add_cart @endif" data-cat="{{$course_name}}" id="{{"product_dressing_".$main_course->id}}" data-price="0" data-id="dressing_{{$main_course->id}}" data-image="cup.png" data-ingredient_id="s_{{$main_course->id}}" data-title="Dressing on The Side " data-min_value="{{$main_course->min_value+1}}" data-max_value="{{$main_course->max_value+1}}" data-main_course_id="{{$main_course->id}}" data-half="{{$main_course->half}}" data-skip="{{$main_course->skip}}" data-dressing="{{$main_course->dressing}}" data-extra_charge="{{$main_course->extra_charge}}" data-extra_charge_min="{{$main_course->min_extra_limit}}" data-extra_charge_max="{{$main_course->max_extra_limit}}" data-extra_charge_price="{{$main_course->extra_price}}" data-ingredient_status="{{$ingredient->Ingredients->status}}">
                            <i class="icofont icofont-soup-bowl"></i>

                          </button>
                          <span>Dressings on the side</span>
                        </div>
                      @endif

                    </div>
                    <!-- Box End -->
                  </div>
                  @if($main_course->extra_charge)
                    <div class="modal fade" id="modal_{{$course_name}}" role="dialog">
                      <div class="modal-dialog add-cart2 additem-popup">
                        <!-- Modal content-->
                        <div class="modal-content slider-model">
                          <div class="modal-header slide-card">
                            <h2 class="add-head">Add Extra</h2>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                          </div>
                          <div class="modal-body addcart-body clearfix">
                            <p class="p-text">Add an extra your order.</p>

                            <div class="wrap-one">

                              @if($store_meal!='')
                                @foreach($ingredients as $ingredient)
                                        <?php $check_ok = 1; ?>

                                  @foreach($store_meal as $values)
                                    @if($values['main_course']==$course_name.'_extra' && $values['ingredient_id']==$ingredient->id.'_extra')
                                      <ul class="itemlist popup-itemlist">
                                        <li class="add_cart_extra  clearfix">
                                          <button type="button" class="btn deleted selected add-item-info {{$course_name}}_extra" id="{{"product_".$ingredient->id."_extra"}}"
                                                  data-price="{{$ingredient->Ingredients->price+$main_course->extra_price}}"
                                                  data-id="{{$ingredient->id}}_extra" data-image="{{$ingredient->Ingredients->image}}"
                                                  data-ingredient_id="{{$ingredient->Ingredients->id}}"
                                                  data-code="Extra {{$ingredient->Ingredients->short_code!=null? $ingredient->Ingredients->short_code:$ingredient->Ingredients->name}}"
                                                  data-title="Extra {{$ingredient->Ingredients->name}}"

                                                  data-min_value="{{$main_course->min_value}}" data-max_value="{{$main_course->max_value}}" data-main_course_id="{{$main_course->id}}" data-ingredient_status="{{$ingredient->Ingredients->status}}" data-cat="{{$course_name."_extra"}}"><i id="font_{{$ingredient->id}}_extra" class="icofont icofont-minus"></i>
                                          </button>
                                          <span class="item-name">{{$ingredient->Ingredients->name}}</span>
                                          @if($ingredient->Ingredients->price!=0)
                                            <span class="item-price">+ ${{$ingredient->Ingredients->price+$main_course->extra_price}}</span>
                                          @else
                                            <span class="item-price">+ ${{$main_course->extra_price}}</span>
                                          @endif
                                        </li>
                                      </ul>
                                                <?php $check_ok = 0; ?>
                                    @endif
                                  @endforeach
                                  @if($check_ok)
                                    <ul class="itemlist popup-itemlist">
                                      <li class="add_cart_extra  clearfix">
                                        <button type="button" class="btn add_cart  add-item-info {{$course_name}}_extra" id="{{"product_".$ingredient->id."_extra"}}"
                                                data-price="{{$ingredient->Ingredients->price+$main_course->extra_price}}"
                                                data-id="{{$ingredient->id}}_extra" data-image="{{$ingredient->Ingredients->image}}"
                                                data-ingredient_id="{{$ingredient->Ingredients->id}}"
                                                data-title="Extra {{$ingredient->Ingredients->name}}"
                                                data-code="Extra {{$ingredient->Ingredients->short_code!=null? $ingredient->Ingredients->short_code:$ingredient->Ingredients->name}}"
                                                data-min_value="{{$main_course->min_value}}" data-max_value="{{$main_course->max_value}}" data-main_course_id="{{$main_course->id}}" data-ingredient_status="{{$ingredient->Ingredients->status}}" data-cat="{{$course_name."_extra"}}">
                                          <i id="font_{{$ingredient->id}}_extra" class="icofont icofont-plus"></i></button>
                                        <span class="item-name">{{$ingredient->Ingredients->name}}</span>
                                        @if($ingredient->Ingredients->price!=0)
                                          <span class="item-price">+ ${{$ingredient->Ingredients->price+$main_course->extra_price}}</span>
                                        @else
                                          <span class="item-price">+ ${{$main_course->extra_price}}</span>
                                        @endif
                                      </li>
                                    </ul>
                                  @endif
                                @endforeach
                              @endif
                            </div>
                          </div>
                          <div class="modal-footer footer-btn cart-footer">
                            <button type="button" class="checkout-btn" data-dismiss="modal">Save</button>
                          </div>
                        </div>

                      </div>
                    </div>
                  @endif
                @else
                  @foreach($ingredients as $ingredient)
                    <div class="col-md-6 col-sm-6 col-xs-12 ">
                      <!-- box box2 Start -->
                      <diV class="box box2 add_cart {{strtolower(str_replace(' ', '_', $main_course->getMainCourse->name))}}"
                           id="{{"product_".$ingredient->id}}" data-price="{{$ingredient->Ingredients->price}}" data-id="{{$ingredient->id}}"
                           data-image="{{$ingredient->Ingredients->image}}" data-ingredient_id="{{$ingredient->Ingredients->id}}"
                           data-title="{{$ingredient->Ingredients->name}}"
                           data-code="{{$ingredient->Ingredients->short_code!=null? $ingredient->Ingredients->short_code:$ingredient->Ingredients->name}}"
                           data-min_value="{{$main_course->min_value}}" data-max_value="{{$main_course->max_value}}" data-half="{{$main_course->half}}" data-skip="{{$main_course->skip}}" data-dressing="{{$main_course->dressing}}" data-extra_charge="{{$main_course->extra_charge}}" data-extra_charge_min="{{$main_course->min_extra_limit}}" data-extra_charge_max="{{$main_course->max_extra_limit}}" data-extra_charge_price="{{$main_course->extra_price}}" data-ingredient_status="{{$ingredient->Ingredients->status}}" data-main_course_id="{{$main_course->id}}" data-cat="{{ strtolower(str_replace(' ', '_', $main_course->getMainCourse->name))}}">
                        <div class="image dish-img"> <img src="{{url('site_images/ingredient_images/'.$ingredient->Ingredients->image)}}" alt="{{$ingredient->Ingredients->name}}" title="{{$ingredient->Ingredients->name}}" class="img-responsive " width="130" />
                          <div class="img-overlay hide " id="overlay_{{$ingredient->id}}"> <span id="span_{{$ingredient->id}}">1</span> </div>
                        </div>
                        <div class="caption caption2">
                          <h4>{{$ingredient->Ingredients->name}}</h4>
                          <span>{{$ingredient->Ingredients->detail}}</span> @if($ingredient->Ingredients->price!=0)
                            <div class="price">+${{$ingredient->Ingredients->price}}</div>
                          @endif
                        </div>
                      </div>
                      <!-- box box2 End -->
                    </div>
                  @endforeach
                @endif </div>
            </div>

          </div>
        </div>
      </div>
    </div>
    <?php
    if ($class == 0) {
        $class = 1;
    } else {
        $class = 0;
    }
    ?>
  @endforeach
  <div class="col-sm-8 col-xs-8 offset-md-1 offset-sm-1">
    <div class="button-cross button-new">

      <div id="newsletter">
        <div class="container">
          <div id="subscribe">
            <!-- Subscribe Form -->
            {{--<form class="form-horizontal" name="subscribe">--}}
            <div class="row">
              <div class="col-sm-0 col-md-0 col-lg-2">

              </div>
              <div class="col-sm-12 col-md-10 form-group">
                <label class="control-label" style="color:white">Special instructions</label>
                <div class="input-group ">

                  <input id="note_write" value="{{$note}}" name="message" placeholder="Type here" type="text">

                </div>
              </div>
            </div>
          {{--</form>--}}
          <!-- Subscribe Form -->
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Breakfast Menu End -->
  <div class="modal fade" id="model_add_name" role="dialog">
    <div class="modal-dialog add-cart2 second-cart">

      <div class="modal-content slider-model">
        <div class="main-bodypopup">
          <div class="modal-header slide-card">
            <div class="slanted-image__container">
              <div class="slanted-image__cover"></div>
              <div class="slanted-image__image"> <img src="{{url('assets/images/dishes/01.jpg')}}"> </div>
            </div>
            {{--<button type="button" class="close" data-dismiss="modal">&times;</button>--}}
          </div>
          {{--<div class="row">--}}
          <div class="col-lg-12">
            <div class="form-horizontal search-icon"> <br>
              <fieldset>
                <div class="form-group">
                  <input name="name_customer" id="add_name_customer" value="{{$name}}" placeholder="This Meal is for?" class="form-control" type="text">

                </div>
                <div class="form-group">
                  <label>Quantity</label>
                  <div class="add-value value-wrap">

                    <button type="button" class="sub" >-</button>

                    <input id="qty_customer" class="vlaue"  value="{{$qty}}" type="number"  name="qty"/>

                    <button type="button" class="add" >+</button>

                  </div>

                </div>

              </fieldset>
            </div>
          </div>
          {{--</div>--}}


          {{--<div class="input-group">--}}
          {{--<input type="text" value="{{$name}}" name="name" id="add_name">--}}
          {{--</div>--}}
        </div>
        <div class="modal-footer footer-btn cart-footer">
          <button type="submit" class="checkout-btn" id="add_bag">Add To Bag</button>
        </div>
      </div>

    </div>
  </div>
@endsection
@section('script')
  <script>
      var course_names = '{{substr($course_names,0,-1)}}';
      course_names = course_names.split(',');


      $(function() {

          $('#add_bag').on('click', function(e) {
              var success = 0;
              e.preventDefault();

              var note = $("#note_write").val();
              $("#note").val(note);
              var nama_person = $("#add_name_customer").val();
              var order_qty = $("#qty_customer").val();
              $("#name_customer").val(nama_person);
              $("#qtycustomer").val(order_qty);
              $('#model_add_name').modal('hide');
              for (i = 0; i < course_names.length; i++) {
                  var cname = course_names[i];
                  var min = 0;
                  var max = 0;
                  var selected = 0;
                  var is_skip = 0;

                  $('.' + cname).each(function() {
                      min = $(this).data('min_value');
                      max = $(this).data('max_value');
                      is_skip = $(this).data('skip');
                      if ($(this).hasClass('selected')) {
                          selected++;
                      }
                  });
                  if (is_skip == 0) {
                      if (selected < min) {
                          moveToDiv(cname + '_div');
                          showHideError(cname, 1);
                          break;
                      } else {
                          success++;
                          showHideError(cname, 0);
                      }
                  } else {

                      if (selected >= 1) {

                          showHideError(cname, 0);
                          success++;
                      } else {
                          moveToDiv(cname + '_div');
                          showHideError(cname, 1);
                          break;
                      }
                  }


              }
              if (course_names.length == success) {
                  $('#cart_form').submit();
              }
          });
          $(document).on('click', '.skip_others', function() {
              var cat = $(this).data('cat');
              var image = $(this).data('image');
              var price = $(this).data('price');
              var title = $(this).data('title');
              var id = $(this).data('id');

              var min_value = $(this).data('min_value');
              var max_value = $(this).data('max_value');
              var main_course_id = $(this).data('main_course_id');
              var ingredient_id = $(this).data('ingredient_id');
              var total_price = $("#total_price").text();
              var is_half = $(this).data('half');


              $('.' + cat).each(function() {

                  var id = $(this).data('id');

                  if ($(this).hasClass('selected')) {
                      removeInput(id);
                      removed(id);
                      $('#overlay_' + id).addClass('hide');
                      $(this).removeClass('selected');
                      var price = $(this).data('price');
                      var total_prices = $("#total_price").text();
                      var total_amount = calculatedRemove(total_prices, price);
                      var count_item = CountItem();
                      UpdateSetAmount(total_amount, count_item);
                  }

              });
              $('.' + cat+"_extra").each(function() {

                  var id = $(this).data('id');

                  if ($(this).hasClass('selected')) {
                      removeInput(id);
                      removed(id);
                      changeFont(id, 'plus');
                      $(this).removeClass('selected');
                      var price = $(this).data('price');
                      var total_prices = $("#total_price").text();
                      var total_amount = calculatedRemove(total_prices, price);
                      var count_item = CountItem();
                      UpdateSetAmount(total_amount, count_item);
                  }

              });

              if ($('#product_dressing_' + main_course_id).hasClass('dressing_others selected')) {
                  removed("dressing_" + main_course_id);
              }

              if (!$(this).hasClass(cat)) {
                  $("#remove_text").remove();
                  addItem(id, title, image, price, total_price, cat);
                  var total_prices = $("#total_price").text();

                  $(this).addClass(" selected deleted_skip");
                  $(this).addClass(cat);
                  var count_item = CountItem();
                  UpdateSetAmount(total_price, count_item);

                  Addform(title,title, id, 0, price, cat, total_prices, image, main_course_id, count_item);
              } else {
                  CountItem();
                  $(this).removeClass(cat);
              }
          })
      });

      $(document).on('click', ".deleted_skip", function() {
          var id = $(this).data('id');
          var div_id = $(this).attr('id');
          $('#' + div_id).removeClass("deleted_skip").removeClass('selected');
          var price = $(this).data('price');
          var total_price = $("#total_price").text();
          var min_value = $(this).data('min_value');
          var max_value = $(this).data('max_value');
          var main_course_id = $(this).data('main_course_id');
          var main_cat = $(this).data('cat');
          var total_amount = calculatedRemove(total_price, price);
          removedSkip(id);
          var count_item = CountItem();
          var obj = $(this);
          removeInput(id);

          UpdateSetAmount(total_amount, count_item)

      });

      $(document).on('click', ".add_cart", function() {

          var image = $(this).data('image');
          var price = $(this).data('price');
          var title = $(this).data('title');
          var sort_code = $(this).data('code');
          var id = $(this).data('id');

          var min_value = $(this).data('min_value');
          var max_value = $(this).data('max_value');
          var main_course_id = $(this).data('main_course_id');
          var ingredient_id = $(this).data('ingredient_id');
          var total_price = $("#total_price").text();
          var is_half = $(this).data('half');
          $("#remove_text").remove();
          var main_cat = $(this).data('cat');
          var obj = $(this);

          var select_count = checkCount(main_cat) + 1;

          if (select_count <= max_value) {
              changeFont(id, 'minus');
              obj.addClass('selected');
              var select_count = checkCount(main_cat);
              var value_overlay = changeOverlay(main_cat, id, is_half);

              addItem(id, title, image, price, total_price, main_cat);
              $(this).addClass("deleted").removeClass("add_cart");
              var count_item = CountItem();
              var total_prices = $("#total_price").text();
              Addform(title,sort_code, id, value_overlay, price, main_cat, total_prices, image, main_course_id, count_item);
          }
          if ($("#product_skip_" + main_course_id).hasClass('selected')) {

              var id = $("#product_skip_" + main_course_id).data('id');
              $("#product_skip_" + main_course_id).removeClass("deleted_skip").removeClass('selected').removeClass(main_cat);
              removedSkip(id);
              var count_item = CountItem();
              removeInput(id);
              UpdateSetAmount(total_amount, count_item)
          }
      });

      function changeFont(id, type, plus) {

          if (plus == "plus") {
              $('#font_' + id).removeClass('icofont-minus').addClass('icofont-' + type)
          } else {
              $('#font_' + id).removeClass('icofont-plus').addClass('icofont-' + type)
          }

      }

      function changeOverlay(main_cat, id, is_half) {
          var select_count = checkCount(main_cat);
          var val = '1';

          if (select_count != 1) {
              if (is_half == 1) {
                  val = '1/' + select_count;
              } else {
                  val = 1;
              }

          }
          updateOverlay(main_cat, id, val);
          return val;
      }

      function updateOverlay(cat_class, id, value) {
          $('#overlay_' + id).toggleClass('hide');
          $('.' + cat_class).each(function() {
              var span_id = $(this).data('id');
              $('#span_' + span_id).html(value);
          });
          $('.' + cat_class).attr('value', value);
      }

      function checkCount(main_cat) {

          var selected = 0;
          $('.' + main_cat).each(function() {
              if ($(this).hasClass('selected')) {
                  selected++;
              }
          });
          return selected;

      }

      function moveToDiv(div_id) {
          $('html, body').animate({
              scrollTop: $("#" + div_id).offset().top
          }, 1500);
      }

      function showHideError(div_id, show_hide) {
          // 1 show 0 hide
          if (show_hide == 1) {

              $('#' + div_id + "_msg").show();
          } else {
              $('#' + div_id + "_msg").hide();
          }
      }
      $(document).on('click', ".deleted", function() {
          var id = $(this).data('id');
          changeFont(id, 'plus');
          var div_id = $(this).attr('id');

          $('#' + div_id).addClass("add_cart").removeClass("deleted").removeClass('selected');
          var price = $(this).data('price');
          var total_price = $("#total_price").text();
          var min_value = $(this).data('min_value');
          var max_value = $(this).data('max_value');
          var main_course_id = $(this).data('main_course_id');
          var main_cat = $(this).data('cat');

          var total_amount = calculatedRemove(total_price, price);
          removed(id);
          var count_item = CountItem();
          var obj = $(this);
          obj.removeClass('selected');
          changeOverlay(main_cat, id);
          removeInput(id);
          UpdateSetAmount(total_amount, count_item)

      });
      $(document).on('click', ".deleted_cart", function() {
          var id = $(this).data('id');
          changeFont(id, 'plus');
          var total_price = $("#total_price").text();
          var price = $(this).data('price');
          var total_amount = calculatedRemove(total_price, price);

          if ($("#product_" + id).hasClass('selected') && $("#product_" + id).hasClass("skip_others")) {

              var id = $("#product_" + id).data('id');
              $("#product_" + id).removeClass("deleted_skip").removeClass('selected').removeClass(main_cat);
              removedSkip(id);
              var count_item = CountItem();
              removeInput(id);

          } else {
              $('#product_' + id).addClass("add_cart").removeClass("deleted").removeClass('selected');
              removed(id);
          }
          var main_cat = $(this).data('cat');
          $('#product_' + id).toggleClass(main_cat);


          var total_item = CountItem();
          changeOverlay(main_cat, id);
          removeInput(id);

          UpdateSetAmount(total_price, total_item);


      });

      function addItem(id, title, image, price, total_price, main_cat) {

          var markup = '<li class="clearfix " id="delete-' + id + '"><span class="float-left span-left"><img src="' + base_url + '/site_images/ingredient_images/' + image + '" alt="' + title + '">' + title + '<br />';
          if (price != 0) {

              calculatedTotal(total_price, price);
              markup = markup + '+ $' + price + '</span><span class="pull-right span-right btnRemoveItem  deleted_cart" data-cat="' + main_cat + '"  data-id="' + id + '" data-price="' + price + '" id="delete-' + id + '">X</span></li>';
          } else {

              markup = markup + '</span><span class="pull-right span-right btnRemoveItem  deleted_cart"  data-cat="' + main_cat + '"  data-id="' + id + '" data-price="' + price + '" id="delete-' + id + '">X</span></li>';
          }

          $("#add_list_cart").append(markup);

      }

      function removedSkip(id) {
          $('#delete-' + id).remove();
          $('#product_' + id).removeClass("deleted").removeClass('selected');
      }

      function removed(id) {
          $('#delete-' + id).remove();
          $('#product_' + id).addClass("add_cart").removeClass("deleted").removeClass('selected');
      }

      function CountItem() {
          var count_item = document.getElementById("add_list_cart").getElementsByTagName("li").length;
          $('#total_item').text('(' + count_item + ')');
          return count_item;
      }

      function calculatedTotal(total_price, price) {
          var sum = parseFloat(total_price) + parseFloat(price);
          $('#total_price').text(parseFloat(sum).toFixed(2));
      }

      function calculatedRemove(total_price, price) {
          var sum = parseFloat(total_price) - parseFloat(price);
          $('#total_price').text(parseFloat(sum).toFixed(2));
          return sum;
      }

      function Addform(product_name,short_code, id, value, ingredient_price, cat_title, total_price, image, main_course_id, total_items) {
          var form_pos = "<input type='hidden' value='" + id + "' class='form-control form_id_" + id + "' name='ingredient[product_" + id + "][ingredient_id]' > " +
              "<input type='hidden' value='" + product_name + "' class='form-control form_name_" + id + "' name='ingredient[product_" + id + "][product_name]' > " +
              "<input type='hidden' value='" + short_code + "' class='form-control form_code_" + id + "' name='ingredient[product_" + id + "][product_code]' > " +
              "<input type='hidden' value='" + value + "' class='form-control form_ingredient_" + id + "  " + cat_title + "' name='ingredient[product_" + id + "][span_value]' > " +
              "<input type='hidden' value='" + cat_title + "' class='form-control cat_title_" + id + "' name='ingredient[product_" + id + "][main_course]' > " +
              "<input type='hidden' value='" + image + "' class='form-control image_" + id + "' name='ingredient[product_" + id + "][image]' > " +
              "<input type='hidden' value='" + main_course_id + "' class='form-control main_course_id_" + id + "' name='ingredient[product_" + id + "][main_course_id]' > " +
              "<input type='hidden' value='" + ingredient_price + "' class='form-control ingredient_price_" + id + "' name='ingredient[product_" + id + "][ingredient_price]' > ";
          $("#cart_form").append(form_pos);
          UpdateSetAmount(total_price, total_items)
      }

      function removeInput(id) {
          $(".form_id_" + id).remove();
          $(".form_name_" + id).remove();
          $(".form_code_" + id).remove();
          $(".form_ingredient_" + id).remove();
          $(".cat_title_" + id).remove();
          $(".image_" + id).remove();
          $(".main_course_id_" + id).remove();
          $(".ingredient_price_" + id).remove();
      }

      function UpdateSetAmount(total_price, total_items) {

          $('#total_amount').val(parseFloat(total_price).toFixed(2));
          $('#total_items').val(total_items);

      }
  </script>
@endsection