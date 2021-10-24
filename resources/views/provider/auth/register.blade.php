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
        height: 45px;
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
    .log-left h2 , .log-left h1,.log-left h3,.log-left  strong,.log-left p{
        color: #eee;

    }
    .log-left h3{
        font-weight: 900;
    }
    .log-left h1 {
        font-weight: bold;
    }
    .login-box select {

        border-radius: 10px;

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

        <h3>@lang('user.sing_up')</h3>
        <a class="log-blk-btn" href="{{ url('/provider/login') }}">@lang('user.already_registered')</a>
    </div>

    <div class="col-md-12">
        <form class="form-horizontal text-center" role="form" method="POST" action="{{ url('/provider/register') }}"
              enctype="multipart/form-data">

            {{ csrf_field() }}

            <div id="second_step">

                <input id="name" type="text" class="form-control" name="first_name" required value="{{ old('first_name') }}"
                       placeholder="@lang('user.profile.first_name')">

                @if ($errors->has('first_name'))
                    <span class="help-block">
                    <strong>{{ $errors->first('first_name') }}</strong>
                </span>
                @endif

                <input id="name" type="text" class="form-control" name="last_name" required value="{{ old('last_name') }}"
                       placeholder="@lang('user.profile.last_name')">

                @if ($errors->has('last_name'))
                    <span class="help-block">
                    <strong>{{ $errors->first('last_name') }}</strong>
                </span>
                @endif

                <input id="email" type="email" class="form-control" name="email" required value="{{ old('email') }}"
                       placeholder="@lang('user.profile.email')">

                @if ($errors->has('email'))
                    <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
                @endif

                <div class="col-md-4">
                    <input value="+2" type="text" placeholder="+2" disabled id="country_code1" name="country_code1"/>
                    <input value="+2" type="hidden" id="country_code" name="country_code"/>
                </div>
                @if ($errors->has('country_code'))
                <span class="help-block">
                    <strong>{{ $errors->first('country_code') }}</strong>
                </span>
            @endif

                <div class="col-md-8">
                    <input type="number" autofocus id="phone_number" required class="form-control"
                           placeholder="{{trans('user.mobile')}}"
                           name="phone_number" value="{{ old('phone_number') }}" onkeydown="limit_phone(this);" onkeyup="limit_phone(this);" />
                </div>

                <div class="col-md-8">
                    @if ($errors->has('phone_number'))
                        <span class="help-block">
                        <strong>{{ $errors->first('phone_number') }}</strong>
                    </span>
                    @endif
                </div>

                <input id="identity_number" type="number" class="form-control" name="identity_number" required value="{{ old('identity_number') }}"
                       placeholder="@lang('user.profile.identity_number')"  onkeydown="limit_identity(this);" onkeyup="limit_identity(this);">

                @if ($errors->has('identity_number'))
                    <span class="help-block">
                    <strong>{{ $errors->first('identity_number') }}</strong>
                </span>
                @endif

                <label for="governorate_id">{{trans('user.certificate')}}</label>
                <div style="margin-bottom: 20px">
                    <select name="car_license_type" id="" required>
                        {{-- <option >@lang('user.certificate')</option> --}}
                        <option value="0">@lang('user.certificate_0')</option>
                        <option value="1">@lang('user.certificate_1')</option>
                        <option value="2">@lang('user.certificate_2')</option>
                        <option value="3">@lang('user.certificate_3')</option>
                    </select>
                </div>
                @if ($errors->has('car_license_type'))
                    <span class="help-block">
                        <strong>{{ $errors->first('car_license_type') }}</strong>
                    </span>
                @endif

                <input id="password" type="password" class="form-control" required name="password"
                       placeholder="@lang('user.profile.password')">

                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
                @endif

                <input id="password-confirm" type="password" class="form-control" required name="password_confirmation"
                       placeholder="@lang('user.profile.confirm_password')">

                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                    <strong>{{ $errors->first('password_confirmation') }}</strong>
                </span>
                @endif

                <label for="governorate_id">{{trans('admin.governorate_id')}}</label>
                <div style="margin-bottom: 20px">
                    <select  class="form-control " name="governorate_id" required >
                        {{-- <option>@lang('admin.You must choose the governorate model')</option> --}}
                        @foreach($governorates as $governorate)

                            @if(app()->getLocale()=="en")
                                <option value="{{ $governorate->id}}">{{ $governorate->name_en }}</option>
                            @else
                                <option value="{{ $governorate->id}}">{{ $governorate->name }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
                @if ($errors->has('governorate_id'))
                    <span class="help-block">
                    <strong>{{ $errors->first('governorate_id') }}</strong>
                </span>
                @endif

{{--                <div class="form-group  text-center col-md-12">--}}
{{--                    <div style="font-size: 15px" class="pr-5 col-md-6">--}}
{{--                        {!! Form::radio('car_type', 'true', true,['id' => 'accept']) !!} @lang('user.A car already exists')--}}
{{--                    </div>--}}
{{--                    <div style="font-size: 15px" class="pr-5 col-md-6">--}}
{{--                        {!! Form::radio('car_type', 'false', null,['id' => 'desaple']) !!} @lang('user.private car')--}}
{{--                    </div>--}}
{{--                </div>--}}

                <button type="submit" class="log-teal-btn">
                    @lang('user.rigister')
                </button>
                <p class="helper"><a href="{{ url(route('auth.resetProvider')) }}">@lang('user.forget_pass')</a></p>
                <p class="helper"> <a href="{{ url('/') }}">@lang('user.back')</a></p>
            </div>
        </form>
    </div>
@endsection


@section('scripts')
    <script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
    <script>
        // initialize Account Kit with CSRF protection
        AccountKit_OnInteractive = function () {
            AccountKit.init(
                {
                    appId:{{env('FB_APP_ID')}},
                    state: "state",
                    version: "{{env('FB_APP_VERSION')}}",
                    fbAppEventsEnabled: true,
                    redirect: "http://192.168.1.200/ailbaz_server/public/provider/register",
                    debug: true
                }
            );
        };

    </script>
    <script>

        // login callback
        function loginCallback(response) {
            if (response.status === "PARTIALLY_AUTHENTICATED") {
                var code = response.code;
                var csrf = response.state;
                // Send code to server to exchange for access token
                $('#mobile_verfication').html("<p class='helper'> * Phone Number Verified </p>");
                $('#phone_number').attr('readonly', true);
                $('#country_code').attr('readonly', true);
                $('#second_step').fadeIn(400);

                $.post("{{route('account.kit')}}", {code: code}, function (data) {
                    $('#phone_number').val(data.phone.national_number);
                    $('#country_code').val('+' + data.phone.country_prefix);
                });

            } else if (response.status === "NOT_AUTHENTICATED") {
                // handle authentication failure
                $('#mobile_verfication').html("<p class='helper'> * @lang('user.authentication_failed') </p>");
            } else if (response.status === "BAD_PARAMS") {
                // handle bad parameters
            }
        }
    </script>

    <script>
        // phone form submission handler
        function smsLogin() {
            var countryCode = document.getElementById("country_code").value;
            var phoneNumber = document.getElementById("phone_number").value;

            $('#mobile_verfication').html("<p class='helper'> @lang('user.plz_wait') </p>");
            $('#phone_number').attr('readonly', true);
            $('#country_code').attr('readonly', true);

            AccountKit.login(
                'PHONE',
                {countryCode: countryCode, phoneNumber: phoneNumber}, // will use default values if not specified
                loginCallback
            );
        }

        function limit_phone(element)
        {

            var max_chars = 11;

            if(element.value.length > max_chars) {
                element.value = element.value.substr(0, max_chars);
            }
        }

        function limit_identity(element)
        {

            var max_chars = 14;

            if(element.value.length > max_chars) {
                element.value = element.value.substr(0, max_chars);
            }
        }

    </script>

@endsection
