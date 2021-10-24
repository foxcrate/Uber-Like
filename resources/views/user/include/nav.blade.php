<div class="col-md-3">
    <div class="dash-left">
        <div class="user-img">
            <?php $profile_image = auth()->user()->picture;?>
            @if($profile_image != null)
                <div class="pro-img">
                    <img style="width: 100%;height: 100%;border-radius: 50%;" src="{{asset($profile_image)}}">
                </div>
            @else
                <div class="pro-img">
                    <img style="width: 100%;height: 100%;border-radius: 50%;"
                         src="{{asset('asset/img/user2.jpg')}}">
                </div>
            @endif
            <h4>{{base64_decode(Auth::user()->first_name)}} {{base64_decode(Auth::user()->last_name)}}</h4>
        </div>
        <div class="side-menu">
            <ul>
                <li><a href="{{url('dashboard')}}">@lang('user.dashboard')</a></li>
                <li><a href="{{url('trips')}}">@lang('user.my_trips')</a></li>
                <li><a href="{{url('upcoming/trips')}}">@lang('user.upcoming_trips')</a></li>
                <li><a href="{{url('profile')}}">@lang('user.profile.profile')</a></li>
                <li><a href="{{url('change/password')}}">@lang('user.profile.change_password')</a></li>
                <li class="dropdown show">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="text-align: left;">
                        @lang('user.order_mycars')
                    </a>

                    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <a href="{{url('/order/My-Cars/')}}">@lang('user.order_mycars')</a>
                        <a href="{{url('/order/creat/')}}">@lang('user.order_cars_creat')</a>
                    </div>
                </li>
                <li><a href="{{url('/cars')}}">@lang('user.ad_cars')</a></li>
                <li><a href="{{url('/payment')}}">@lang('user.payment')</a></li>
                <li><a href="{{url('/promotions')}}">@lang('user.promotion')</a></li>
                <li><a href="{{url('/wallet')}}">@lang('user.my_wallet') <span
                                class="pull-right">{{currency(Auth::user()->wallet_balance)}}</span></a></li>
                <li><a href="{{ url('/logout') }}"
                       onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">@lang('user.profile.logout')</a></li>
                <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </ul>
        </div>
    </div>
</div>
