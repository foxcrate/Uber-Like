<div class="col-md-3">
    <div class="dash-left">
        <div class="user-img">
            <?php $profile_image = auth('fleet')->user()->logo;?>
            @if(asset($profile_image) != null)
                <div class="pro-img">
                    <img style="width: 100%;height: 100%;border-radius: 50%;" src="{{asset($profile_image)}}">
                </div>
            @else
                <div class="pro-img">
                    <img style="width: 100%;height: 100%;border-radius: 50%;"
                         src="{{asset('asset/img/provider.jpg')}}">
                </div>
            @endif
            <h4>{{auth('fleet')->user()->name}}</h4>
        </div>
        <div class="side-menu">
            <ul>
                <li><a href="{{url('company/dashboard')}}">@lang('user.homepage')</a></li>
                <li><a href="{{url('company/profile')}}">@lang('user.profile.profile')</a></li>
                <li><a href="{{url(route('company.password'))}}">@lang('user.profile.change_password')</a></li>
                <li><a href="{{ url('company/logout') }}"
                       onclick="event.preventDefault();
                        document.getElementById('logout-form').submit();">@lang('user.profile.logout')</a></li>
                <form id="logout-form" action="{{ url(route('company.logout')) }}" method="POST" style="display: none;">
                    {{ csrf_field() }}
                </form>
            </ul>
        </div>
    </div>
</div>