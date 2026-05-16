<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Hummas Mediterranean Grill</title>

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
  font-size: 12px;
  margin-bottom:0px;
  }
  .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th{
  padding: 6px;
  border-top: 1px solid #000;
  }
  </style>
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body onload="window.prints();">
<div class="wrapper">
  <!-- Main content -->
  <section class="invoice">

    <!-- info row -->
    <div class="row invoice-infos">
      <div class="col-xs-12 table-responsive">
         <table class="table ">
              <tr>
                 <th style="border-top: 0px;">Hummas Mediterranean Grill</th>
                 <th  style="border-top: 0px;"></th>
              </tr>
               <tr>
                 <td>11205 Jhon F.<br>Kennedy Dr Unit<br>108A<br>HAGERSTOWN, MD 21742<br>(240) 513-6020</td>
                 <td>{{date('F d, Y')}}<br>{{date('h:i a')}}</td>
              </tr>

           </table>
      </div>

    </div>
    <!-- /.row -->

    <!-- Table row -->
    <div class="row">
      <div class="col-xs-12 table-responsive">
        <table class="table table-striped">

               <tbody>
               <tr>

                 <td><strong>Chicken Rice Bowl</strong><br>
                 Greens Bowl, Rice Bowl, Chicken Kabob, Couscous, Tabboulch, Ezme, Crumbled Feta,
                              Diced Tomatoes, Garlic Sauce, Schug Sauce, Mediterranean Dressing, Poppy
                 </td>
                 <td>$64.50</td>
               </tr>
                <tr>
                   <td >Sub Total</td>
                   <td>$7.99</td>
                </tr>

                 <tr>
                   <td >Sale tax</td>
                   <td>$0.48</td>
                 </tr>
                 <tr>
                   <th >Total</th>
                   <th>$7.99</th>
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
