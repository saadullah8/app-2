<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#e54c2a">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link type="image/x-icon" rel="shortcut icon" href="{{url('img/logo/logo-icon.png')}}"/>
    <title>{{env('APP_NAME')}} - @yield('title')</title>
    <meta name="description"
          content="{{env('APP_NAME')}} - Restaurant, We use only the best ingredients, it must be fresh in order to be used in our dishes">

    <!-- Bootstrap stylesheet -->
    <link href="{{url('assets/libs/bootstrap-4.0.0-dist/css/bootstrap.min.css')}}" rel="stylesheet">
    <!-- icofont -->
    <link href="{{url('assets/libs/icofont/css/icofont.css')}}" rel="stylesheet" type="text/css"/>
    <!-- crousel css -->
    <link href="{{url('assets/libs/owlcarousel2/assets/owl.carousel.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- mb.YTPlayer css -->
    <link href="{{url('assets/libs/mb.YTPlayer/css/jquery.mb.YTPlayer.min.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Theme Stylesheet -->
    <link href="{{url('assets/css/custom.css')}}" data-style="styles" rel="stylesheet">
    <link href="{{url('assets/css/style.css')}}" rel="stylesheet" type="text/css"/>
    <!-- Switch Color Style css -->
    <link href="{{url('assets/css/color/color-12.css')}}" data-style="styles" rel="stylesheet">
    <link href="{{url('plugins/jasny/jasny-bootstrap.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('plugins/toastr/toastr.min.css')}}">
    <script>var base_url = '{{url('/')}}';</script>
    <style>
        input[type='number'] {
            -moz-appearance: textfield;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        .scrollTop {
            position: fixed;
            left: 5%;
            bottom: 5%;
            background-color: #fe0000;
            padding: 20px;
            border-radius: 40px;
            opacity: 0;
            transition: all 0.4s ease-in-out 0s;
        }

        .scrollTop a {
            font-size: 18px;
            color: #fff;
        }
    </style>
    @yield('style')
</head>
<body>
<div class="wrapper">
    <!--[if lt IE 8]>
    <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a
        href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->

    <!-- Loader Start -->
@yield('loader')
<!-- Loader End -->

    <!--  Header Start  -->
@include('header')
<!-- Header End   -->


    <!-------------- model popup------------>

    <!-- Modal -->
@include('cart_model')
<!---------------- model end up--------------->

@yield('content')

<!-- Footer Start -->
@include('footer')
<!-- Footer End  -->
    <!-- Breakfast Menu End -->
    <div class="modal fade" id="model_closed_store" role="dialog">
        <div class="modal-dialog second-cart add-cart2">

            <div class="modal-content slider-model">
                <div class="main-bodypopup">
                    <div class="modal-header ">

                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="col-sm-12 col-xs-12   text-center">
                        <br>
                        <br>
                        <h4>Sorry you cannot order right now!</h4>

                        <div class="divider style-1 center">

                            <span class="hr-simple left"></span>

                            <i class="icofont icofont-ui-press hr-icon"></i>

                            <span class="hr-simple right"></span>

                        </div>

                        <div class="thanks-content">

                            <p> Online Ordering is Currently Closed.<br>
                                MON-SUN: 11.00 AM TO 10:00 PM<br>
                                LAST ONLINE ORDER: 9:45 PM<br>
                        </div>
                        <br>

                    </div>
                </div>

                <div class="modal-footer">
                    <a href="{{url('cart')}}"
                       class="btn   btn-danger">Schedule for later </a>

                    {{--<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>--}}

                </div>

            </div>

        </div>
    </div>
</div>
<!-- jquery -->
<script src="{{url('assets/libs/jquery/jquery.min.js')}}"></script>
<!-- jquery Validate -->
<script src="{{url('assets/libs/jquery-validation/jquery.validate.min.js')}}"></script>
<!-- popper js -->
<script src="{{url('assets/libs/popper/popper.min.js')}}"></script>
<!-- bootstrap js -->
<script src="{{url('assets/libs/bootstrap-4.0.0-dist/js/bootstrap.min.js')}}"></script>
<!-- owlcarousel js -->
<script src="{{url('assets/libs/owlcarousel2/owl.carousel.min.js')}}"></script>
<!--inview js code-->
<script src="{{url('assets/libs/jquery.inview/jquery.inview.min.js')}}"></script>
<!--CountTo js code-->
<script src="{{url('assets/libs/jquery.countTo/jquery.countTo.js')}}"></script>
<!-- mb.YTPlayer js code-->
<script src="{{url('assets/libs/mb.YTPlayer/jquery.mb.YTPlayer.min.js')}}"></script>
<!--internal js-->
<script src="{{url('assets/js/internal.js')}}"></script>
<script src="{{url('assets/js/custom.js')}}"></script>
<!-- Input Mask-->
<script src="{{url('plugins/jasny/jasny-bootstrap.min.js')}}"></script>

<script src="{{url('plugins/toastr/toastr.min.js')}}"></script>
@toastr_render
@yield('script')
@if(Session::has('success_cart'))
    <script>
        $("#myModal").modal('show');
        $(document).ready(function () {

            $("#closed_store").on("click", ".disable_btn", function (e) {
                e.preventDefault();

                $("#myModal").modal('hide');
            });

        });


    </script>
@endif

<script>

    $(document).ready(function() {
        /******************************
         BOTTOM SCROLL TOP BUTTON
         ******************************/

            // declare variable
        var scrollTop = $(".scrollTop");

        $(window).scroll(function() {
            // declare variable
            var topPos = $(this).scrollTop();
            // if user scrolls down - show scroll to top button
            if (topPos > 9565 || topPos > 5000 ) {
                $(scrollTop).css("opacity", "1");

            } else {
                $(scrollTop).css("opacity", "0");
            }

        }); // scroll END

        //Click event to scroll to top
        $(scrollTop).click(function() {
            $('html, body').animate({
                scrollTop: 0
            }, 800);
            return false;

        }); // click() scroll top EMD



    }); // ready() END
    $("#closed_store").click(function (e) {
        e.preventDefault();

        $("#myModal").modal('hide');
    })

    //       setInterval(function() {
    ////           alert('t')
    //           window.location.reload();
    //       }, 60000*1);

    $(document).ready(function () {
        //called when key is pressed in number
        $(':input[type="number"]').keypress(function (e) {
            //if the letter is not digit then display error and don't type anything
            if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                //display error message
//                   $("#errmsg").html("Digits Only").show().fadeOut("slow");
                return false;
            }
        });
    });
</script>

</body>
</html>
