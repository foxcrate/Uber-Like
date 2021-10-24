@extends('provider.layout.auth')

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
    
    border-radius: 10px;
}
.log-teal-btn {

    border-radius: 10px;
}
.checkbox label, .radio label {
    
    margin: 0 25px;
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
.log-left h2 , .log-left h1,.log-left h3,.log-left  strong,.log-left p{
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
    @include('flash::message')

<div class="col-md-12">
    
    <h3>@lang('user.sing_in')</h3>
    <a class="log-blk-btn" href="{{ url('/provider/register') }}">@lang('user.create_new_account')</a>
</div>
<div class="col-md-12">
    <form role="form" method="POST" action="{{ url('/provider/login') }}">
{{--        {{ csrf_field() }}--}}
        <input type="hidden" name="_token" value="{{ csrf_token() }}">

        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="@lang('user.email_address')" autofocus>

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif

        <input id="password" type="password" class="form-control" name="password" placeholder="@lang('user.pass')">

        @if ($errors->has('password'))
            <span class="help-block">
                <strong>{{ $errors->first('password') }}</strong>
            </span>
        @endif

        <div class="checkbox">
        <input type="checkbox" name="remember">
            <label>
                @lang('user.remember_me')
            </label>
        </div>

        <br>

        <button type="submit" class="log-teal-btn">
        @lang('user.login')
        </button>

        <p class="helper"><a href="{{ url(route('auth.resetProvider')) }}">@lang('user.forget_pass')</a></p>
        <p class="helper"> <a href="{{ url('/') }}">@lang('user.back')</a></p>
    </form>
    @if(Setting::get('social_login', 0) == 1)
    @endif
</div>
@endsection
