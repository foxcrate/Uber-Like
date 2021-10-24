<div class="footer row no-margin">
    <div class="container">
        <div class="row no-margin">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <ul>
                    <li><a href="login">@lang('user.ride')</a></li>
                    <li><a href="provider/login">@lang('user.drive')</a></li>
                    <li><a href="{{url('register')}}">@lang('user.signup_to_ride')</a></li>
                    <li><a href="{{url('provider/register')}}">@lang('user.become_to_drive')</a></li>
                </ul>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-12">
                <ul>
                    <li><a href="{{url('login')}}">@lang('user.ride_now')</a></li>
                    <li><a href="{{url('privacy')}}">@lang('user.privacy_policy')</a></li>
                    <li><a href="{{url('condition')}}">@lang('user.s_c')</a></li>
                </ul>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12">
                <h5>@lang('user.Get App on')</h5>
                <ul class="app">
                    <li><a href="{{Setting::get('store_link_ios','#')}}"><img src="{{ asset('asset/img/appstore.png') }}"></a></li>
                    <a href="{{Setting::get('store_link_android_provider','#')}}">
                                                <img src="{{asset('asset/img/playstore.png')}}">
                                            </a>
                </ul>
            </div>

            <div class="col-md-3 col-sm-3 col-xs-12">
                <h5>@lang('user.Connect us')</h5>
                <ul class="social">
                    <li><a href="{{ Setting::get('facebook_link') }}"><i class="fa fa-facebook-square"></i></a></li>
                    <li><a href="{{ Setting::get('twitter_link') }}"><i class="fa fa-twitter-square"></i></a></li>
                    <li><a href="{{ Setting::get('youtube_link') }}"><i class="fa fa-youtube"></i></a></li>
                </ul>
            </div>
        </div>

        <div class="row no-margin">
            <div class="col-md-12 copy text-center">
            <h3 style="color: white;">
                                {{ Setting::get('site_copyright',  'AilBaz.com') }}&copy;

                            </h3>
            </div>
            
        </div>
    </div>
</div>