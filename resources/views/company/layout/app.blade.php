<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(app()->getLocale()=="en")
    <title>{{ Setting::get('site_title_en','AilBaz') }}</title>
    @else
    <title>{{ Setting::get('site_title','الباز') }}</title>
    @endif
    <meta name="description" content="">
    <meta name="author" content="">

    <link style="float: right" rel="shortcut icon" type="image/png" href="{{ url('/').Setting::get('site_icon') }}"/>

    <link href="{{asset('asset/css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('asset/font-awesome/css/font-awesome.min.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">
    <link href="{{asset('asset/css/style.css')}}" rel="stylesheet">

</head>


<body >


    <div id="wrapper">
        <div class="overlay" id="overlayer" data-toggle="offcanvas"></div>
        @if(app()->getLocale()=='en')
        <nav  class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation">
            <ul class="nav sidebar-nav">
                <li>
                </li>
                <li class="full-white">
                    <a href="{{ url('/login') }}">@lang('user.sign_up_to_ride')</a>
                </li>
                <li class="white-border">
                    <a href="{{ url('/provider/login') }}">@lang('user.become_a_driver')</a>
                </li>
                <li>
                    <a href="{{ url('/login') }}">@lang('user.ride')</a>
                </li>
                <li>
                    <a href="{{ url('/provider/login') }}">@lang('user.drive')</a>
                </li>
{{--                <li>--}}
{{--                    <a href="">@lang('user.help')</a>--}}
{{--                </li>--}}
                <li>
                    <a href="privacy">@lang('user.pp')</a>
                </li>
                <li>
                    <a href="condition">@lang('user.tac')</a>
                </li>
                <li>
                    <a href="{{ Setting::get('store_link_ios','#') }}"><img src="{{ asset('/asset/img/appstore-white.png') }}"></a>
                </li>
                <li>
                    <a href="{{ Setting::get('store_link_android','#') }}"><img src="{{ asset('/asset/img/playstore-white.png') }}"></a>
                </li>
                <li>
                    <a style="font-size: 20px" href="ar">العربية</a>
                </li>
            </ul>
        </nav>
        @else
            <nav style="left:100%" class="navbar navbar-inverse navbar-fixed-top" id="sidebar-wrapper" role="navigation" >
                <ul class="nav sidebar-nav" >
                    <li>
                    </li>
                    <li class="full-white">
                        <a style="text-align: end;" href="{{ url('/login') }}">@lang('user.sign_up_to_ride')</a>
                    </li>
                    <li class="white-border">
                        <a style="text-align: end;" href="{{ url('/provider/login') }}">@lang('user.become_a_driver')</a>
                    </li>
                    <li>
                        <a style="text-align: end;" href="{{ url('/login') }}">@lang('user.ride')</a>
                    </li>
                    <li>
                        <a style="text-align: end;" href="{{ url('/provider/login') }}">@lang('user.drive')</a>
                    </li>
{{--                    <li>--}}
{{--                        <a style="text-align: end;" href="#">@lang('user.help')</a>--}}
{{--                    </li>--}}
                    <li>
                        <a style="text-align: end;" href="privacy">@lang('user.pp')</a>
                    </li>
                    <li>
                        <a style="text-align: end;" href="condition">@lang('user.tac')</a>
                    </li>
                    <li>
                        <a style="text-align: end;" href="{{ Setting::get('store_link_ios','#') }}"><img src="{{ asset('/asset/img/appstore-white.png') }}"></a>
                    </li>
                    <li>
                        <a style="text-align: end;" href="{{ Setting::get('store_link_android','#') }}"><img src="{{ asset('/asset/img/playstore-white.png') }}"></a>
                    </li>
                    <li>
                        <a style="text-align: end;font-size: 20px" href="en">ENGLISH</a>
                    </li>
                </ul>
            </nav>
        @endif

        <div id="page-content-wrapper">



            <header>
                <nav class="navbar navbar-fixed-top" style="
/*
    border-width: 0px;
    -webkit-box-shadow: 0px 0px;
    box-shadow: 0px 0px;
    background-color: rgba(0,0,0,0.0);
    background-image: -webkit-gradient(linear, 50.00% 0.00%, 50.00% 100.00%, color-stop( 0% , rgba(0,0,0,0.00)),color-stop( 100% , rgba(0,0,0,0.00)));
    background-image: -webkit-linear-gradient(270deg,rgba(0,0,0,0.00) 0%,rgba(0,0,0,0.00) 100%);
    background-image: linear-gradient(180deg,rgba(0,0,0,0.00) 0%,rgba(0,0,0,0.00) 100%);
*/
/*background:transparent;*/
background-color: rgba(255,255,255,0.23);

">
                    <div class="container-fluid">

                     @if(app()->getLocale()=="en")
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                                <button type="button" class="hamburger is-closed" data-toggle="offcanvas">
                                    <span class="hamb-top"></span>
                                    <span class="hamb-middle"></span>
                                    <span class="hamb-bottom"></span>
                                </button>


                            <a class="navbar-brand" style="background: none" href="{{url('/')}}"><img src="{{url('/').Setting::get('site_logo', asset('logo-black.png'))}}" style="height: 43px;border-radius: 50%;border: white solid 2px;"></a>
                        </div>

                    @else
                        <div class="navbar-header" style=" float: right;">

                            <button style=" margin-left: 95px;" type="button" class="hamburger is-closed" data-toggle="offcanvas">
                                <span class="hamb-top"></span>
                                <span class="hamb-middle"></span>
                                <span class="hamb-bottom"></span>
                            </button>
                            <a class="navbar-brand" style="background: none" href="{{url('/')}}"><img src="{{url('/').Setting::get('site_logo', asset('logo-black.png'))}}" style="height: 43px;border-radius: 50%;border: white solid 2px;    margin-left: -50px;"></a>

                            <button style="margin-right: 5px" type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                        </div>

                    @endif
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            @if(app()->getLocale()=='en')
                            <ul class="nav navbar-nav navbar-left">
                                <li class="dropdown">
                                    <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">@lang('user.rigister') <span class="caret"></span></a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{url('register')}}">
                                                <img src="{{ asset('asset/img/5.png') }}" style="width: 2em;">
                                                @lang('user.Sign_up_to_Ride')
                                            </a>
                                        </li>
                                        <li role="separator" class="divider"></li>
                                        <li>
                                            <a href="{{url('/provider/register')}}">
                                                <img src="{{ asset('asset/img/6.png') }}" style="width: 2em;">
                                                @lang('user.Sign_up_to_drive')
                                            </a>
                                        </li>
                                    </ul>
                                </li>

                                <li class="nav-item">
                                    <a href="ar"  class="nav-link">AR</a>
                                </li>



                                <li class="dropdown mega-dropdown">
                                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">@lang('user.ser') <span class="caret"></span></a>

                                    <ul class="dropdown-menu mega-dropdown-menu row">
                                        <li class="col-sm-12" style="margin-bottom: 10px;display: flex;">

                                            @foreach(App\ServiceType::all() as $services)

                                                <div class="col-sm-12" style="display: {{ ($services->status)?'block':'none' }}">

                                                    <div style="flex: 1;">
                                                        <a href="{{url('/').'/services/'.$services->id}}"><img src="{{asset($services->image)}}" style="width: 150px;height: 120px;"  class="img-responsive" alt="product 1"></a>
                                                        <h4 >{{$services->name}} </h4>
                                                        <button class="btn btn-primary" type="button">{{$services->price}} €</button>
                                                        <a href="{{url('/').'/services/'.$services->id}}" class="btn btn-default" >@lang('user.s_m_d')</a>
                                                    </div>
                                                </div>

                                            @endforeach
                                        </li>
                                </ul>



                                <style>
                                    @import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700);
                                    body {
                                        font-family: 'Open Sans', 'sans-serif';
                                        background: #f0f0f0;
                                        background: url(https://pcbx.us/bfjb.jpg);
                                    }

                                    h1,
                                    .h1 {
                                        font-size: 36px;
                                        text-align: center;
                                        font-size: 5em;
                                        color: #404041;
                                    }

                                    .navbar-nav>li>.dropdown-menu {
                                        margin-top: 20px;
                                        border-top-left-radius: 4px;
                                        border-top-right-radius: 4px;
                                    }

                                    .navbar-default .navbar-nav>li>a {
                                        width: 200px;
                                        font-weight: bold;
                                    }

                                    .mega-dropdown {
                                        position: static !important;
                                        /*width: 100%;*/
                                    }

                                    .mega-dropdown-menu {
                                        padding: 20px 0px;
                                        width: 100%;
                                        box-shadow: none;
                                        -webkit-box-shadow: none;
                                    }

                                    .mega-dropdown-menu:before {
                                        content: "";
                                        border-bottom: 15px solid #fff;
                                        border-right: 17px solid transparent;
                                        border-left: 17px solid transparent;
                                        position: absolute;
                                        top: -15px;
                                        left: 285px;
                                        z-index: 10;
                                    }

                                    .mega-dropdown-menu:after {
                                        content: "";
                                        border-bottom: 17px solid #ccc;
                                        border-right: 19px solid transparent;
                                        border-left: 19px solid transparent;
                                        position: absolute;
                                        top: -17px;
                                        left: 283px;
                                        z-index: 8;
                                    }

                                    .mega-dropdown-menu > li > ul {
                                        padding: 0;
                                        margin: 0;
                                    }

                                    .mega-dropdown-menu > li > ul > li {
                                        list-style: none;
                                    }

                                    .mega-dropdown-menu > li > ul > li > a {
                                        display: block;
                                        padding: 3px 20px;
                                        clear: both;
                                        font-weight: normal;
                                        line-height: 1.428571429;
                                        color: #999;
                                        white-space: normal;
                                    }

                                    .mega-dropdown-menu > li ul > li > a:hover,
                                    .mega-dropdown-menu > li ul > li > a:focus {
                                        text-decoration: none;
                                        color: #444;
                                        background-color: #f5f5f5;
                                    }

                                    .mega-dropdown-menu .dropdown-header {
                                        color: #428bca;
                                        font-size: 18px;
                                        font-weight: bold;
                                    }

                                    .mega-dropdown-menu form {
                                        margin: 3px 20px;
                                    }

                                    .mega-dropdown-menu .form-group {
                                        margin-bottom: 3px;
                                    }

                                </style>
                                <script>
                                    jQuery(document).on('click', '.mega-dropdown', function(e) {
                                        e.stopPropagation()
                                    })
                                </script>
                            </ul>
                                @else
                                <ul class="nav navbar-nav navbar-right">
                                    <li class="dropdown">
                                        <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">@lang('user.rigister') <span class="caret"></span></a>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a href="{{url('register')}}">
                                                    <img src="{{ asset('asset/img/5.png') }}" style="width: 2em;">
                                                  تسجيل عميل جديد
                                                </a>
                                            </li>
                                            <li role="separator" class="divider"></li>
                                            <li>
                                                <a href="{{url('/provider/register')}}">
                                                    <img src="{{ asset('asset/img/6.png') }}" style="width: 2em;">
                                                تسجيل سائق جديد
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="nav-item">
                                        <a href="en"  class="nav-link">EN</a>
                                    </li>


                                    <li class="dropdown mega-dropdown">
                                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">@lang('user.ser') <span class="caret"></span></a>

                                        <ul class="dropdown-menu mega-dropdown-menu row">
                                            <li class="col-sm-12" style="margin-bottom: 10px;display: flex;">

                                                @foreach(App\ServiceType::all() as $services)

                                                    <div class="col-sm-12" style="display: {{ ($services->status)?'block':'none' }}">

                                                        <div style="flex: 1;">
                                                            <a href="{{url('/').'/services/'.$services->id}}"><img src="{{asset($services->image)}}" style="width: 150px;height: 120px;"  class="img-responsive" alt="product 1"></a>
                                                            <h4 >{{$services->name}} </h4>
                                                            <button class="btn btn-primary" type="button">{{$services->price}} €</button>
                                                            <a href="{{url('/').'/services/'.$services->id}}" class="btn btn-default" >@lang('user.s_m_d')</a>
                                                        </div>
                                                    </div>

                                                @endforeach
                                            </li>
                                        </ul>



                                        <style>
                                            @import url(http://fonts.googleapis.com/css?family=Open+Sans:400,700);
                                            body {
                                                font-family: 'Open Sans', 'sans-serif';
                                                background: #f0f0f0;
                                                background: url(https://pcbx.us/bfjb.jpg);
                                            }

                                            h1,
                                            .h1 {
                                                font-size: 36px;
                                                text-align: center;
                                                font-size: 5em;
                                                color: #404041;
                                            }

                                            .navbar-nav>li>.dropdown-menu {
                                                margin-top: 20px;
                                                border-top-left-radius: 4px;
                                                border-top-right-radius: 4px;
                                            }

                                            .navbar-default .navbar-nav>li>a {
                                                width: 200px;
                                                font-weight: bold;
                                            }

                                            .mega-dropdown {
                                                position: static !important;
                                                /*width: 100%;*/
                                            }

                                            .mega-dropdown-menu {
                                                padding: 20px 0px;
                                                width: 100%;
                                                box-shadow: none;
                                                -webkit-box-shadow: none;
                                            }

                                            .mega-dropdown-menu:before {
                                                content: "";
                                                border-bottom: 15px solid #fff;
                                                border-right: 17px solid transparent;
                                                border-left: 17px solid transparent;
                                                position: absolute;
                                                top: -15px;
                                                left: 285px;
                                                z-index: 10;
                                            }

                                            .mega-dropdown-menu:after {
                                                content: "";
                                                border-bottom: 17px solid #ccc;
                                                border-right: 19px solid transparent;
                                                border-left: 19px solid transparent;
                                                position: absolute;
                                                top: -17px;
                                                left: 283px;
                                                z-index: 8;
                                            }

                                            .mega-dropdown-menu > li > ul {
                                                padding: 0;
                                                margin: 0;
                                            }

                                            .mega-dropdown-menu > li > ul > li {
                                                list-style: none;
                                            }

                                            .mega-dropdown-menu > li > ul > li > a {
                                                display: block;
                                                padding: 3px 20px;
                                                clear: both;
                                                font-weight: normal;
                                                line-height: 1.428571429;
                                                color: #999;
                                                white-space: normal;
                                            }

                                            .mega-dropdown-menu > li ul > li > a:hover,
                                            .mega-dropdown-menu > li ul > li > a:focus {
                                                text-decoration: none;
                                                color: #444;
                                                background-color: #f5f5f5;
                                            }

                                            .mega-dropdown-menu .dropdown-header {
                                                color: #428bca;
                                                font-size: 18px;
                                                font-weight: bold;
                                            }

                                            .mega-dropdown-menu form {
                                                margin: 3px 20px;
                                            }

                                            .mega-dropdown-menu .form-group {
                                                margin-bottom: 3px;
                                            }

                                        </style>
                                        <script>
                                            jQuery(document).on('click', '.mega-dropdown', function(e) {
                                                e.stopPropagation()
                                            })
                                        </script>

                                </ul>
                            @endif

                        </div>
                    </div>
                </nav>
            </header>

            @yield('content')
            <div class="page-content">
                <div class="footer row no-margin">
                    <div class="container">
                        <div class="footer-logo row no-margin">
                            @if(app()->getLocale()=="en")
                            <div class="logo-img">
                                <img style="height: 43px;border-radius: 50%;border: white solid 2px" src="{{url('/').Setting::get('site_logo',asset('asset/img/logo-white.png'))}}" style="width: 5em;height: auto">
                            </div>
                                @else
                                <div class="logo-img" style="float: right">
                                    <img style="height: 43px;border-radius: 50%;border: white solid 2px" src="{{url('/').Setting::get('site_logo',asset('asset/img/logo-white.png'))}}" style="width: 5em;height: auto">
                                </div>
                            @endif
                        </div>
                        <div class="row no-margin">
                            @if(app()->getLocale()=="en")
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <ul>
                                    <li><a href="login">@lang('user.ride')</a></li>
                                    <li><a href="provider/login">@lang('user.drive')</a></li>
                                    <li><a href="#">@lang('user.cities')</a></li>
                                    <li><a href="#">@lang('user.f_e')</a></li>
                                </ul>
                            </div>

                            <div   class="col-md-3 col-sm-3 col-xs-12">
                                <ul>
                                    <li><a href="{{url('register')}}">@lang('user.signup_to_ride')</a></li>
                                    <li><a href="{{url('provider/register')}}">@lang('user.become_to_drive')</a></li>
                                    <li><a href="{{url('login')}}">@lang('user.ride_now')</a></li>
                                    <li><a href="{{url('privacy')}}">@lang('user.privacy_policy')</a></li>
                                    <li><a href="{{url('condition')}}">@lang('user.s_c')</a></li>
                                </ul>
                            </div>

                            <div  class="col-md-3 col-sm-3 col-xs-12">
                                <h5>@lang('user.g_a_o')</h5>
                                <ul class="app">
                                    <li>
                                        <a href="{{Setting::get('store_link_ios','#')}}">
                                            <img src="{{asset('asset/img/appstore.png')}}">
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{Setting::get('store_link_android','#')}}">
                                            <img src="{{asset('asset/img/playstore.png')}}">
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            <div   class="col-md-3 col-sm-3 col-xs-12">
                                <h5>@lang('user.connect_us')</h5>
                                <ul class="social">
                                    <li><a href="{{ Setting::get('facebook_link') }}"><i class="fab fa-facebook-square"></i></a></li>
                                    <li><a href="{{ Setting::get('twitter_link') }}"><i class="fab fa-twitter-square"></i></a></li>
                                   <li><a href="{{ Setting::get('youtube_link') }}"><i class="fab fa-youtube"></i></a></li>
                                </ul>

                                <img style="height: 43px;border-radius: 50%;border: white solid 2px" src="{{url('/').Setting::get('site_logo',asset('asset/img/logo-white.png'))}}" style="width: 5em;height: auto">
                            </div>
                                @else
                                <div style="float: right;text-align:right" class="col-md-3 col-sm-3 col-xs-12">
                                    <ul>
                                        <li><a href="login">@lang('user.ride')</a></li>
                                        <li><a href="provider/login">@lang('user.drive')</a></li>
                                        <li><a href="#">@lang('user.cities')</a></li>
                                        <li><a href="#">@lang('user.f_e')</a></li>
                                    </ul>
                                </div>

                                <div style="float: right;text-align:right"  class="col-md-3 col-sm-3 col-xs-12">
                                    <ul>
                                        <li><a href="{{url('register')}}">@lang('user.signup_to_ride')</a></li>
                                        <li><a href="{{url('provider/register')}}">@lang('user.become_to_drive')</a></li>
                                        <li><a href="{{url('login')}}">@lang('user.ride_now')</a></li>
                                        <li><a href="{{url('privacy')}}">@lang('user.privacy_policy')</a></li>
                                        <li><a href="{{url('condition')}}">@lang('user.s_c')</a></li>
                                    </ul>
                                </div>

                                <div style="float: right;" class="col-md-3 col-sm-3 col-xs-12">
                                    <h5>@lang('user.g_a_o')</h5>
                                    <ul class="app">
                                        <li>
                                            <a href="{{Setting::get('store_link_ios','#')}}">
                                                <img src="{{asset('asset/img/appstore.png')}}">
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{Setting::get('store_link_android','#')}}">
                                                <img src="{{asset('asset/img/playstore.png')}}">
                                            </a>
                                        </li>
                                    </ul>
                                </div>

                                <div   class="col-md-3 col-sm-3 col-xs-12">
                                    <h5>@lang('user.connect_us')</h5>
                                    <ul class="social">
                                        <li><a href="{{ Setting::get('facebook_link') }}"><i class="fab fa-facebook-square"></i></a></li>
                                        <li><a href="{{ Setting::get('twitter_link') }}"><i class="fab fa-twitter-square"></i></a></li>
                                        <li><a href="{{ Setting::get('youtube_link') }}"><i class="fab fa-youtube"></i></a></li>
                                    </ul>

                                    <img style="height: 43px;border-radius: 50%;border: white solid 2px" src="{{url('/').Setting::get('site_logo',asset('asset/img/logo-white.png'))}}" style="width: 5em;height: auto">
                                </div>

                            @endif
                        </div>

                        <div class="row no-margin">
                            <div class="col-md-12 copy">

                                
                                <p>
                                    {{ Setting::get('site_copyright', '&copy; '.date('Y').' AilBaz.com') }};

                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{asset('asset/js/jquery.min.js')}}"></script>
    <script src="{{asset('asset/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('asset/js/scripts.js')}}"></script>

</html>
