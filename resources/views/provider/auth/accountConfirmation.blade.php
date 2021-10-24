@extends('provider.layout.auth')

@section('content')
<style>
        .log-teal-btn {
        background-color: #37b38b;
        padding: 20px;
        display: block;
        color: #fff;
        text-align: center;
        padding-bottom: 16px;
        width: 118%;
        border: 0;
        /* max-height: 55px; */
        margin-top: 10px;
        margin-bottom: 10px;
        border-radius: 22px 5px;
    }
    .text-center{
        color: #fff;
    }
    .login-box {
        background-color: #f1f1f166;
        padding: 40px 20px;
    }
    .helper a {
        font-weight: 900;
        font-size: 15px;
        color: #ffffff;
    }
</style>
    @include('flash::message')
    <div class="text-center">
        <h2 class="text-center">@lang('user.check_account')</h3>
        <h5 class="text-center">@lang('user.acount_che')</h3>
    </div>

    <div class="col-md-12">
        <form role="form" method="POST" action="{{ url('/provider/check/account', [$Provider->id,$Provider->id_url]) }}">
            {{ csrf_field() }}

            <input id="otp" type="text" class="form-control" name="otp" value="{{ old('otp') }}" placeholder="@lang('user.otp')" autofocus>

            @if ($errors->has('otp'))
                <span class="help-block">
                <strong>{{ $errors->first('otp') }}</strong>
            </span>
            @endif

            <br>

            <div class="col-md-6">
                <button class="log-teal-btn" type="submit">@lang('user.check_account')</button>
            </div>
            <div class="col-md-6">
                <a class="log-teal-btn"
                   href="{{ route('provider.resend.code', $Provider->id ) }}">
                    @lang('user.Resend Code')
                </a>
            </div>

            <p class="helper"><a href="{{ url('/provider/login') }}">@lang('user.login')</a></p>
            <p class="helper"> <a href="{{ url('/') }}">@lang('user.back')</a></p>
        </form>
        @if(Setting::get('social_login', 0) == 1)
        @endif
    </div>
@endsection

