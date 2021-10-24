<!-- Header -->
<div class="site-header">
    <nav class="navbar navbar-light">
        <div class="navbar-left" style="background-color: #212330;">
            <a class="navbar-brand" style="background: none" href="{{url('/admin/dashboard')}}"><img
                        src="{{url('/').Setting::get('site_logo', asset('logo-black.png'))}}"
                        style="height: 43px;border-radius: 50%;border: white solid 2px;"></a>
            <div class="toggle-button dark sidebar-toggle-first float-xs-left hidden-md-up">
                <span class="hamburger"></span>
            </div>
            <div class="toggle-button-second dark float-xs-right hidden-md-up">
                <i class="ti-arrow-left"></i>
            </div>
            <div class="toggle-button dark float-xs-right hidden-md-up" data-toggle="collapse"
                 data-target="#collapse-1">
                <span class="more"></span>
            </div>
        </div>
        <style>
            .site-header .toggle-button.light span:before, .site-header .toggle-button.light span:after {
                background-color: #ffffff;
            }

            .site-header .toggle-button.light span {
                background-color: #ffffff;
            }

            .site-header .toggle-button.light:hover {
                background-color: #ffffff;
            }
        </style>
        <div class="navbar-right navbar-toggleable-sm collapse" id="collapse-1" style="background-color: #212330;">
            <div class="toggle-button light sidebar-toggle-second float-xs-left hidden-sm-down" style="color: #ffffff">
                <span class="hamburger"></span>
            </div>

            <ul class="nav navbar-nav">
                <li class="nav-item hidden-sm-down">
                    <a class="nav-link toggle-fullscreen" href="#">
                        <i class="ti-fullscreen" style="color: #ffffff"></i>
                    </a>
                </li>
            </ul>
            <ul class="nav navbar-nav float-md-right" style="">

                <!-- User Account: style can be found in dropdown.less -->
{{--                <ul class="nav navbar-nav">--}}
                    <li class="dropdown user user-menu" style="margin-left: -50px;">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" style="color: #ffffff">
                            <i class="fa fa-globe"></i>
                            <span class="hidden-xs"></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="{{url('admin/lang/ar')}}" style="color: #000000;font-size: 18px"> <i class="fa fa-flag" style="color: #000000"></i> عربى</a></li>
                            <li><a href="{{url('admin/lang/en')}}" style="color: #000000;font-size: 18px"><i class="fa fa-flag"></i> English</a></li>
                        </ul>
                    </li>
{{--                </ul>--}}

                <li class="nav-item dropdown hidden-sm-down" style="margin-top: -60px;">
                    <a href="#" data-toggle="dropdown" aria-expanded="false">
                        <span style="color: #ffffff;padding-right: 8px">{{Auth::guard('admin')->user()->name}}</span>
                        <span class="avatar box-32" style="width: 50px;">
							@if(Auth::guard('admin')->user()->picture != null)
                                <img src="{{asset(Auth::guard('admin')->user()->picture)}}" alt="">
                            @else
                                <img src="{{asset('asset/img/admin.png')}}" alt="">
                            @endif
						</span>
                    </a>

                    <div class="dropdown-menu dropdown-menu-right animated fadeInUp">
                        <a class="dropdown-item" href="{{route('admin.profile')}}">
                            <i class="ti-user mr-0-5"></i> @lang('admin.profile')
                        </a>
                        <a class="dropdown-item" href="{{route('admin.password')}}">
                            <i class="ti-settings mr-0-5"></i> @lang('admin.Change Password')
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{route('admin.help')}}"><i
                                    class="ti-help mr-0-5"></i> @lang('admin.Help')</a>
                        <a class="dropdown-item" href="{{ url('/admin/logout') }}"
                           onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();"><i
                                    class="ti-power-off mr-0-5"></i> @lang('admin.Logout')</a>
                    </div>

                </li>

            </ul>

        </div>
    </nav>
</div>