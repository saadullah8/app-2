<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">

    <title>Hummus Mediterranean Grill</title>

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
            /*width: 100%;*/
        }
        thead tr th:first-child,
        tbody tr td:first-child {
            /*width: 50%;*/
        }

        .table > tbody > tr > td,
        .table > tbody > tr > th,
        .table > tfoot > tr > td,
        .table > tfoot > tr > th,
        .table > thead > tr > td,
        .table > thead > tr > th {
            padding: 0px;
            padding-bottom: 1em;
            border-top: 1px solid #000;
            vertical-align: top;
        }
    </style>
</head>
<body onload="window.print();">
<div class="wrapper">  <!-- Main content -->
    <section class="invoice">    <!-- info row -->
        <!-- /.row -->    <!-- Table row -->
        <div class="row">
            <div class="col-xs-12 table-responsive">
                <table class="table table-striped">
                    <tbody>
                    <tr>
                        <td style="border-top: 0px;" colspan="3">
                            {{date('F d, Y')}}<br>{{date('h:i a')}}<br/>
                            Order No. <strong>{{$order->order_no}}</strong>
                        </td>
                        {{--<td style="border-top: 0px;"></td>--}}
                        {{--<td style="border-top: 0px;" ></td>--}}
                    </tr>
                    <tr>
                        <td colspan="3">Ticket:  <strong> {{$order->userDetail->first_name.' '.substr($order->userDetail->last_name, 0, 1)}}</strong>  <br>
                            Phone:   {{$order->userDetail->phone}} </td>
                        {{--<td></td>--}}
                        {{--<td style="text-align: center;"></td>--}}
                    </tr>
                    <?php $sub_total=0;   ?>




                    <tr>

                        <td>

                            <strong>Product Name</strong>

                        </td>

                        <td>

                            <strong>Qty</strong>

                        </td>

                        <td style="text-align: center">

                            <strong>Price</strong>

                        </td>

                    </tr>

                    @foreach($order->details as $key=>$value)


                        <tr>
                            @if($value->product_type=='meal')
                                <td style="width: 180px">
                                    <strong>{{$value->product_name}}

                                        @if($value->serial_number!=null)
                                            (Sr. {{$value->serial_number}})
                                        @endif

                                        @if($value->name!=null)
                                            {{"( ".$value->name." )"}}
                                        @endif

                                    </strong><br>
                                    @php ($rowss = json_decode($value->detail))

                                    @php ($names='')
                                    @foreach($rowss as $rowsss)
                                        <span >
                                {{$rowsss->product_name.', '}} &nbsp</span>


                                    @endforeach

                                    <br>
                                    @if($value->note !='')
                                        <strong>Special instructions: </strong>
                                        {{$value->note}}
                                    @endif
                                </td>
                            @endif

                            @if($value->product_type=='customized')

                                <td style="width: 180px">
                                    <strong>{{$value->product_name}}

                                        @if($value->serial_number!=null)
                                            (Sr. {{$value->serial_number}})
                                        @endif

                                        @if($value->name!=null)
                                            {{"( ".$value->name." )"}}
                                        @endif

                                    </strong><br>
                                    <span class="word-space">

                                        @if($value->exclude_item)
                                            @php ($rows = json_decode($value->exclude_item))
                                            @php ($name='')
                                            @foreach($rows as $row)
                                                <span>
                                {{"No ".$row.', '}}&nbsp</span>
                                                <?php $name  .="No ".$row.', '; ?>
                                            @endforeach
                                            {{--{{rtrim($name, ', ')}}--}}

                                        @endif
                                    </span>
                                    <br>
                                    @if($value->note !='')
                                        <strong>Special instructions: </strong>
                                        {{$value->note}}
                                    @endif
                                </td>
                            @endif

                            @if($value->product_type=='fixed')

                                <td style="width: 180px" >
                                    <strong>{{$value->product_name}}  </strong>
                                </td>
                            @endif

                            <td style="width: 20px">{{$value->qty}}</td>

                            <?php $sub_total+=$value->price; ?>

                            <td style="text-align: center;width: 20px;">${{$value->price}}</td>



                        </tr>


                    @endforeach


                    <?php
                    $tax=6/100; // 6% tax
                    // $amount_P=$order->total_amount*$tax;
                    $amount_=$sub_total*$tax;
                    //$total_price=$order->total_amount;
                    //$sub_total=$sub_total;
                    ?>
                    <tr>





                        <td colspan="2"><strong>Sub Total</strong> <br>

                            <strong> Sale tax</strong>

                        </td>

                        {{--<td></td>--}}

                        <td style="text-align: center;vertical-align: top;">${{$sub_total}}   <br>

                            ${{round($amount_,2)}}

                        </td>

                    </tr>

                    <tr>

                        <td colspan="2"><strong>Total</strong></td>

                        {{--<td></td>--}}

                        <td style="text-align: center;vertical-align: top;">${{number_format((float)round($amount_,2)+$sub_total, 2, '.', '') }} </td>

                    </tr>

                </table>
            </div>      <!-- /.col -->    </div>
    </section>  <!-- /.content --></div><!-- ./wrapper --></body>
</html>