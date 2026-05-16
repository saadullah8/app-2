<div class="modal fade" id="print_model">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <section class="invoicess" id="printReceipt">

                    <!-- Table row -->

                    <div class="row">

                        <div class="col-xs-12 table-responsive">

                            <table class="table table-striped" style=" margin: 0px;
            font-size: 13.5px;
            font-family:'Calibri';
                            border-spacing: 0;
                            border-collapse: collapse;
                            width: 100%;">


                                <tbody>

                                @if($order->is_pickup)
                                    <tr>

                                        <td style="border-top: 0px;">
                                        </td>
                                        <td style="border-top: 0px;text-align: left" colspan="2">
                                            <strong>SCHEDULED FOR LATER<br>
                                                Time: {{date('h:i a',strtotime($order->pickup_date))}}<br>
                                                Date: {{date('F d, Y',strtotime($order->pickup_date))}}</strong>
                                        </td>
                                        <td style="border-top: 0px;">

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
                                    <td style="border-top: 0px; text-align: left; vertical-align: top;" colspan="2">
                                        @if($order->remark!=null && $order->remark!='')
                                            <strong>Pickup Instructions: </strong>{{$order->remark}}
                                        @endif
                                    </td>
                                    <td style="border-top: 0px;"></td>
                                </tr>


                                <tr>
                                    <td style="text-align:left; padding: 0px;

            border-top: 1px solid #000;
            vertical-align: top;">Ticket:
                                        <strong> {{$order->userDetail->first_name.' '.substr($order->userDetail->last_name, 0, 1)}}</strong>
                                        <br>
                                        {{$order->userDetail->phone}} </td>
                                    <td style="text-align:left; padding: 0px;

            border-top: 1px solid #000;
            vertical-align: top;"></td>
                                    <td style="text-align:center; padding: 0px;

            border-top: 1px solid #000;
            vertical-align: top;"></td>
                                </tr>


                                <tr>

                                    <th style="text-align:left; padding: 0px;

            border-top: 1px solid #000;
            vertical-align: top;">

                                        Product Name

                                    </th>

                                    <th style="text-align: left; padding: 0px;

            border-top: 1px solid #000;
            vertical-align: top;">

                                        Quantity

                                    </th>

                                    <th style="text-align: right;padding: 0px;

            border-top: 1px solid #000;
            vertical-align: top;">

                                        Price

                                    </th>

                                </tr>

                                @foreach($order->details as $key=>$value)

                                    <tr>

                                        @if($value->product_type=='customized')
                                            <td style="padding: 0px;
            border-top: 1px solid #000;
            vertical-align: top;"><strong>{{$value->product_name}}
                                                    @if($value->serial_number!=null)
                                                        <small>(Sr. {{$value->serial_number}})
                                                        </small>
                                                    @endif
                                                    @if($value->name!=null)
                                                        <small>{{"( ".$value->name." )"}}</small>
                                                    @endif
                                                </strong>
                                                <br>
                                                @if($value->note !='')

                                                    <strong>Special instructions: </strong>
                                                    {{$value->note}}

                                                    <br>
                                                @endif

                                                @if($value->exclude_item)

                                                    @php ($rows = json_decode($value->exclude_item))
                                                    @php ($name='')
                                                    @foreach($rows as $row)
                                                        <?php $name .= $row . ', '; ?>
                                                    @endforeach
                                                    {{rtrim($name, ', ')}}
                                                @endif

                                            </td>
                                            <td style="text-align: left;padding: 0px;
            border-top: 1px solid #000;
            vertical-align: top;">{{$value->qty}}</td>



                                            <td style="text-align: right;padding: 0px;
            border-top: 1px solid #000;
            vertical-align: top;">${{$value->price}}</td>

                                        @endif
                                        @if($value->product_type=='fixed')
                                            <td style="padding: 0px;
            border-top: 1px solid #000;
            vertical-align: top;"><strong>{{$value->product_name}}
                                                    @if($value->serial_number!=null)
                                                        <small>(Sr. {{$value->serial_number}})
                                                        </small>
                                                    @endif
                                                    @if($value->name!=null)
                                                        <small>{{"( ".$value->name." )"}}</small>
                                                    @endif
                                                </strong>
                                            </td>
                                            <td style="text-align: left;padding: 0px;
            border-top: 1px solid #000;
            vertical-align: top;">{{$value->qty}}</td>
                                            <td style="text-align: right;padding: 0px;
            border-top: 1px solid #000;
            vertical-align: top;">${{$value->price}}</td>
                                        @endif
                                    </tr>
                                @endforeach
                                <tr>

                                    <td colspan="2" style="padding: 0px;
            border-top: 1px solid #000;
            vertical-align: top;">Sub Total <br>

                                        Sale tax<br>
                                        Discount

                                    </td>


                                    <td style="text-align: right;padding: 0px;border-top: 1px solid #000;vertical-align: top;">${{$order->subTotal}} <br>

                                        ${{$order->taxAmount}}
                                        <br>
                                        ${{$order->discountAmount}}
                                    </td>

                                </tr>

                                <tr>

                                    <th colspan="2" style="padding: 0px;text-align: left;
            border-top: 1px solid #000;
            vertical-align: top;">Total
                                    </th>


                                    <th style="text-align: right;padding: 0px;
                                    border-top: 1px solid #000;
                                    vertical-align: top;">
                                        ${{$order->total_amount}}
                                    </th>

                                </tr>
                                <tr>

                                    <th colspan="3" style="padding: 0px;text-align: center;
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


                </section>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
