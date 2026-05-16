<div class="modal fade fixed-model" id="myModal" role="dialog">

    <div class="modal-dialog cart-model">



        <!-- Modal content-->

        <div class="modal-content ">

            <div class="modal-header">

                <h4 class="modal-title">My Cart</h4>

                <button type="button" class="close" data-dismiss="modal">&times;</button>

            </div>



            @if(Session::get('orders'))

                <div class="modal-body bag-body clearfix">

                    <?php

                    $sum=0;

                    $data=Session::get('orders');

                    ?>

                    @foreach($data as $key => $values)



                        @if($values['type']=="meal")

                            <div class="wrap-one">

                                <h2>{{$values['product_name']}}<span > <small>(Sr. {{$values['serial_number']}})

                                            @if($values['name'] !='')

                                                ( {{$values['name']}} )

                                            @endif

                    </small></span></h2>

                                <div class="clearfix span-item">

                                    <span class="amount-bag">${{$values['price']}}</span>

                                    <span class="call-bag">( {{$values['qty']}} quantity )</span>

                                </div>



                                <p>

                                    <?php  $store_ingredient=$values['data'];     $_name=''; ?>

                                    @foreach($store_ingredient as $key_data=> $ingredient)

                                        <?php $_name  .= $ingredient['product_name'].', '; ?>



                                    @endforeach

                                    {{rtrim($_name, ', ')}}

                                    <br>

                                    @if($values['note'] !='')

                                        <strong>Special instructions: </strong>

                                        {{$values['note']}}

                                    @endif

                                </p>

                                <div class="dual-buttons">

                                    <a href="#" class="btn remove alert_removed change-btn" data-remove_id="{{$key}}" >Remove</a>

                                    <a href="{{url('edit/meal/'.$key)}}" class="btn remove change-btn"  >Change</a>



                                </div>

                            </div>

                        @endif

                        @if($values['type']=="fixed")

                            <div class="wrap-one">

                                <h2>{{$values['product_name']}}



                                </h2>

                                <div class="clearfix span-item">

                                    <span class="amount-bag">${{$values['price']}}</span>

                                </div>

                                <ul class="itemlist">

                                    <li><span>{{$values['product_name']}} ({{$values['qty']}})</span></li>

                                    {{--<li><span>.</span></li>--}}

                                </ul>



                                <form action="{{url('edit/fixed')}}" method="post">
                                    @csrf
                                    <div class="add-value">

                                        <button type="button" class="sub">-</button>

                                        <input class="vlaue" name="qty" type="number" value="{{$values['qty']}}" />

                                        <button type="button" class="add">+</button>

                                    </div>



                                    <input type="hidden" value="{{$key}}" name="id">

                                    <?php  $store_ingredient=$values['data'];      ?>

                                    @foreach($store_ingredient as $key_data=> $product)

                                        <input type="hidden" value="{{$product}}" name="product_id">

                                    @endforeach

                                    <input type="hidden" value="{{$values['product_name']}}" name="product_name">

                                    <div class="dual-buttons">

                                        <a href="#" class="btn remove alert_removed change-btn" data-remove_id="{{$key}}" >Remove</a>

                                        <button type="submit"  class="btn remove change-btn"  >Update</button>

                                    </div>

                                </form>

                            </div>

                        @endif



                        @if($values['type']=="customized")

                            <div class="wrap-one">

                                <h2>{{$values['product_name']}}

                                    <span> <small>

                                                           @if($values['name'] !='')

                                                ( {{$values['name']}} )

                                            @endif

                                                           </small></span>

                                </h2>

                                <div class="clearfix span-item">

                                    <span class="amount-bag">${{$values['price']}}</span>

                                </div>

                                <ul class="itemlist">

                                    <?php  $ingredients_cus=$values['data'];   $_name_in='';   ?>



                                    @foreach($ingredients_cus as  $ingredients)



                                        <?php $_name_in  .= $ingredients.', '; ?>

                                    @endforeach

                                    {{rtrim($_name_in, ', ')}},

                                    <br>

                                    @if($values['note'] !='')

                                        <strong>Special instructions: </strong>

                                        {{$values['note']}}

                                    @endif



                                    {{--<li><span>{{$ingredients}},</span></li>--}}



                                </ul>

                                <form action="{{url('edit/customized')}}" method="post">

                                    @csrf

                                    <input type="hidden" value="{{$key}}" name="id">

                                    <div class="add-value">

                                        <button type="button" class="sub">-</button>

                                        <input class="vlaue" type="number" value="{{$values['qty']}}"  name="qty"/>

                                        <button type="button" class="add">+</button>

                                    </div>

                                    <div class="dual-buttons">

                                        <a href="#" class="btn remove alert_removed change-btn" data-remove_id="{{$key}}" >Remove</a>

                                        <button type="submit"  class="btn remove change-btn"  >Update</button>

                                    </div>

                                </form>

                            </div>

                        @endif

                        <?php $sum=$sum+$values['price'];?>



                    @endforeach

                    <div class="modal-footer_customs ">

                        <?php
                        $store = \App\Models\StoreOpening::first();
                        $currentTime = date('H:i: a', strtotime(\Carbon\Carbon::now()));
                        $startTime = date('H:i: a', $store->opening_time);
                        $endTime = date('H:i: a', $store->closing_time);

                        ?>
                        @if($store->status)

                            @if($currentTime > $startTime && $currentTime < $endTime)

                                <a href="{{url('cart')}}"
                                   class="btn  add-itembtn change-btn test_click "
                                   id="cart_click">Go To Checkout <span>${{$sum}}</span></a>

                                {{--onclick="event.preventDefault();
                    document.getElementById('cart_item_sub').submit();"
                                <form style="display: none;" action="{{url('checkout')}}" method="post"  enctype="multipart/form-data" id="cart_item_sub">--}}
                                    {{--@csrf--}}
                                    {{--<input type="hidden" value="0" name="is_cart_form">--}}
                                {{--</form>--}}
                            @else
                                <a href="javascript:;" data-toggle="modal" data-target="#model_closed_store"
                                   class="btn  add-itembtn change-btn test_click " id="closed_store">Go To Checkout <span>${{$sum}}</span></a>
                            @endif


                        @else
                            <a href="javascript:;" data-toggle="modal" data-target="#model_closed_store"
                               class="btn  add-itembtn change-btn test_click " id="closed_store"
                            >Go To Checkout <span>${{$sum}}</span></a>
                        @endif





                        <a   class=" btn add-itembtn change-btn" href="{{url('/home/side_menus')}}">Add more items</a>

                        <a href="#" class="btn add-itembtn change-btn alert_cleared"   id="cleared_cart">Clear Bag</a>





                    </div>

                </div>





            @else



                <div class="modal-header">

                    <h4 class="modal-title">Empty Cart</h4>



                </div>

            @endif

        </div>



    </div>

</div>



