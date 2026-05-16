<div class="modal fade" id="print_model">

<div class="modal-dialog">

 <div class="modal-content">





   <div class="modal-body">



      <!-- Main content -->

       <section class="invoice" id="printReceipt">

         <!-- info row -->

         <div class="row invoice-infos">

           <div class="col-xs-12 table-responsive">

              <table class="table " style="width: 100%; vertical-align:top !important; font-size:13px"  >



                    <tr style="vertical-align:top !important;">

                      <td style="border-top: 0px;"><strong>Gyro Joint</strong><br>11205 Jhon F.

                      <br>Kennedy Dr Unit <br>108A<br>HAGERSTOWN, MD 21742<br>(240) 513-6020</td>



                           <td style="border-top: 0px; margin-top:0px; text-align: right;">{{date('F d, Y')}}<br>{{date('h:i a')}}

                           </td>

                    </tr>





                </table>

                <hr style="margin-top: 5px;     margin-bottom: 5px;     border: 0;     border-top: 1px solid #eee;">

                 <table class="table " style="width: 100%; vertical-align:top !important;  font-size:13px"  >

                        <tr >



                           <td>

                           Ticket: {{$order->userDetail->first_name.' '.substr($order->userDetail->last_name, 0, 1)}}

                           </td>

                           <td style="border-top: 0px; margin-top:0px; text-align: right;"><strong>Order No.</strong> {{$order->order_no}}</td>

                       </tr>

                </table>

                <hr style="margin-top: 5px;     margin-bottom: 5px;     border: 0;     border-top: 1px solid #eee;">

           </div>



         </div>

         <!-- /.row -->



         <!-- Table row -->

         <div class="row">

           <div class="col-xs-12 table-responsive">

             <table class="table table-striped"  style="width: 100%; vertical-align:top! important;  font-size:13px;

             border-spacing: 0; border-collapse: collapse;

             ">



                    <tbody>

                    <tr style="text-align: left;">

                        <th style="border-bottom: 1px solid #eee; padding-bottom: 5px;">

                        Product Name

                        </th>

                        <th style="text-align: center;border-bottom: 1px solid #eee;padding-bottom: 5px;">

                        Quantity

                        </th>

                    <th style="text-align: right; border-bottom: 1px solid #eee;padding-bottom: 5px;">

                    Price

                    </th>

                    </tr>

                    @foreach($order->details as $key=>$value)

                    <tr>

                      <td style="padding-top: 5px; ">



                      {{$value->product_name}}

                       @if($value->serial_number!=null)



                            <small  >(Sr. {{$value->serial_number}})</small>

                        @endif







                      </td>

                      <td style="text-align:center; padding-top: 5px;">{{$value->qty}}</td>

                      <?php $sub_total+=$value->price; ?>

                      <td style="text-align:right; padding-top: 5px;">${{$value->price}}</td>

                    </tr>



                    @endforeach

                    </tbody>

             </table>

             <hr style="margin-top: 6px;     margin-bottom: 6px;     border: 0;     border-top: 1px solid #eee;">

             <table  style="width: 100%; vertical-align:top! important;  font-size:13px;

                                  border-spacing: 0; border-collapse: collapse;

                                  ">


              <tr>





                                     <td >Sub Total</td>

                                     <td></td>

                                     <td style="text-align: right;">${{$order->subTotal}}

                                     </td>

                                  </tr>

                                  <tr>

                                   <td>Sale tax</td>

                                   <td></td>

                                    <td style="text-align: right;">${{$order->taxAmount}}</td>

                                  </tr>
                 <tr>

                     <td>Discount</td>

                     <td></td>

                     <td style="text-align: right;">${{$order->discountAmount}}</td>

                 </tr>

             </table>

              <hr style="margin-top: 6px;     margin-bottom: 6px;     border: 0;     border-top: 1px solid #eee;">

              <table  style="width: 100%; vertical-align:top! important;  font-size:13px;

                                                              border-spacing: 0; border-collapse: collapse;

                                                              ">

              <tr>

                                      <th style="text-align: left">Total</th>



                                      <th style="text-align: right" >${{$order->total_amount}}</th>

                                    </tr>



              </table>

           </div>

           <!-- /.col -->

         </div>

       </section>

       <!-- /.content -->



   </div>

 </div>

 <!-- /.modal-content -->

</div>

<!-- /.modal-dialog -->

</div>
