```php
<header>
    <!--Top Header Start -->
    <div class="top">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <ul class="list-inline float-left icon">
                        <li class="list-inline-item">
                            <a href="tel:+18145753580">
                                <i class="icofont icofont-phone"></i>
                                Hotline : 1814-575-3580
                            </a>
                        </li>
                    </ul>

                    <!-- Header Social Start -->
                    <ul class="list-inline float-right icon">

                        <li class="list-inline-item">
                            <a href="{{url('about')}}">About Us</a>
                        </li>

                        <li class="list-inline-item">
                            <a href="{{url('contact')}}">Contact Us</a>
                        </li>

                        <li class="list-inline-item">

                            <a href="#" data-toggle="modal" data-target="#myModal">
                                <i class="icofont icofont-cart-alt"></i> Cart

                                @if(Session::get('orders'))
                                    <span class="badge">
                                        <?php
                                        $data = Session::get('orders');
                                        echo count($data);
                                        ?>
                                    </span>
                                @endif
                            </a>

                        </li>

                        <li class="list-inline-item dropdown">

                            <a class="dropdown-toggle"
                               href="#"
                               role="button"
                               id="dropdownMenuLink"
                               data-toggle="dropdown"
                               aria-haspopup="true"
                               aria-expanded="false">

                                <i class="icofont icofont-ui-user"></i> My Account
                            </a>

                            <ul class="dropdown-menu dropdown-menu-right drophover"
                                aria-labelledby="dropdownMenuLink">

                                @if(!\Illuminate\Support\Facades\Auth::check())

                                    <li class="dropdown-item">
                                        <a href="{{url('login')}}">Login</a>
                                    </li>

                                    <li class="dropdown-item">
                                        <a href="{{url('register')}}">Register</a>
                                    </li>

                                @else

                                    @php
                                        $user = \Illuminate\Support\Facades\Auth::user();
                                        $role = optional($user->role)->role;
                                    @endphp

                                    @if($role == "Super Admin")

                                        <li class="dropdown-item">
                                            <a href="{{url('/admin')}}">Dashboard</a>
                                        </li>

                                    @elseif($role == "Admin")

                                        <li class="dropdown-item">
                                            <a href="{{url('orders/pending')}}">Dashboard</a>
                                        </li>

                                    @else

                                        <li class="dropdown-item">
                                            <a href="{{url('user/dashboard')}}">Dashboard</a>
                                        </li>

                                        <li class="dropdown-item">
                                            <a href="{{url('user/dashboard')}}">Order History</a>
                                        </li>

                                    @endif

                                    <li class="dropdown-item">
                                        <a href="{{ route('logout') }}"
                                           onclick="event.preventDefault();
                                           document.getElementById('logout-form').submit();">

                                            <i class="typcn typcn-power-outline"></i>
                                            Sign Out
                                        </a>

                                        <form id="logout-form"
                                              action="{{ route('logout') }}"
                                              method="POST"
                                              style="display: none;">

                                            @csrf
                                        </form>
                                    </li>

                                @endif

                            </ul>

                        </li>

                        <li class="list-inline-item">

                            <ul class="list-inline social">

                                <li class="list-inline-item">
                                    <a href="javascript:;" target="_blank">
                                        <i class="icofont icofont-social-facebook"></i>
                                    </a>
                                </li>

                                {{--<li class="list-inline-item">
                                    <a href="https://www.instagram.com/" target="_blank">
                                        <i class="icofont icofont-social-instagram"></i>
                                    </a>
                                </li>--}}

                            </ul>

                        </li>

                    </ul>
                    <!-- Header Social End -->
                </div>
            </div>
        </div>
    </div>
    <!--Top Header End -->

    <div class="container">
        <div class="row">

            <div class="col-md-3 col-sm-6 col-xs-12">

                <!-- Logo Start -->
                <div id="logo">
                    <a href="{{url('/')}}">
                        <img id="logo_img"
                             class="img-fluid"
                             src="{{url('img/logo/logo.png')}}"
                             alt="GJ" />
                    </a>
                </div>
                <!-- Logo End -->

            </div>

            <div class="col-md-9 col-sm-6 col-xs-12 paddleft">

                <!-- Main Menu Start -->
                <div id="menu">

                    <nav class="navbar navbar-expand-md">

                        <div class="navbar-header">
                            <span class="menutext d-block d-md-none">Menu</span>

                            <button data-target=".navbar-ex1-collapse"
                                    data-toggle="collapse"
                                    class="btn btn-navbar navbar-toggler"
                                    type="button">

                                <i class="icofont icofont-navigation-menu"></i>
                            </button>
                        </div>

                        <div class="collapse navbar-collapse navbar-ex1-collapse padd0">

                            <ul class="nav navbar-nav">

                                <li class="nav-item">
                                    <a href="{{url('/')}}">HOME</a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{url('/#menus')}}"
                                       class="tab_active_click_menu">
                                        Menu
                                    </a>
                                </li>

                                <li class="nav-item dropdown">

                                    <a href="#"
                                       class="dropdown-toggle"
                                       data-toggle="dropdown">
                                        Shawarma
                                    </a>

                                    <div class="dropdown-menu">
                                        <div class="dropdown-inner">

                                            <ul class="list-unstyled">

                                                <li>
                                                    <a href="{{url('/#Shawarma')}}"
                                                       class="tab_active_click_shawarma1">
                                                        Chicken Shawarma
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="{{url('/#Shawarma')}}"
                                                       class="tab_active_click_shawarma1">
                                                        Lamb Shawarma
                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="{{url('/#Shawarma')}}"
                                                       class="tab_active_click_shawarma1">
                                                        Combo Shawarma
                                                    </a>
                                                </li>

                                            </ul>

                                        </div>
                                    </div>

                                </li>

                                <li class="nav-item">
                                    <a href="{{url('/#RicePlatters')}}"
                                       class="tab_active_a">
                                        Rice Platters
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{url('/#Sandwiches')}}"
                                       class="tab_active_b">
                                        Sandwiches
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{url('/#Gyros')}}"
                                       class="tab_active_c">
                                        Gyros
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{url('/#Salad')}}"
                                       class="tab_active_d">
                                        Salad
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a href="{{url('/#Wings')}}"
                                       class="tab_active_f">
                                        Wings
                                    </a>
                                </li>

                            </ul>

                        </div>

                    </nav>

                </div>
                <!-- Main Menu End -->

            </div>

        </div>
    </div>
</header>
```
