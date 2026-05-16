<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Gyro Joint</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
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
        }

        table td {
            width: 300px;
        }

        table th {
            width: 300px;
        }

    </style>
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body>
<div class="wrapper">  <!-- Main content -->
    <section class="invoice">    <!-- info row -->

        <div class="row">

            <div class="col-xs-12 table-responsive">

                <table class="table table-striped"
                       style=" margin: 0px;font-size: 13.5px;font-family:'Calibri';border-spacing: 0;border-collapse: collapse;width: 100%;">


                    <tbody>

                    @if($order->is_pickup)
                        <tr>

                            <td style="border-top: 0px;"></td>
                            <td style="border-top: 0px;text-align: left">
                                <strong>SCHEDULED FOR LATER<br>
                                    Time: {{date('h:i a',strtotime($order->pickup_date))}}<br>
                                    Date: {{date('F d, Y',strtotime($order->pickup_date))}}</strong>
                            </td>

                        </tr>
                    @endif
                    <tr>
                        <td style="border-top: 0px;">
                            {{date('F d, Y',strtotime($order->created_at))}}<br/>
                            {{date('h:i a',strtotime($order->created_at))}}<br/>
                            Order No. <strong>{{$order->order_no}}</strong>
                        </td>
                        <td style="border-top: 0px; text-align: left; vertical-align: top;">
                            @if($order->remark!=null && $order->remark!='')
                                <strong>Pickup Instructions: </strong>{{$order->remark}}
                            @endif
                        </td>
                    </tr>


                    <tr>
                        <td style="text-align:left; padding: 0px;border-top: 1px solid #000;vertical-align: top;"
                            colspan="2">Ticket:
                            <strong> {{$order->userDetail? $order->userDetail->first_name.' '.substr($order->userDetail->last_name, 0, 1):""}}</strong>
                            <br>{{$order->userDetail? $order->userDetail->phone:""}}
                        </td>

                    </tr>
                    <tr>

                        <th style="text-align:left; padding: 0px; border-top: 1px solid #000; vertical-align: top;">
                            Quantity
                        </th>
                        <th style="text-align: right;padding: 0px;border-top: 1px solid #000;vertical-align: top;">
                            Price
                        </th>

                    </tr>

                    @foreach($order->details as $key=>$value)

                        <tr>
                            @if($value->product_type=='customized')
                                <td style="padding: 0px; border-top: 1px solid #000; vertical-align: top;">
                                    <strong>
                                        {{$value->qty}} {{$value->product_name}}
                                        @if($value->serial_number!=null)
                                            (Sr. {{$value->serial_number}})
                                        @endif
                                        @if($value->name!=null)
                                            {{"( ".$value->name." )"}}
                                        @endif
                                    </strong>
                                    <br>
                                    @if($value->note !='')

                                        <strong>Special instructions: </strong>
                                        {{$value->note}}

                                        <br>
                                    @endif

                                    <span class="word-space">
                                        @if($value->short_code)
                                            @php ($rows = json_decode($value->short_code))
                                            @php ($name='')
                                            @php ($j=1)
                                            @foreach($rows as $row)
                                                <span> {{"No ".$row.'. '}} </span>
                                                @if($j==2)

                                                    <br>
                                                    <?php $j = 1;?>
                                                @else
                                                    <?php $j += 1;?>
                                                @endif
                                            @endforeach

                                        @elseif($value->exclude_item)
                                            @php ($rows = json_decode($value->exclude_item))
                                            @php ($name='')
                                            @php ($j=1)
                                            @foreach($rows as $row)
                                                <span> {{"No ".$row.'. '}} </span>
                                                @if($j==2)

                                                    <br>
                                                    <?php $j = 1;?>
                                                @else
                                                    <?php $j += 1;?>
                                                @endif
                                            @endforeach

                                        @endif
                                    </span>


                                </td>

                                <td style="text-align: right;padding: 0px; border-top: 1px solid #000;vertical-align: top;">
                                    ${{$value->price}}</td>
                            @endif
                            @if($value->product_type=='fixed')
                                <td style="padding: 0px; border-top: 1px solid #000;vertical-align: top;">
                                    <strong>
                                        {{$value->qty}} {{$value->product_name}}
                                        @if($value->serial_number!=null)
                                            (Sr. {{$value->serial_number}})
                                        @endif
                                        @if($value->name!=null)
                                            {{"( ".$value->name." )"}}
                                        @endif
                                    </strong>


                                </td>


                                <td style="text-align: right;padding: 0px;  border-top: 1px solid #000; vertical-align: top;">
                                    ${{$value->price}}</td>
                            @endif
                        </tr>

                    @endforeach


                    <tr>




                        <td style="padding: 0px; border-top: 1px solid #000; vertical-align: top;">Sub Total <br>

                            Sale tax<br>
                            Discount
                        </td>


                        <td style="text-align: right;padding: 0px; border-top: 1px solid #000;vertical-align: top;">
                            ${{$order->subTotal}} <br>

                            ${{$order->taxAmount}} <br>
                            ${{$order->discountAmount}}

                        </td>

                    </tr>

                    <tr>

                        <th style="padding: 0px;text-align: left;border-top: 1px solid #000;vertical-align: top;">
                            Total
                        </th>


                        <th style="text-align: right; padding: 0px; border-top: 1px solid #000; vertical-align: top;">
                            ${{$order->total_amount}}
                        </th>

                    </tr>

                    <tr>

                        <th colspan="2" style="padding: 0px;text-align: center;
            border-top: 1px solid #000;
            vertical-align: top;">

                            @if($order->paymentType=="CashOfCreditCard")
                                <p>
                                    <strong>
                                        Online Payment
                                    </strong>
                                    Stripe id: <a href="{{$order->stripInvoiceURL}}" target="_blank"
                                                  class="link"> {{$order->stripPaymentId}}</a>
                                </p>
                            @else
                                <p>
                                    <strong>
                                        Pay at store
                                    </strong>
                                </p>
                            @endif
                        </th>


                    </tr>

                    </tbody>

                </table>

            </div>

            <!-- /.col -->

        </div>


    </section>  <!-- /.content -->
</div><!-- ./wrapper -->
</body>
</html>
