<!DOCTYPE html>

<html>

<head>

  <meta charset="utf-8">

  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <title>Hummus Mediterranean Grill</title>



   <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">



  <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">

    <!-- Font Awesome -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">

    <!-- Ionicons -->

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    <!-- Theme style -->

    <link rel="stylesheet" href="{{url('css/AdminLTE.min.css')}}">

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->

  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->

  <!--[if lt IE 9]>

  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>

  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>

  <![endif]-->



  <!-- Google Font -->

  <style>

  .invoice {

      position: relative;

      background: #fff;

      border: 0px solid #f4f4f4;

      padding: 0px;

      /* margin: 10px 25px; */

  }

  .table{

  margin: 0px;

  font-size: 15px;

  margin-bottom:0px;

  }

  .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{

  padding: 6px;

  border-top: 1px solid #000;

  }

  </style>

  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

</head>

<body>

<div class="wrapper">

  <!-- Main content -->

  <section class="invoice">

    <!-- info row -->

    <div class="row invoice-infos">

      <div class="col-xs-12 table-responsive">

         <!--<table class="table">-->



               



         <!--  </table>-->

      </div>



    </div>

    <!-- /.row -->



    <!-- Table row -->

    <div class="row">

      <div class="col-xs-12 table-responsive">

        <table class="table table-striped" cellpadding="0" cellspacing="0">



               <tbody><?php $sub_total=0;  $tax=6/100; // 6% tax

                                                   $amount=$sub_total*$tax;?>



                <tr>

                 <td style="border-top: 0px;"><strong>Hummus Mediterranean Grill</strong><br>11205 Jhon F.<br>Kennedy Dr Unit <br>108A<br>HAGERSTOWN, MD 21742<br>(240) 513-6020</td>



                      <td style="border-top: 0px;"></td>

                      <td style="border-top: 0px;  vertical-align: top;">{{date('F d, Y')}}<br>{{date('h:i a')}}

                      </td>



                </tr>



                <tr>



                     <td>

                     Ticket: {{$order->userDetail->last_name}}

                     </td>

                     <td></td>

                     <td style="text-align: right;"><strong>Order No.</strong> {{$order->order_no}}</td>

                </tr>

               <tr>

                   <td>

                   Product Name

                   </td>

                   <td>

                   Quantity

                   </td>

                   <td style="text-align: right">

                   Price

                   </td>

               </tr>

               @foreach($order->details as $key=>$value)


               <tr>
                   @if($value->product_type=='meal')
                       <td >
                           <strong>{{$value->product_name}}

                               @if($value->serial_number!=null)
                                   <small>(Sr. {{$value->serial_number}}) </small>
                               @endif

                               @if($value->name!=null)
                                   <small>{{"( ".$value->name." )"}}</small>
                               @endif

                           </strong><br>
                           @php ($rowss = json_decode($value->detail))

                           @php ($names='')
                           @foreach($rowss as $rowsss)
                               <span>
                                {{$rowsss->product_name.', '}}</span>


                           @endforeach

                           <br>
                           @if($value->note !='')
                               <strong>Special instructions: </strong>
                               {{$value->note}}
                           @endif
                       </td>
                   @endif

                 <td>



                 <strong>{{$value->product_name}}

                  @if($value->serial_number!=null)



                       <small  >(Sr. {{$value->serial_number}})</small>

                   @endif

                 </strong>





                 </td>

                 <td style="text-align: left">{{$value->qty}}</td>



                 <?php $sub_total+=$value->price; ?>

                 <td style="text-align: right">${{$value->price}}</td>



               </tr>



               @endforeach


<?php
                $tax=6/100; // 6% tax
                $amount_=round($order->total_amount*$tax,2);
                $total_price=$order->total_amount;
                $sub_total=$total_price-$amount_;
                ?>
                <tr>





                   <td >Sub Total <br>

                   Sale tax

                   </td>

                   <td></td>

                   <td style="text-align: right">${{$sub_total}}   <br>

                   ${{$amount_}}

                   </td>

                </tr>

                 <tr>

                   <td >Total</td>

                    <td></td>

                   <td style="text-align: right">${{$order->total_amount}} </td>

                 </tr>



               </tbody>

        </table>

      </div>

      <!-- /.col -->

    </div>





  </section>

  <!-- /.content -->

</div>

<!-- ./wrapper -->

</body>

</html>

