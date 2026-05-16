<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Hummus Mediterranean Grill</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{url('css/AdminLTE.min.css')}}">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->  <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>  <![endif]-->  <!-- Google Font -->
    <style>  .invoice {
            position: relative;
            background: #fff;
            border: 0px solid #f4f4f4;
            padding: 0px; /* margin: 10px 25px; */
        }

        .table {
            margin: 0px;
            font-size: 15px;
            margin-bottom: 0px;
        }

        .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
            padding: 6px;
            border-top: 1px solid #000;
        }  </style>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body onload="window.print();">
<div class="wrapper">  <!-- Main content -->
    <section class="invoice">    <!-- info row -->
        <div class="row invoice-infos">
            <div class="col-xs-12 table-responsive">
                <table class="table ">
                    <tr>
                        <td style="border-top: 0px;"><strong>Hummus Mediterranean Grill</strong></td>
                        <td style="border-top: 0px;text-align: right;">{{date('F d, Y')}}<br>{{date('h:i a')}}</td>
                    </tr>
                    <tr>
                        <td>
                            Ticket: {{$order->userDetail->first_name.' '.substr($order->userDetail->last_name, 0, 1)}}                    </td>
                        <td style="text-align: right;"><strong>Order No.</strong> {{$order->order_no}}</td>
                    </tr>
                </table>
            </div>
        </div>    <!-- /.row -->    <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <?php $sub_total = 0;  $tax = 6 / 100; // 6% tax
                    //                                                   $amount=$sub_total*$tax;
                    ?>
                    @foreach($order->details as $key=>$value)
                        <tr> @if($value->product_type=='meal')
                                <td><strong>{{$value->product_name}} @if($value->serial_number!=null)
                                            <small>(Sr. {{$value->serial_number}}
                                                ) {{$value->name? "( ".$value->name." )":''}}
                                            </small>
                                        @endif
                                    </strong><br> @php ($rowss = json_decode($value->detail)) @php ($names='')
                                    @foreach($rowss as $rowsss)
                                        <span style="padding-right: 9px;font-weight: 600;">
                                {{$rowsss->product_name.', '}}</span>
                                    @endforeach
                                    {{--{{rtrim($names, ', ')}}--}}
                                    <br> @if($value->note !='') <strong>Special
                                        instructions: </strong> {{$value->note}} @endif
                                </td> <?php $sub_total += $value->price; ?>
                                <td style="text-align: right">
                                    ${{$value->price}}</td> @endif
                            @if($value->product_type=='customized')
                                <td>
                                    <strong>{{$value->product_name}}
                                        @if($value->serial_number!=null)
                                            <small>(Sr. {{$value->serial_number}})
                                            </small>                      @endif
                                        @if($value->name!=null)
                                            <small>{{"( ".$value->name." )"}}</small>
                                        @endif
                                    </strong><br>
                                    @if($value->exclude_item)
                                        @php ($rows = json_decode($value->exclude_item))                                                          @php ($name='')                                                        @foreach($rows as $row)                                                          <?php $name .= "No " . $row . ', '; ?>                                                        @endforeach                                                         {{rtrim($name, ', ')}}                                                   @endif
                                    <br> @if($value->note !='')
                                        <strong>Special
                                        instructions: </strong>
                                        {{$value->note}}
                                    @endif
                                </td>
                                <td style="text-align: right">
                                    ${{$value->price}}</td>
                                <?php $sub_total += $value->price; ?>
                            @endif
                        </tr> @endforeach
                    </tbody>
                </table>
            </div>      <!-- /.col -->
        </div>
    </section>  <!-- /.content -->
</div><!-- ./wrapper -->
</body>
</html>
