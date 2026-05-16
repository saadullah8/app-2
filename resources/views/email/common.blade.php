<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Gyro Joint</title>

    <style>
        body{
            margin: 0px;
            padding: 0px;
        }

        .table {
            margin: 0px;
            font-size: 14px;
            font-family: "Calibri";
            border-spacing: 0;
            border-collapse: collapse;
            width: 100%;
        }
        thead tr th:first-child,
        tbody tr td:first-child {
            width: 15%;
        }

        .table > tbody > tr > td,
        .table > tbody > tr > th,
        .table > tfoot > tr > td,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > thead > tr > th {
            padding: 0px;

            border-top: 1px solid #000;
            vertical-align: top;
        }
    </style>
</head>
<body >
<div class="wrapper">  <!-- Main content -->
    <section class="invoice">    <!-- info row -->
        <!-- /.row -->    <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <tbody>
                    @if($order->is_pickup)
                        <tr>

                            <td style="border-top: 0px;">
                            </td>
                            <td style="border-top: 0px;text-align: left" colspan="2">
                                <strong>SCHEDULED FOR LATER<br>
                                    Time:  {{date('h:i a',strtotime($order->pickup_date))}}<br>
                                    Date:  {{date('F d, Y',strtotime($order->pickup_date))}}</strong>
                            </td>
                            <td style="border-top: 0px;" >

                            </td>
                        </tr>
                    @endif
                    <tr>
                        <td style="border-top: 0px;">
                            {{date('F d, Y',strtotime($order->created_at))}}
                            <br>{{date('h:i a',strtotime($order->created_at))}}
                            <br/>
                            Order No. <strong>{{$order->order_no}}</strong>
                        </td>
                        <td style="border-top: 0px; text-align: left" colspan="2">
                            @if($order->remark!=null && $order->remark!='')
                                <strong>Pickup Instructions: </strong>{{$order->remark}}
                            @endif
                        </td>
                        <td style="border-top: 0px;" ></td>
                    </tr>


                    <tr>
                        <td >Ticket:  <strong> {{$order->userDetail->first_name.' '.substr($order->userDetail->last_name, 0, 1)}}</strong> <br>
                            {{$order->userDetail->phone}} </td>
                        <td></td>
                        <td style="text-align: center;"></td>
                    </tr>

                    <tr>

                        <td style="padding-bottom: 1em;">

                            <strong>Product Name</strong>

                        </td>

                        <td style="padding-bottom: 1em;">

                            <strong>Qty</strong>

                        </td>

                        <td style="text-align: center;padding-bottom: 1em;">

                            <strong>Price</strong>

                        </td>

                    </tr>

                    @foreach($order->details as $key=>$value)


                        <tr>

                            @if($value->product_type=='customized')

                                <td style="padding-bottom:0.5em;">
                                    <strong>{{$value->product_name}}

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
                                            <!---->
                                                <span>● {{$row.''}}&nbsp</span>
                                                @if($j==2)

                                                    <br>
                                                    <?php $j =1;?>
                                                @else
                                                    <?php $j +=1;?>
                                                @endif
                                            @endforeach

                                        @elseif($value->exclude_item)
                                            @php ($rows = json_decode($value->exclude_item))
                                            @php ($name='')
                                            @php ($j=1)
                                            @foreach($rows as $row)
                                                <span>● {{$row.''}}&nbsp</span>
                                                @if($j==2)

                                                    <br>
                                                    <?php $j =1;?>
                                                @else
                                                    <?php $j +=1;?>
                                                @endif
                                            @endforeach

                                        @endif
                                    </span>


                                </td>
                            @endif

                            @if($value->product_type=='fixed')

                                <td  style="padding-bottom: 0.5em;" >
                                    <strong>{{$value->product_name}}  </strong>
                                </td>
                            @endif

                            <td style="vertical-align: top;padding-bottom: 0.5em;">{{$value->qty}}</td>


                            <td style="text-align: center;vertical-align: top;padding-bottom: 0.5em;">${{$value->price}}</td>



                        </tr>


                    @endforeach

                    <tr>





                        <td><strong>Sub Total</strong> <br>

                            <strong> Sale tax</strong><br>
                            <strong> Discount</strong>

                        </td>

                        <td></td>

                        <td style="text-align: center;vertical-align: top;">${{$order->subTotal}}   <br>

                            ${{$order->taxAmount}}  <br>
                            ${{$order->discountAmount}}

                        </td>

                    </tr>

                    <tr>

                        <td ><strong>Total</strong></td>

                        <td></td>

                        <td style="text-align: center;vertical-align: top;">${{$order->total_amount}} </td>

                    </tr>

                </table>
            </div>      <!-- /.col -->    </div>
    </section>  <!-- /.content --></div><!-- ./wrapper --></body>
</html>
