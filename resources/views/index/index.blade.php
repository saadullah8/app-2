@extends('layouts.master')

@section('title','Home')

@section('style')

    <style>
        .wrap_display {
            display: none;
        }

        #newsletter .input-group2 input {

            width: 94%;

            height: 45px;

            font-size: 15px;

            border: 0;

            /*border-radius: 10px;*/

            padding: 16px;

            margin: 10px;

        }

    </style>

@endsection

@section('content')

    <!-- Slider Start -->

    @php ($count_add=100)



    <div class="slide">

        <div class="slideshow owl-carousel">

            <!-- Slider Backround Image Start -->

            <div class="item"><img src="{{url('assets/sliders/banner-1.jpg')}}" alt="banner" title="banner"
                                   class="img-fluid"/></div>

            <div class="item"><img src="{{url('assets/sliders/banner-2.jpg')}}" alt="banner" title="banner"
                                   class="img-fluid"/></div>

            <div class="item"><img src="{{url('assets/sliders/banner-3.jpg')}}" alt="banner" title="banner"
                                   class="img-fluid"/></div>

            <div class="item"><img src="{{url('assets/sliders/banner-4.jpg')}}" alt="banner" title="banner"
                                   class="img-fluid"/></div>

            <!-- Slider Backround Image End -->

        </div>


        <!-- Slide Detail Start  -->

        <div class="slide-detail">

            <div class="container"><img src="{{url('assets/images/logo/logo-icon.png')}}" alt="logo1"
                                        class="img-fluid"/>

                <h4>SAVE TIME, ORDER ONLINE!</h4>
                <h4>MON-SUN :11:00 AM TO 09:00 PM</h4>
                <h4>LAST ONLINE ORDER: 08:45 PM</h4>


                <a class="btn-primary btn btn-wide" href="{{url('#menus')}}">Today's menu</a></div>

        </div>

        <!-- Slide Detail End  -->

    </div>

    @foreach($sectionProducts as $value)
    <!-- all product start-->
    <div class="menu" id="{{str_replace(' ','',$value->name)}}">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 commontop text-center">
                    <h4>{{$value->name}}</h4>
                    <div class="divider style-1 center">
                        <span class="hr-simple left"></span>
                        <i class="icofont icofont-ui-press hr-icon"></i>
                        <span class="hr-simple right"></span>
                    </div>
                </div>
                <div class="col-lg-12 ">
                    <div class="tab-content">
                            <div class="tab-pane   active  show ">
                                <div class="row">
                                    <?php $products_cat = $value->getProduct;  ?>
                                    @foreach($products_cat as $product)
                                        <div class="col-md-6 col-sm-6 col-xs-12 ">
                                            <!-- Box Start -->
                                            <diV class="box home-box">
                                                <div class="image">
                                                    <img src="{{url('site_images/product_images/'.$product->image)}}"
                                                        alt="{{$product->title}}" class="img-fluid"/>
                                                </div>
                                                <div class="caption clearfix">
                                                    <h4>{{$product->title}}</h4>
                                                    <span>{{$product->detail}}</span>
                                                    <div class="price">${{$product->price}}</div>

                                                    <!----------------------add to cart popup condition implement-------------------->
                                                @if($product->status==0)
                                                    <button type="button" data-toggle="modal"
                                                            data-target="#model_category_{{$product->id}}"
                                                            class="btn btn-theme btn-wide add-btn">Add To Cart
                                                    </button>
                                                @else
                                                        <button type="button" class="btn btn-theme btn-wide add-btn">
                                                            Temporarily out of stock
                                                        </button>
                                                @endif
                                                    <!----------------------add to cart popup condition implement-------------------->
                                                </div>
                                            </div>
                                            <!-- Box End -->
                                        </div>
                                            @if($product->status==0)
                                                <div class="modal fade" id="model_category_{{$product->id}}" role="dialog">
                                            <div class="modal-dialog add-cart2 second-cart">
                                                <!-- Modal content-->
                                                <form action="{{url('add/product/cart')}}" class="productFrom{{$product->id}}" method="post">
                                                    @csrf
                                                    <div class="modal-content slider-model">
                                                        <div class="main-bodypopup">
                                                            <div class="modal-header slide-card">
                                                                <div class="slanted-image__container">
                                                                    <div class="slanted-image__cover"></div>
                                                                    <div class="slanted-image__image">
                                                                        <img src="{{url('site_images/product_images/'.$product->image)}}" alt="{{$product->title}}">
                                                                    </div>
                                                                </div>
                                                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                            </div>
                                                            <input type="hidden" value="{{$product->id}}" name="product_id">
                                                            <input type="hidden" value="{{$product->title}}" name="product_name">
                                                            <input type="hidden" value="{{$product->price}}" name="total_price" id="total_price_{{$product->id."_".$value->id}}">
                                                            <div class="modal-body addcart-body clearfix">
                                                                <div class="wrap-one">
                                                                    <h2>{{$product->title}}</h2>
                                                                    <div class="clearfix span-item">
                                                                        <span class="amount-bag">${{$product->price}}</span>
                                                                    </div>
                                                                </div>

                                                                <div class="value-wrap">
                                                                    <p> *If you would to like have sauces on side- simply write in the special instructions.</p>
                                                                    <?php $cat_slists = $product->CustomizedProduct?>
                                                                    <?php
                                                                    $cat_extraPriceIds='';
                                                                    ?>
                                                                    @foreach($cat_slists as $cat_data)
                                                                        <h4>{{$cat_data->getMainCourse->name=="Choice Of Sauce"?$cat_data->getMainCourse->name.' (Select Up to 4)':$cat_data->getMainCourse->name}} </h4>
                                                                        <ul class="list-unstyled dish-list clearfix">
                                                                            @foreach($cat_data->getMainCourse->MainCourseList as $key=> $ingredient_data)
                                                                                <li class="clearfix">
                                                                                    <?php $count_add = $count_add + 101 * 2;
                                                                                    $extraId=$key+$count_add;
                                                                                    $extraId="styled-checkbox-".$extraId
                                                                                    ?>
                                                                                        @if($ingredient_data->Ingredients->price>0)
                                                                                            <?php
                                                                                            $cat_extraPriceIds=$extraId.','.$cat_extraPriceIds;
                                                                                            ?>
                                                                                        @endif
                                                                                    <input class="styled-checkbox checkboxCustomizeProduct @if($cat_data->getMainCourse->name=="Choice Of Sauce") {{'product_'.$product->id}} @endif"
                                                                                            id="{{$extraId}}"
                                                                                            type="checkbox"
                                                                                            name="ingredient[]"
                                                                                            data-productid="{{$product->id."__".$product->id}}"
                                                                                            data-qtyinput="{{"qty_".$product->id."_".$value->id}}"
                                                                                            data-productprice="{{$product->price}}"
                                                                                            data-extraprice="{{$ingredient_data->Ingredients->price}}"
                                                                                            data-extrapriceids="{{$cat_extraPriceIds}}"


                                                                                            value="{{$ingredient_data->Ingredients->name}}">
                                                                                    <label for="{{$extraId}}">{{$ingredient_data->Ingredients->name}}
                                                                                    @if($ingredient_data->Ingredients->price>0) +  ${{$ingredient_data->Ingredients->price}}

                                                                                        @endif</label>
                                                                                </li>
                                                                            @endforeach
                                                                        </ul>
                                                                    @endforeach
                                                                    <div class="col-sm-12 col-md-12 form-group" id="newsletter" style="padding: 0px">
                                                                        <div class="input-group2 ">
                                                                            <input id="note_write"  name="note"     placeholder="Special instructions" type="text">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="add-value value-wrap">
                                                                    <button type="button" class="sub" data-extraPriceId="{{$cat_extraPriceIds}}" data-id="{{$product->id."__".$product->id}}" data-price="{{$product->price}}">-</button>
                                                                    <input class="vlaue" type="number"  id="{{"qty_".$product->id."_".$value->id}}" value="1" name="qty"/>
                                                                    <button type="button" class="add" data-extraPriceId="{{$cat_extraPriceIds}}" data-id="{{$product->id."__".$product->id}}" data-price="{{$product->price}}">+</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer footer-btn cart-footer">
                                                            @if(in_array($value->name,$optionalCategory))
                                                            <button type="submit" class="checkout-btn btnCheckout" data-optionalSauce="yes" data-productuniqueid="{{$product->id}}">Add To Bag • $<span id="total_d_price_{{$product->id."__".$product->id}}">{{$product->price}}</span></button>
                                                            @else
                                                                <button type="submit" class="checkout-btn btnCheckout" data-optionalSauce="no" data-productuniqueid="{{$product->id}}">Add To Bag • $<span id="total_d_price_{{$product->id."__".$product->id}}">{{$product->price}}</span></button>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                            @endif
                                    @endforeach
                                </div>
                            </div>
                            <!--  Menu Tab End  -->
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Popular Dishes End -->
    @endforeach
    <!-- Food Menu Start -->
    <div class="menu" id="menus">
        <div class="menu-inner">
            <div class="container">
                <div class="row ">
                    <!-- Title Content Start -->
                    <div class="col-sm-12 col-xs-12 commontop text-center">
                        <h4>Our Menu</h4>
                        <div class="divider style-1 center"><span class="hr-simple left"></span>
                            <i class="icofont icofont-ui-press hr-icon"></i> <span class="hr-simple right"></span></div>
                        <p>Come and try us, we promise you will not be disappointed!</p>
                    </div>
                    <!-- Title Content End -->
                    <div class="col-sm-12 col-xs-12" id="main_tab">
                        <!--  Menu Tabs Start  -->
                        <ul class="nav nav-tabs list-inline">
                            @foreach($categories as $value)
                                    <li class="nav-item">
                                        <a  class="nav-link @if(strtolower(str_replace(' ', '_', $value->name))=='appetizers') active @endif"
                                            href="{{ '#'.strtolower(str_replace(' ', '_', $value->name))}}"
                                            data-toggle="tab" aria-expanded="false">
                                            {{$value->name}}
                                        </a>
                                    </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach($categories as $value)
                                    <div class="tab-pane @if(strtolower(str_replace(' ', '_', $value->name))=='appetizers') active  show @endif"
                                        id="{{strtolower(str_replace(' ', '_', $value->name))}}">
                                        <div class="row">
                                            <?php $products_cat = $value->getProduct;  ?>
                                            @foreach($products_cat as $product)
                                                <div class="col-md-6 col-sm-6 col-xs-12">
                                                        <!-- Box Start -->
                                                        <diV class="box home-box">

                                                            <div class="image"><img
                                                                    src="{{url('site_images/product_images/'.$product->image)}}"
                                                                    alt="{{$product->title}}" class="img-fluid"/></div>

                                                            <div class="caption clearfix">

                                                                <h4>{{$product->title}}</h4>

                                                                <span>{{$product->detail}}</span>

                                                                <div class="price">${{$product->price}}</div>

                                                                <!----------------------add to cart popup condition implement-------------------->
                                                            @if($product->status==0)
                                                                <button type="button" data-toggle="modal"
                                                                        data-target="#model_category_{{$product->id}}"
                                                                        class="btn btn-theme btn-wide add-btn">Add To
                                                                    Cart
                                                                </button>
                                                            @else
                                                                    <button type="button"
                                                                            class="btn btn-theme btn-wide add-btn">
                                                                        Temporarily out of stock
                                                                    </button>
                                                            @endif

                                                                <!----------------------add to cart popup condition implement-------------------->

                                                            </div>

                                                        </div>

                                                        <!-- Box End -->

                                                    </div>
                                                    @if($product->status==0)
                                                <div class="modal fade" id="model_category_{{$product->id}}"
                                                         role="dialog">

                                                        <div class="modal-dialog add-cart2">

                                                            <!-- Modal content-->

                                                            <form action="{{url('product/add/cart')}}" method="post">
                                                                @csrf
                                                                <div class="modal-content slider-model">

                                                                    <div class="main-bodypopup">

                                                                        <div class="modal-header slide-card">

                                                                            <div class="slanted-image__container">

                                                                                <div class="slanted-image__cover"></div>

                                                                                <div class="slanted-image__image"><img
                                                                                        src="{{url('site_images/product_images/'.$product->image)}}"
                                                                                        alt="{{$value->title}}"></div>

                                                                            </div>

                                                                            <button type="button" class="close"
                                                                                    data-dismiss="modal">&times;
                                                                            </button>

                                                                        </div>


                                                                        <input type="hidden" value="{{$product->id}}"
                                                                               name="product_id">

                                                                        <input type="hidden" value="{{$product->title}}"
                                                                               name="product_name">

                                                                        <input type="hidden" value="{{$product->price}}"
                                                                               name="total_price"
                                                                               id="total_price_{{$product->id}}">

                                                                        <div class="modal-body addcart-body clearfix">

                                                                            <div class="wrap-one">

                                                                                <h2>{{$product->title}}</h2>

                                                                                <div class="clearfix span-item"><span
                                                                                        class="amount-bag">${{$product->price}}</span>
                                                                                </div>

                                                                            </div>

                                                                            <div class="add-value value-wrap">

                                                                                <button type="button" class="sub"
                                                                                        data-id="{{$product->id."_".$product->id}}"
                                                                                        data-price="{{$product->price}}">
                                                                                    -
                                                                                </button>

                                                                                <input class="vlaue" type="number"
                                                                                       value="1" name="qty" checked/>

                                                                                <button type="button" class="add"
                                                                                        data-id="{{$product->id."_".$product->id}}"
                                                                                        data-price="{{$product->price}}">
                                                                                    +
                                                                                </button>

                                                                            </div>

                                                                        </div>


                                                                    </div>

                                                                    <div class="modal-footer footer-btn cart-footer">

                                                                        <button type="submit" class="checkout-btn">Add
                                                                            To Bag • $<span
                                                                                id="total_d_price_{{$product->id."_".$product->id}}">{{$product->price}}</span>
                                                                        </button>

                                                                    </div>

                                                                </div>

                                                            </form>

                                                        </div>

                                                    </div>
                                                    @endif
                                            @endforeach
                                        </div>
                                    </div>
                            <!--  Menu Tab End  -->
                            @endforeach
                        </div>
                        <!--------------add to cart  model popup------------>
                        <!-- Modal -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Food Menu End -->
    <!-- Newsletter Start -->

    <div id="newsletter">

        <div class="container">

            <div id="subscribe">

                <!-- Subscribe Form -->

                <form class="form-horizontal" name="subscribe">
                    @csrf
                    <div class="row">

                        <div class="col-sm-6 col-md-7">

                            <div class="input-group"><span class="news">newsletter</span>

                                <p>Join Our News Letter and Marketing Correspondence<br/> We'll send you news and
                                    offers.</p>

                            </div>

                        </div>

                        <div class="col-sm-6 col-md-5 form-group">

                            <div class="input-group">

                                <input value="" name="subscribe_email" id="subscribe_email" placeholder="Email"
                                       type="text">

                                <button class="btn btn-news" disabled type="submit" value="submit">Send</button>

                            </div>

                        </div>

                    </div>

                </form>

                <!-- Subscribe Form -->

            </div>

        </div>

    </div>

    <!-- Newsletter End -->
    <div class="modal fade" id="pickup_delivery_popup" role="dialog">
        <div class="modal-dialog add-cart2">
            <!-- Modal content-->
            <div class="modal-content slider-model">
                <div class="main-bodypopup">
                    <div class="modal-header slide-card">
                        <div class="slanted-image__container">
                            <div class="slanted-image__cover"></div>
                            <div class="slanted-image__image">
                                <img src="{{url('img/pickup.png')}}" alt="Pickup and delivery option">
                            </div>
                        </div>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body addcart-body clearfix">
                        <div class="wrap-one" style="margin-bottom:0px">
                            <h2>Please Choose One Option</h2>
                        </div>
                        <div class="loginnow get-password" style="margin-bottom:0px;background: #ffffff">
                                <div class="form-group">
                                    <label>
                                        <input type="radio" name="pickup"  value="pickup" checked> Pickup
                                    </label>
                                    <br>
                                    <label>
                                        <input type="radio" name="pickup" value="delivery"> Delivery
                                    </label>
                                </div>
                                <div class="form-group" id="pickupDive" style="margin-left: 30px;margin-top: -12px; display: none">
{{--                                    <label>--}}
{{--                                        <input type="radio" name="thirdParty"  value="Grub hub"> Grub hub--}}
{{--                                    </label>--}}
{{--                                    <br>--}}
{{--                                    <label>--}}
{{--                                        <input type="radio" name="thirdParty" value="Uber eats">  Uber eats--}}
{{--                                    </label><br>--}}
                                    <label>
                                        <input type="radio" name="thirdParty" value="door dash">  door dash
                                    </label>
                                </div>
                                <div class="form-group">
                                    <button type="button"  id="pickUpAndDelivery" class="btn btn-theme btn-md btn-wide">Save</button>
                                </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')

    <script>
        $('.btnCheckout').click(function (e) {
            e.preventDefault();
            var isOptional=$(this).data('optionalsauce');
            var pid=$(this).data('productuniqueid');

            if(isOptional=='yes'){
                $('.productFrom'+pid).submit()

            }else{
                var checkedValue = $('.product_'+pid+':checked').val();
                if(checkedValue === undefined){
                    alert('Please select at least one sauce')
                }else{
                    $('.productFrom'+pid).submit()
                }
            }



        })
        $(document).ready(function() {

            $("#pickup_delivery_popup").on("click", "#pickUpAndDelivery", function(e) {
                e.preventDefault();
                var  options=$("input[name='pickup']:checked").val();
                if(options=="pickup"){
                    localStorage.setItem('isshow', 1);
                    $('#pickup_delivery_popup').modal('hide');
                }else{

                    var  value=$("input[name='thirdParty']:checked").val();
                    if(value=== undefined){
                        $('#pickupDive').show()
                    }else{
                        if (value=="Grub hub"){
                            goToURL("https://www.grubhub.com/");
                        }
                        if (value=="Uber eats"){
                            goToURL("https://www.ubereats.com/");
                        }
                        if (value=="door dash"){
                            goToURL("https://www.doordash.com/store/gyro-joint-johnstown-24659320/");
                        }

                    }
                }

            });

        });
        function goToURL(url) {
            location.href = url;
        }
        // $(document).ready(function(){
        //     var isshow = localStorage.getItem('isshow');
        //     if (isshow== null) {
        //          $("#pickup_delivery_popup").modal('show');
        //     }
        // });

        $(".tab_active_a").click(function () {
            moveToDiv('RicePlatters')
        });
        $(".tab_active_b").click(function () {
            moveToDiv('Sandwiches')
        });
        $(".tab_active_c").click(function () {
            moveToDiv('Gyros')
        });

        $(".tab_active_d").click(function () {
            moveToDiv('Salad')
        });
        $(".tab_active_f").click(function () {
            moveToDiv('Wings')
        });

        $(".tab_active_click_menu").click(function () {
            moveToDiv('menus')
        });
        $(".tab_active_click_shawarma1").click(function () {
            moveToDiv('Shawarma')
        });
        function moveToDiv(div_id) {
            $('html, body').animate({
                scrollTop: $("#" + div_id).offset().top
            }, 1500);
        }

        @if($title)

        activeSide('menus');

        @endif

        function activaTab(tab) {
            $('.nav-tabs a[href="#' + tab + '"]').tab('show');
        }

        function activeSide(tab) {
            $('.nav-tabs a[href="#' + tab + '"]').tab('show');
            $("html, body").animate({
                scrollTop: '3500'+"px"
            }, 1500);
        }
        setInterval(function() {
            window.location.reload();
        }, 60000);
    </script>
@endsection
