@extends('company.layout.auth')

@section('content')
<style>
.login-box h3 {
    padding-top: 20px;
    padding-bottom: 20px;
    margin: 0 0 10px 0!important;
    font-size: 18px;
    background: #ffffff3d;
    text-align: center;
    /* font-style: revert; */
    font-weight: bold;
    color: #000000a1;

}
.login-box {
    background-color: #f1f1f187;
    padding: 40px 20px;
    
}
.log-blk-btn {
    background-color: #39c0ea;
    font-size: large;
    border-radius: 10px;
    margin-bottom:10px
}
.log-blk-btn:hover, .log-blk-btn:focus, .log-blk-btn:active {
    background-color: #3fb2d6;
    color: #fff;
    text-decoration: none;
}
.login-box input {
   /* height: 45px;*/
    border-radius: 10px;
}
.log-teal-btn {

    border-radius: 10px;
}
.log-copy {
    background-color: #f1f1f1e3;
}
.helper{
    text-align: center;
}
.helper a {
    color: #39c0ea;
}

.helper a:hover, .helper a:focus, .helper a :active {
    color: #39c0ea;
    text-decoration: none;
}
.helper a:hover, .helper a:focus, .helper a :active {
    color: #47b4d6;
    text-decoration: none;
}
.log-left h2 , .log-left h1,.log-left h3,.log-left h3 strong,.log-left p{
    color: #eee;
    
}
.log-left h3{
    font-weight: 900;
}
.log-left h1 {
    font-weight: bold;
}
</style>
@if(app()->getLocale()=="ar")
    <style>
        .login-box{
            direction: rtl;
        }
    </style>
@endif
<div class="full-page-bg" style="background-image: url('{{asset(Setting::get('company_backgruond_photo', 'uploads/tesla-taxi-main.png'))}}');">
    <div class="log-overlay"></div>
    <div class="full-page-bg-inner">
        <div class="row no-margin">
            <div class="col-md-6 log-left">
                <span class="login-logo" style="background: none"><img src="{{url('/').Setting::get('site_logo', asset('logo-black.png'))}}" style="height: 120px;border-radius: 50%;border: white solid 2px"></span>
                @if(app()->getLocale()=="en")
                <h2>{!!Setting::get('big_title_en',asset(''))!!}</h2>
                <p>{!!Setting::get('small_title_en',asset(''))!!}</p>
                @else
                    <h2>{!!Setting::get('big_title_ar',asset(''))!!}</h2>
                    <p>{!!Setting::get('small_title_ar',asset(''))!!}</p>
                @endif
            </div>
            <div class="col-md-6 log-right">
                <div class="login-box-outer">
                    @include('flash::message')
                    <div class="login-box row no-margin">
                    <div class="col-md-12">
{{--                        <a class="log-blk-btn" href="{{url('register')}}">@lang('user.create_new_account')</a>--}}
                        <h3>@lang('user.login')</h3>
                    </div>

                    <form  role="form" method="POST" action="{{ url(route('company.login')) }}">
{{--                    {{ csrf_field() }}--}}
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <div class="col-md-12">
                             <input id="email" type="email" class="form-control" placeholder="@lang('user.email_address')" name="email" value="{{ old('email') }}"  autofocus>

                            @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                            @endif
                        </div>

                        
                        <div class="col-md-12">
                            <input id="password" type="password" class="form-control" placeholder="@lang('user.pass')" name="password" required>

                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="col-md-12">
                            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : ''}}><span> @lang('user.remember_me')</span>
                        </div>
                       
                        <div class="col-md-12">
                            <button type="submit" class="log-teal-btn">@lang('user.login')</button>
                        </div>
                    </form>

                    <div class="col-md-12">
                        <p class="helper"> <a href="{{route('company.email')}}">@lang('user.forget_pass')</a></p>
                        <p class="helper"> <a href="{{ url('/') }}">@lang('user.back')</a></p>
                    </div>
                </div>


                <div class="log-copy"><p class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}</p></div></div>
            </div>
        </div>
    </div>
</div>
@endsection