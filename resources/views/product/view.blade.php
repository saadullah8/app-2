@extends('layouts.theme')
@section('title','Product')
@section('style')
<style>
.img-algin{
margin: 8px;
}
</style>
@endsection

@section('content')
<!-- row -->
      <div class="row">
        <div class="col-md-12">
          <!-- The time line -->
          <ul class="timeline">
            <!-- timeline time label -->
            <li class="time-label">
                  <span class="bg-green">
                  {{date('d M. Y',strtotime($product->created_at))}}

                  </span>
            </li>
            <!-- /.timeline-label -->
            <!-- timeline item -->
            <li>
              <i class="fa fa-rub bg-blue"></i>

              <div class="timeline-item">
              <?php
                      $created = new \Carbon\Carbon($product->created_at);
                        $now = \Carbon\Carbon::now();
                        $product_created = ($created->diff($now)->days < 1)
                            ? 'today'
                            : $created->diffForHumans($now);
                    ?>
                <span class="time"><i class="fa fa-clock-o"></i> {{$product_created}}</span>

                <h3 class="timeline-header"><a href="#">Product Name</a> {{$product->title}}</h3>

                <div class="timeline-body">
                 {{$product->detail}}
                </div>

              </div>
            </li>
            <!-- END timeline item -->
            <!-- timeline item -->
            <li>
              <i class="fa fa-dollar bg-aqua"></i>

              <div class="timeline-item">


                <h3 class="timeline-header no-border"><a href="#">Product Price</a> ${{$product->price}}</h3>
              </div>
            </li>
             <li>
              <i class="fa  fa-filter bg-aqua"></i>

              <div class="timeline-item">

                <h3 class="timeline-header no-border"><a href="#">Category</a> {{$product->getType? $product->getType->name:'No Category'}}</h3>
              </div>
            </li>
             <li>
              <i class="fa fa-tree bg-aqua"></i>

              <div class="timeline-item">

                <h3 class="timeline-header no-border"><a href="#">Type Of Product</a> {{$product->category->name}}</h3>
              </div>
            </li>

             <?php   $option=$product->CustomizedProduct; ?>
             {{--@if($product->category_id==2)--}}
             @if(count($option)>0)
                 <li>
              <i class="fa  fa-spoon bg-purple"></i>
            @foreach($option as $value)
              <div class="timeline-item">
                                    <?php
                                        $created = new \Carbon\Carbon($value->created_at);
                                          $now = \Carbon\Carbon::now();
                                          $difference = ($created->diff($now)->days < 1)
                                              ? 'today'
                                              : $created->diffForHumans($now);
              						?>

                <span class="time"><i class="fa fa-clock-o"></i> {{$difference}}</span>

                <h3 class="timeline-header"><a href="Javascript:;">Main Course</a> {{$value->getMainCourse->name}}</h3>

                <div class="timeline-body">
                    <div class="row">
                    <?php $ingredientList=$value->getMainCourse->MainCourseList; ?>
                      @foreach($ingredientList as $ingre)
                       <div class="col-md-2">
                           <div class="thumbnail">
                                   <img src="{{url('site_images/ingredient_images/'.$ingre->Ingredients->image)}}"  width="125" >
                                   <div class="caption">
                                     <p>{{$ingre->Ingredients->name}}</p>
                                   </div>
                               </div>
                           </div>
                     @endforeach
                    </div>
                    @if($product->category_id==3)
               <div class="row">
                <div class="col-md-4">
                <div class="box box-primary">
                   <div class="box-header with-border">
                     <h3 class="box-title">Limitation</h3>
                   </div>
                   <!-- /.box-header -->
                   <div class="box-body">
                     <strong><i class="fa  fa-balance-scale margin-r-5"></i> Min Value & Max Value</strong>

                     <p class="text-info">

                       ( Mim Limit - {{$value->min_value?  $value->min_value:$value->min_value}} ,
                         Max Limit - {{$value->max_value==100? 'All':$value->max_value}} )
                     </p>
                   </div>
                   <!-- /.box-body -->
                 </div>
                </div>
                {{--<div class="col-md-4">--}}
                     {{--<div class="box box-primary">--}}
                        {{--<div class="box-header with-border">--}}
                          {{--<h3 class="box-title">Other Options</h3>--}}
                        {{--</div>--}}
                        {{--<!-- /.box-header -->--}}
                        {{--<div class="box-body">--}}

                          {{--<strong><i class="fa fa-pencil margin-r-5"></i> Option </strong>--}}

                          {{--<p>--}}
                            {{--<span class="label label-danger">{{$value->half? 'Half':'No Half'}}</span>--}}
                            {{--<span class="label label-success">{{$value->dressing? 'Dressing on site':'No Dressing on site'}}</span>--}}
                            {{--<span class="label label-info">{{$value->skip? 'Skip':'No Skip'}}</span>--}}
                          {{--</p>--}}

                        {{--</div>--}}
                        {{--<!-- /.box-body -->--}}
                      {{--</div>--}}
                     {{--</div>--}}
                {{--<div class="col-md-4">--}}
                    {{--<div class="box box-primary">--}}
                       {{--<div class="box-header with-border">--}}
                         {{--<h3 class="box-title">If Extra Option</h3>--}}
                       {{--</div>--}}
                       {{--<!-- /.box-header -->--}}
                       {{--<div class="box-body">--}}
                          {{--<strong><i class="fa  fa-plus-square margin-r-5"></i> Extra Charge?</strong>--}}

                         {{--<p class="text-muted">--}}

                         {{--{{$value->extra_charge? '$'.$value->extra_price:''}}--}}
                       {{--( Mim Limit - {{$value->min_extra_limit?  $value->min_extra_limit:$value->min_extra_limit}} ,--}}
                        {{--Max Limit - {{$value->max_extra_limit==100? 'All':$value->max_extra_limit}} )--}}
                         {{--</p>--}}
                       {{--</div>--}}
                        {{----}}
                     {{--</div>--}}
                    {{--</div>--}}

               </div>
                @endif
                </div>
              </div>
              @endforeach
            </li>
            @endif
            {{--@endif--}}

            <!-- END timeline item -->
            <!-- timeline item -->
            <li>
              <i class="fa fa-camera bg-purple"></i>

              <div class="timeline-item">
                {{--<span class="time"><i class="fa fa-clock-o"></i> 5 days ago</span>--}}

                <h3 class="timeline-header">Product Image</h3>

                <div class="timeline-body">
                  <div class="embed-responsive embed-responsive-16by9">
                  <img src="{{url('site_images/product_images/'.$product->image)}}" class="img-responsive" >

                  </div>
                </div>
                {{--<div class="timeline-footer">--}}
                  {{--<a href="#" class="btn btn-xs bg-maroon">See comments</a>--}}
                {{--</div>--}}
              </div>
            </li>
            <!-- END timeline item -->
            <li>
              <i class="fa fa-clock-o bg-gray"></i>
            </li>
          </ul>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->


@endsection