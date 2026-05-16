@extends('layouts.theme')
@section('title','Edit Order')
@section('style')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{url('plugins/select2/select2.min.css')}}">
@endsection
@section('content')
    <div class="box box-info">

        <div class="box-header ">
            @if(Session::has('success'))
                <div class="alert alert-success alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('success') }}
                </div>
            @endif

            @if(Session::has('error'))

                <div class="alert alert-error alert-dismissable fade in">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    {{ Session::get('error') }}
                </div>
            @endif
            <div class="row">
                <div class="col-md-6"><h3 class="box-title">Order No: {{ $order->order_no }}</h3></div>
                <div class="col-md-6"><a class=" btn btn-primary" style="float: right;" href="{{url('orders/pending')}}">Back</a></div>
            </div>
            @foreach($products as $v)
                <div class="modal fade" id="productid-{{$v->id}}">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <div class="row">
                                    <div class="col-lg-6"><h4 class="modal-title">Product </h4></div>
                                    <div class="col-lg-6">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                </div>


                            </div>
                            <div class="modal-body">
                                <div class="card-body">
                                    <div class="col-md-10">
                                        <h4>Product Name: <span class="text-info">{{$v->title}}</span></h4>
                                    </div>

                                    <div class="col-md-2 text-info">
                                        <h4>$<span>{{$v->price}}</span></h4>
                                    </div>
                                    <form class="form-horizontal" method="post" action="{{url('admin/order-edit')}}">
                                        @csrf
                                        <input type="hidden" name="orderId" value="{{$order->id}}">
                                        <input type="hidden" name="orderType" value="Add">
                                        <input type="hidden" name="productId" value="{{$v->id}}">
                                        @if($v->category_id==1)
                                            <input type="hidden" name="type" value="fixed">
                                        @else
                                            <input type="hidden" name="type" value="customized">
                                        @endif
                                        <div class="card-body">
                                            @foreach($v->CustomizedProduct as $cat_data)
                                            <div class="form-group row">
                                                <label for="inputEmail_{{$cat_data->getMainCourse->id}}" class="col-sm-2 col-form-label">{{$cat_data->getMainCourse->name}}</label>
                                                <div class="col-sm-10">
                                                    @foreach($cat_data->getMainCourse->MainCourseList as $key=> $ingredient_data)
                                                    <div class="input-group">
                                                    <span class="input-group-addon">
                                                      <input type="checkbox" class="minimal"  @if($ingredient_data->Ingredients->price<=0) checked @endif name="ingredient[]" value="{{$ingredient_data->Ingredients->name}}"/>
                                                    </span>
                                                        <input type="text" class="form-control" readonly value="{{$ingredient_data->Ingredients->name}}
                                                        @if($ingredient_data->Ingredients->price>0) +  ${{$ingredient_data->Ingredients->price}}

                                                        @endif
                                                            "/>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                            @endforeach
                                            <div class="form-group row">
                                                <label for="inputPassword3" class="col-sm-2 col-form-label">Quantity</label>
                                                <div class="col-sm-4">
                                                    <input type="text" name="qty" value="1"  required class="form-control" id="inputPassword3" placeholder="Enter total quantity">
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card-body -->
                                        <div class="card-footer">
                                            <button type="submit" class="btn btn-info float-right">Save</button>
{{--                                            <button type="button" class="btn btn-primary float-right close" data-dismiss="modal" aria-label="Close">--}}
{{--                                                <span aria-hidden="true">&times;</span>--}}
{{--                                            </button>--}}
                                        </div>
                                        <!-- /.card-footer -->
                                    </form>
                                </div>

                            </div>

                        </div>
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
            @endforeach
        <!-- /.modal -->
        </div>
        <div class="box-body">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="row">
                <div class="col-md-6">
                    <select name="productName" id="productAdded" class="select2 form-control">
                        @foreach($products as $pro)
                            <option value="{{$pro->id}}"   >{{$pro->title}}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">

                </div>
            </div>
        </div>
        <div class="box-body">
            <div class="row">
                <div class="col-xs-12 table-responsive">
                    <table class="table table-striped" id="removetem">
                        <tbody>
                        <tr>
                            <th>Product Name</th>
                            <th>Ingredients</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Action</th>
                        </tr>
                        @foreach($order->details as $key=>$value)
                            <tr>
                                @if($value->product_type=='customized')
                                    <td>
                                        <strong>{{$value->product_name}}</strong>
                                    </td>
                                    <td>
                                        @if($value->exclude_item)
                                            @php ($rows = json_decode($value->exclude_item))
                                            @php ($name='')
                                            @foreach($rows as $row)
                                                <?php $name .= $row . ', '; ?>
                                            @endforeach
                                            {{rtrim($name, ', ')}}
                                        @endif

                                    </td>
                                    <td>{{$value->qty}}</td>
                                    <td>${{$value->price}}</td>

                                @endif
                                @if($value->product_type=='fixed')
                                    <td><strong>{{$value->product_name}}</strong></td>
                                    <td>N/A</td>
                                    <td>{{$value->qty}}</td>
                                    <td>${{$value->price}}</td>

                                @endif
                                    <td>
                                        <a href="javascript:;" class="action_imgs editItems">
                                            <img src="{{url('images/edit.svg')}}" data-orderdetail="{{$value->id}}" class="editItems" width="23" height="23"/>
                                        </a>

                                        <a href="javascript:;" class="action_imgs">
                                        <img src="{{url('images/remove.svg')}}" width="23" height="23" class="deleted" data-set_id="{{encrypt($value->id)}}">
                                        </a>
                                    </td>

                                    <div class="modal fade" id="orderId-{{$value->id}}">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <div class="row">
                                                        <div class="col-lg-6"><h4 class="modal-title">Edit Product </h4></div>
                                                        <div class="col-lg-6">
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                    </div>


                                                </div>
                                                <div class="modal-body">
                                                    <div class="card-body">
                                                        <div class="col-md-10">
                                                            <h4>Product Name: <span class="text-info">{{$value->product_name}}</span></h4>
                                                        </div>

                                                        <div class="col-md-2 text-info">
                                                            <h4>$<span>{{$value->price}}</span></h4>
                                                        </div>
                                                        <?php
                                                        $cuProduct=\App\Models\Product::where('title',$value->product_name)->first();
                                                        ?>
                                                        <form class="form-horizontal" method="post" action="{{url('admin/order-edit')}}">
                                                            @csrf
                                                            <input type="hidden" name="orderType" value="Edit">
                                                            <input type="hidden" name="orderDetailId" value="{{$value->id}}">
                                                            <input type="hidden" name="orderId" value="{{$order->id}}">
                                                            <input type="hidden" name="productId" value="{{$cuProduct->id}}">
                                                            @if($value->product_type=="fixed")

                                                                <input type="hidden" name="type" value="fixed">
                                                            @else
                                                                <input type="hidden" name="type" value="customized">
                                                            @endif
                                                            <div class="card-body">
                                                                @foreach($cuProduct->CustomizedProduct as $cat_data)
                                                                    <div class="form-group row">
                                                                        <label for="inputEmails_{{$cat_data->getMainCourse->id}}" class="col-sm-2 col-form-label">{{$cat_data->getMainCourse->name}}</label>
                                                                        <div class="col-sm-10">
                                                                            @foreach($cat_data->getMainCourse->MainCourseList as $key=> $ingredient_data)
                                                                                <?php
                                                                                $checkItems=json_decode($value->detail,true);

                                                                                ?>
                                                                                <div class="input-group">
                                                                                    <span class="input-group-addon">
                                                                                      <input type="checkbox" class="minimal"
                                                                                           @if(in_array($ingredient_data->Ingredients->name,$checkItems))   checked  @endif
                                                                                      name="ingredient[]" value="{{$ingredient_data->Ingredients->name}}"/>
                                                                                    </span>
                                                                                    <input type="text" class="form-control" readonly value="{{$ingredient_data->Ingredients->name}}
                                                                                    @if($ingredient_data->Ingredients->price>0) +  ${{$ingredient_data->Ingredients->price}}

                                                                                    @endif
                                                                                        "/>
                                                                                </div>
                                                                            @endforeach
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                                <div class="form-group row">
                                                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Quantity</label>
                                                                    <div class="col-sm-4">
                                                                        <input type="text" name="qty" value="{{$value->qty}}"  required class="form-control" id="inputPassword3" placeholder="Enter total quantity">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- /.card-body -->
                                                            <div class="card-footer">
                                                                <button type="submit" class="btn btn-info float-right">Save</button>
                                                            </div>
                                                            <!-- /.card-footer -->
                                                        </form>
                                                    </div>

                                                </div>

                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>

                            </tr>
                        @endforeach
                        <tr>

                            <td colspan="3" style="text-align: right">
                                Sub Total <br>
                                Sale tax<br>
                                Discount
                            </td>
                            <td>
                                ${{$order->subTotal}} <br>
                                ${{$order->taxAmount}}
                                <br>
                                ${{$order->discountAmount}}
                            </td>

                        </tr>
                        <tr>
                            <th colspan="3" style="text-align: right">Total</th>
                            <th>${{$order->total_amount}}</th>
                        </tr>
                        </tbody>
                    </table>
                </div>

                <!-- /.col -->

            </div>

        </div>

        <!-- /.box-body -->

    </div>
    <div class="modal fade" id="deleted_model">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Delete Confirmation</h4>
                </div>
                <form action="{{url('admin/order/deleted')}}" method="post" >
                    <div class="modal-body">
                        <h3 class="text-center text-danger">Are you sure you want to delete this?</h3>

                        {{csrf_field()}}
                        <input name="id" type="hidden" id="deleted_item" value="">
                        <input name="_method" type="hidden" value="DELETE">

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

@endsection

@section('script')


    <!-- Select2 -->
    <script src="{{url('js/order/edit-order.js')}}"></script>
    <script src="{{url('plugins/select2/select2.full.min.js')}}"></script>

    <script>
        $('.select2').select2({
            placeholder: 'Select a product',
            allowClear: true
        });


    </script>
@endsection

