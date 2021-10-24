@extends('user.layout.auth')

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
    <?php $login_user = asset('asset/img/login-user-bg.jpg'); ?>
    <div class="full-page-bg" style="background-image: url('{{asset(Setting::get('user_backgruond_photo'))}}');">

        <div class="log-overlay"></div>
        <div class="full-page-bg-inner">
            <div class="row no-margin">
                <div class="col-md-6 log-left">
                    <span style="background: none" class="login-logo"><img
                                style="height: 120px;border-radius: 50%;border: white solid 2px"
                                src="{{ url('/').Setting::get('site_logo', asset('logo-black.png'))}}"></span>
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
                        @isset($eror_mail)
                            <div class="text-center alert alert-danger">
                                 {{$eror_mail}}
                            </div>
                        @endisset
                            <div class="col-md-12">

                                <h3>@lang('user.create_new_account')</h3>
                                <a class="log-blk-btn" href="{{url('login')}}">@lang('user.already_have_an_account')</a>
                            </div>

                            <form role="form" method="POST" action="{{ url('/register') }}">

                                {{ csrf_field() }}
                                <div id="second_step">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" required
                                               placeholder="@lang('user.profile.first_name')" name="first_name"
                                               value="{{ old('first_name') }}">

                                        @if ($errors->has('first_name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('first_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" required
                                               placeholder="@lang('user.profile.last_name')" name="last_name"
                                               value="{{ old('last_name') }}">

                                        @if ($errors->has('last_name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('last_name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <input type="email" class="form-control" required name="email"
                                               placeholder="@lang('user.profile.email')" value="{{ old('email') }}">

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>

                                    <div id="first_step">
                                        <div class="col-md-4">


                                            <input value="+2" type="text" placeholder="+2" disabled id="country_code1" name="country_code1"/>
                                            <input value="+2" type="hidden" id="country_code" name="country_code"/>
                                        </div>

                                        <div class="col-md-8">
                                            <input type="text" required autofocus id="phone_number" class="form-control"
                                                   placeholder="@lang('user.profile.mobile')" name="phone_number"
                                                   value="{{ old('phone_number') }}"/>

                                            @if ($errors->has('phone_number'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('phone_number') }}</strong>
                                                </span>
                                            @endif
                                        </div>

                                        {{-- <div class="col-md-8">
                                            @if ($errors->has('mobile'))
                                                <span class="help-block">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                            @endif
                                        </div> --}}
{{--                                                                    <div class="col-md-12" style="padding-bottom: 10px;" id="mobile_verfication">--}}
{{--                                                                        <input type="button" class="log-teal-btn small" onclick="smsLogin();" value="@lang('user.verify_phone_number')"/>--}}
{{--                                                                    </div>--}}
                                    </div>

                                    <div class="col-md-12">
                                        <input type="password" required class="form-control" name="password"
                                               placeholder="@lang('user.profile.password')">

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <input type="password" required
                                               placeholder="@lang('user.profile.confirm_password')" class="form-control"
                                               name="password_confirmation">
                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                    <div class="col-md-12">
                                        <button class="log-teal-btn" type="submit">@lang('user.rigister')</button>
                                    </div>
                                </div>
                            </form>
                            <div class="col-md-12 text-center">
                                <p class="helper">@lang('user.or') <a
                                            href="{{route('login')}}">@lang('user.sing_in')</a>@lang('user.with_your_user_accont')
                                </p>
                                <p class="helper"><a href="{{ url('/') }}">@lang('user.back')</a></p>
                            </div>
                        </div>
                        <div class="log-copy"><p
                                    class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endsection
        @section('scripts')

            <script src="https://sdk.accountkit.com/en_US/sdk.js"></script>
            <script>
                // initialize Account Kit with CSRF protection
                AccountKit_OnInteractive = function () {
                    AccountKit.init(
                        {
                            appId: {{env('FB_APP_ID')}},
                            state: "state",
                            version: "{{env('FB_APP_VERSION')}}",
                            fbAppEventsEnabled: true
                        }
                    );
                };

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
                        $('#mobile_verfication').html("<p class='helper'> * Authentication Failed </p>");
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

            </script>

    </div>

@endsection
