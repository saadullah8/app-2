<footer>
 <div class="container {{ Illuminate\Support\Facades\Route::getFacadeRoot()->current()->uri()=='create/meal/{id}'? "menu-footeroption":''}} ">
        <div class="row inner">
            <div class="col-sm-6 col-md-6 col-lg-3">
                <!-- Footer Widget Start -->
                <h5>Contact Us</h5>
                <ul class="list-unstyled contact">
                    <li><i class="icofont icofont-social-google-map"></i>11205 John F Kennedy Dr, Hagerstown, MD 21742</li>
                    <li><i class="icofont icofont-phone"></i> 240-513-6020</li>
                    <li><a href="javascript:;"><i class="icofont icofont-ui-message"></i>info@hummusgrill.net</a></li>
                </ul>
                <!-- Footer Widget End -->
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <!-- Footer Widget Start -->
                <h5>Information</h5>
                <p class="text-justify">Find out why our customers rave about us. We have been a part of the Jhonstown community since 2022. We use only the best ingredients, it must be fresh in order to be used in our dishes.</p>
                {{--<ul class="list-unstyled">--}}
                    {{--<li><a href="#">About us</a></li>--}}
                    {{--<li><a href="#">Contact us</a></li>--}}
                    {{--<li><a href="#">Terms & Conditions</a></li>--}}
                    {{--<li><a href="#">Sitemap</a></li>--}}
                {{--</ul>--}}
                <!-- Footer Widget End -->
            </div>
            <div class="w-100 d-none d-xs-block"></div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <!-- Footer Widget Start -->
                <h5>Open Hours</h5>
                <ul class="list-unstyled">
                    <li>Monday - Friday   :11.00 AM to 09.00 PM</li>
                    <li>Saturday - Sunday :11.00 AM to 09.00 PM</li>

                </ul>
                <!-- Footer Widget End -->
            </div>
            <div class="col-sm-6 col-md-6 col-lg-3">
                <!-- Footer Widget Start -->
                <h5>Instagram</h5>
                <ul class="list-unstyled insta">
                    <li><a href="javascript:;"><img src="{{url('assets/images/instagram/1.jpg')}}" alt="image" /></a></li>
                    <li><a href="javascript:;"><img src="{{url('assets/images/instagram/2.jpg')}}" alt="image" /></a></li>
                    <li><a href="javascript:;"><img src="{{url('assets/images/instagram/3.jpg')}}" alt="image" /></a></li>
                    <li><a href="javascript:;"><img src="{{url('assets/images/instagram/4.jpg')}}" alt="image" /></a></li>
                    <li><a href="javascript:;"><img src="{{url('assets/images/instagram/5.jpg')}}" alt="image" /></a></li>
                    <li><a href="javascript:;"><img src="{{url('assets/images/instagram/6.jpg')}}" alt="image" /></a></li>
                </ul>
                <!-- Footer Widget End -->
            </div>
        </div>

    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row powered">
                <!--  Copyright Start -->
                <div class="col-md-3 col-sm-6 order-md-1">
                    <img src="{{url('assets/images/logo/logo-white.png')}}" class="img-fluid" title="logo" alt="logo">
                </div>
                <div class="col-md-3 col-sm-6 text-right order-md-3">
                    <!--  Footer Social Start -->
                    <ul class="list-inline social">
                        <li class="list-inline-item"><a href="https://www.facebook.com/hummusmd/" target="_blank"><i class="icofont icofont-social-facebook"></i></a></li>
                        <!--<li class="list-inline-item"><a href="https://twitter.com/" target="_blank"><i class="icofont icofont-social-twitter"></i></a></li>-->
                        <!--<li class="list-inline-item"><a href="https://plus.google.com/" target="_blank"><i class="icofont icofont-social-google-plus"></i></a></li>-->
                        <!--<li class="list-inline-item"><a href="https://in.pinterest.com/" target="_blank"><i class="icofont icofont-social-pinterest"></i></a></li>-->
                        <li class="list-inline-item"><a href="https://www.instagram.com/" target="_blank"><i class="icofont icofont-social-instagram"></i></a></li>
                        <!--<li class="list-inline-item"><a href="https://www.youtube.com/" target="_blank"><i class="icofont icofont-social-youtube-play"></i></a></li>-->
                    </ul>
                    <!--  Footer Social End -->
                </div>
                <div class="col-md-6 col-sm-12 text-center order-md-2">
                    <p>Copyright © <span>Hummus Grill</span> {{date('Y')}}. All Rights Reserved.</p>
                </div>
                <!--  Copyright End -->
            </div>
        </div>
    </div>
</footer>


