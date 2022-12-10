<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('page_title')</title>
    <link href="{{asset('admin_assets/css/font-face.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_assets/vendor/font-awesome-4.7/css/font-awesome.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_assets/vendor/font-awesome-5/css/fontawesome-all.min.css')}}" rel="stylesheet" media="all">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" media="all">
    <link href="{{asset('admin_assets/vendor/mdi-font/css/material-design-iconic-font.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_assets/vendor/bootstrap-4.1/bootstrap.min.css')}}" rel="stylesheet" media="all">
    <link href="{{asset('admin_assets/css/theme.css')}}" rel="stylesheet" media="all">

</head>

<body>
<div class="page-wrapper">
        <!-- HEADER MOBILE-->
        <header class="header-mobile d-block d-lg-none">
            <div class="header-mobile__bar">
                <div class="container-fluid">
                    <div class="header-mobile-inner">
                        <a class="logo" href="{{url('admin/dashboard')}}">
                            <img src="{{asset('admin_assets/images/icon/logo.png')}}" alt="CoolAdmin" width="100px" />
                        </a>
                        <button class="hamburger hamburger--slider" type="button">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>
            </div>
            <nav class="navbar-mobile">
                <div class="container-fluid">
                    <ul class="navbar-mobile__list list-unstyled">
                    <li class="@yield('dashboard_select')">
                            <a href="{{url('admin/dashboard')}}">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>
                        <li class="@yield('order_select')">
                            <a href="{{url('admin/order')}}">
                                <i class="fas fa-shopping-basket"></i>Order</a>
                        </li>
                        <li class="@yield('category_select')">
                            <a href="{{url('admin/category')}}">
                                <i class="fas fa-list"></i>Category</a>
                        </li>

                        <li class="@yield('coupon_select')">
                            <a href="{{url('admin/coupon')}}">
                                <i class="fas fa-tag"></i>Coupon</a>
                        </li>

                        <li class="@yield('size_select')">
                            <a href="{{url('admin/size')}}">
                                <i class="fas fa-window-maximize"></i>Size</a>
                        </li>

                        <li class="@yield('brand_select')">
                            <a href="{{url('admin/brand')}}">
                            <i class="fa fa-product-hunt"></i>Brand</a>
                        </li>

                        <li class="@yield('color_select')">
                            <a href="{{url('admin/color')}}">
                            <i class="fas fa-paint-brush"></i>Color</a>
                        </li>

                        <li class="@yield('tax_select')">
                            <a href="{{url('admin/tax')}}">
                            <i class="fas fa-percent"></i>Tax</a>
                        </li>

                        <li class="@yield('product_select')">
                            <a href="{{url('admin/product')}}">
                            <i class="fa fa-product-hunt"></i>Product</a>
                        </li>

                        <li class="@yield('customer_select')">
                            <a href="{{url('admin/customer')}}">
                            <i class="fa fa-user"></i>Customer</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- END HEADER MOBILE-->

        <!-- MENU SIDEBAR-->
        <aside class="menu-sidebar d-none d-lg-block">
            <div class="logo">
                <a href="{{url('admin/dashboard')}}">
                    <img src="{{asset('admin_assets/images/icon/logo.png')}}" width="100px" />
                </a>
            </div>
            <div class="menu-sidebar__content js-scrollbar1">
                <nav class="navbar-sidebar">
                    <ul class="list-unstyled navbar__list">
                        <li class="@yield('dashboard_select')">
                            <a href="{{url('admin/dashboard')}}">
                                <i class="fas fa-tachometer-alt"></i>Dashboard</a>
                        </li>
                        <li class="@yield('order_select')">
                            <a href="{{url('admin/order')}}">
                                <i class="fas fa-shopping-basket"></i>Order</a>
                        </li>
                        <li class="@yield('product_review_select')">
                            <a href="{{url('admin/product_review')}}">
                            <i class="fas fa-star"></i>Product Review</a>
                        </li>
                        <li class="@yield('category_select')">
                            <a href="{{url('admin/category')}}">
                                <i class="fas fa-list"></i>Category</a>
                        </li>

                        <li class="@yield('coupon_select')">
                            <a href="{{url('admin/coupon')}}">
                                <i class="fas fa-tag"></i>Coupon</a>
                        </li>

                        <li class="@yield('size_select')">
                            <a href="{{url('admin/size')}}">
                                <i class="fas fa-window-maximize"></i>Size</a>
                        </li>

                        <li class="@yield('brand_select')">
                            <a href="{{url('admin/brand')}}">
                            <i class="fa fa-bold"></i>Brand</a>
                        </li>

                        <li class="@yield('color_select')">
                            <a href="{{url('admin/color')}}">
                            <i class="fas fa-paint-brush"></i>Color</a>
                        </li>

                        <li class="@yield('tax_select')">
                            <a href="{{url('admin/tax')}}">
                            <i class="fas fa-percent"></i>Tax</a>
                        </li>

                        <li class="@yield('product_select')">
                            <a href="{{url('admin/product')}}">
                            <i class="fa fa-product-hunt"></i>Product</a>
                        </li>

                        <li class="@yield('customer_select')">
                            <a href="{{url('admin/customer')}}">
                            <i class="fa fa-user"></i>Customer</a>
                        </li>

                        <li class="@yield('home_banner_select')">
                            <a href="{{url('admin/home_banner')}}">
                            <i class="fas fa-images"></i>Home Banner</a>
                        </li>

                        
                    </ul>
                </nav>
            </div>
        </aside>
        <!-- END MENU SIDEBAR-->

        <!-- PAGE CONTAINER-->
        <div class="page-container">
            <!-- HEADER DESKTOP-->
            <header class="header-desktop">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        <div class="header-wrap">
                            <form class="form-header" action="" method="POST">
                              
                            </form>
                            <div class="header-button">
                                <div class="account-wrap">
                                    <div class="account-item clearfix js-item-menu">
                                        <div class="content">
                                            <a class="js-acc-btn" href="#">Welcome Admin</a>
                                        </div>
                                        <div class="account-dropdown js-dropdown">
                                           
                                            <div class="account-dropdown__body">
                                                <div class="account-dropdown__item">
                                                    <a href="#">
                                                        <i class="zmdi zmdi-account"></i>Account</a>
                                                </div>
                                                
                                            </div>
                                            <div class="account-dropdown__footer">
                                                <a href="{{url('admin/logout')}}">
                                                    <i class="zmdi zmdi-power"></i>Logout</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </header>
            <!-- END HEADER DESKTOP-->

            <!-- MAIN CONTENT-->
            <div class="main-content">
                <div class="section__content section__content--p30">
                    <div class="container-fluid">
                        @section('container')
                        @show
                    </div>
                </div>
            </div>
        </div>
        <!-- END PAGE CONTAINER-->

    </div>


    <script src="{{asset('admin_assets/vendor/jquery-3.2.1.min.js')}}"></script>
    <script src="{{asset('admin_assets/vendor/bootstrap-4.1/popper.min.js')}}"></script>
    <script src="{{asset('admin_assets/vendor/bootstrap-4.1/bootstrap.min.js')}}"></script>
    <script src="{{asset('admin_assets/vendor/wow/wow.min.js')}}"></script>
    <script src="{{asset('admin_assets/js/main.js')}}"></script>
</body>
</html>