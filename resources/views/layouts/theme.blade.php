<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title> {{env('APP_NAME')}} | @yield('title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link type="image/x-icon" rel="shortcut icon" href="{{url('img/logo/logo-icon.png')}}" />
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{url('css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  @yield('style')
  <link rel="stylesheet" href="{{url('css/AdminLTE.min.css')}}">
  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="{{url('css/skins/_all-skins.min.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{url('plugins/iCheck/flat/blue.css')}}">

  <!-- jvectormap -->
  <link rel="stylesheet" href="{{url('plugins/jvectormap/jquery-jvectormap-1.2.2.css')}}">
  <!-- Date Picker -->
  <link rel="stylesheet" href="{{url('plugins/datepicker/datepicker3.css')}}">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="{{url('plugins/daterangepicker/daterangepicker-bs3.css')}}">
  <!-- bootstrap wysihtml5 - text editor -->
  <link rel="stylesheet" href="{{url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css')}}">
  <link href="{{url('plugins/jasny/jasny-bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <script>var base_url = '{{url('/')}}';</script>

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
  <style>
  .font-Size{
    font-size: 17px;
    letter-spacing: 5px;
  }
  .word-space{
           word-spacing: 20px;
          }
    .order_menu{
      border: 2px solid #fffefe;
      margin-right: 10px;
    }
    .view_btn{
      color: #f9fafc;
      background: #f39c12;
    }
  .ready_btn{
    color: #f9fafc;
    background: #00a65a;
  }
  .print_btn{
    color: #f9fafc;
    background: #3c8dbc;
  }
  .remove_btn{
    color: #f9fafc;
    background: #dd4b39;
  }
    .sms_btn{
      color: #f9fafc;
      background: #5bc0de;
    }
  </style>
</head>
<body class="hold-transition skin-blue fixed sidebar-mini">
<div class="wrapper">

  <header class="main-header">
    <a href="{{url('admin')}}" class="logo">
      <span class="logo-mini">GJ</span>
      <span class="logo-lg">{{env('APP_NAME')}}</span>
    </a>
    <nav class="navbar navbar-static-top">
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <div class="navbar-header">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
          <i class="fa fa-bars"></i>
        </button>
      </div>

      <!-- Collect the nav links, forms, and other content for toggling -->

      <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
        <ul class="nav navbar-nav">
          <li class=" order_menu" style="background: #f39c12"><a href="{{url('orders/pending')}}">New Orders </a></li>
          <li class=" order_menu" style="background: #00a65a;"><a href="{{url('orders/ready_to_pickup')}}">Ready Orders </a></li>
          <li class=" order_menu" style="background: #dd4b39;"><a href="{{url('orders/cancel')}}">Cancel Orders </a></li>
        </ul>
      </div>

      <div class="navbar-custom-menu">

        <ul class="nav navbar-nav">


          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="{{url('img/user2-160x160.jpg')}}" class="user-image" >
              <span class="hidden-xs">{{Auth::user()->first_name." ". Auth::user()->last_name}}</span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="{{url('img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">

                <p>
                  {{Auth::user()->first_name." ". Auth::user()->last_name}}
                </p>
              </li>

              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="{{url('change/password/')}}" class="btn btn-default btn-flat">Change Password</a>
                </div>
                <div class="pull-right">
                  <a href="{{url('logout')}}" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>

        </ul>
      </div>
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{url('img/user2-160x160.jpg')}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p>{{Auth::user()->first_name." ". Auth::user()->last_name}}</p>
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

    @include('side_bar')
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      @yield('page_header')

    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Main row -->
      @yield('content')
      <!-- /.row (main row) -->

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer">
    {{--<div class="pull-right hidden-xs">--}}
      {{--<b>Version</b> 2.3.3--}}
    {{--</div>--}}
    <strong>Copyright &copy; {{date('Y')}} <a href="{{url('/')}}">{{env('APP_NAME')}}</a>.</strong> All rights
    reserved.
  </footer>


  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->
<!-- jQuery 2.2.0 -->
<script src="{{url('plugins/jQuery/jQuery-2.2.0.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{url('js/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.6 -->
<script src="{{url('js/bootstrap.min.js')}}"></script>
<!-- Sparkline -->
<script src="{{url('plugins/sparkline/jquery.sparkline.min.js')}}"></script>
<!-- jvectormap -->
<script src="{{url('plugins/jvectormap/jquery-jvectormap-1.2.2.min.js')}}"></script>
<script src="{{url('plugins/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
<!-- jQuery Knob Chart -->
<script src="{{url('plugins/knob/jquery.knob.js')}}"></script>
<!-- daterangepicker -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.11.2/moment.min.js"></script>
<script src="{{url('plugins/daterangepicker/daterangepicker.js')}}"></script>
<!-- datepicker -->
<script src="{{url('plugins/datepicker/bootstrap-datepicker.js')}}"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{url('plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{url('plugins/slimScroll/jquery.slimscroll.min.js')}}"></script>
<!-- FastClick -->

<script src="{{url('plugins/fastclick/fastclick.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{url('js/app.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->


<script src="{{url('plugins/jasny/jasny-bootstrap.min.js')}}"></script>
<script src="{{url('plugins/toastr/toastr.min.js')}}"></script>
<script src="{{url('js/demo.js')}}"></script>
<script src="{{url('js/custom.js')}}"></script>
@toastr_render
@yield('script')
<script>
    $(document).on('click', '.print_page', function(e){

        e.preventDefault();
        var id = $(this).data('content');

        $.ajax({url: base_url + '/formPendingPrint/' + id}).done(function (response) {


            $('#models').empty().append(response);
              // $('#print_model').modal('show');

            var divToPrint=document.getElementById("printReceipt");
            newWin= window.open("");
            newWin.document.write(divToPrint.innerHTML);
            newWin.print();
            newWin.close();
          {{--location.href =  '{{URL::to("orders/processed")}}';--}}

        }).fail(function(error){
            alert('error');
        });



    });

</script>
</body>
</html>
