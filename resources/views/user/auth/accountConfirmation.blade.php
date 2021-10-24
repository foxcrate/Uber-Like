@extends('user.layout.auth')

@section('content')

    <?php $login_user = asset('asset/img/login-user-bg.jpg'); ?>
    <div class="full-page-bg" style="background-image: url('http://192.168.1.200/ailbaz_server/public/{{Setting::get('user_backgruond_photo')}}');">

        <div class="log-overlay"></div>
        <div class="full-page-bg-inner">
            <div class="row no-margin">
                <div class="col-md-6 log-left">
                    <span style="background: none" class="login-logo"><img style="height: 120px;border-radius: 50%;border: white solid 2px" src="{{ url('/').Setting::get('site_logo', asset('logo-black.png'))}}"></span>
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
                        <div class="login-box row no-margin">
                            @include('flash::message')
                            <div class="col-md-12">
                                <a class="log-blk-btn" href="{{url('login')}}">@lang('user.already_have_an_account')</a>

                            </div>

                            <div class="row">
                                <h3>@lang('user.check_account')</h3>
                                <form role="form" method="POST" action="{{ url('/check/account', [$user->id,$user->id_url]) }}">
                                    {{ csrf_field() }}

                                    <div id="second_step" >
                                        <div class="col-md-12">
                                            <input type="text" class="form-control" name="otp" placeholder="@lang('user.otp')" value="{{ old('otp') }}">

                                            @if ($errors->has('otp'))
                                                <span class="help-block">
                                            <strong>{{ $errors->first('otp') }}</strong>
                                        </span>
                                            @endif
                                        </div>

                                        <div class="col-md-6">
                                            <button class="log-teal-btn" type="submit">@lang('user.check_account')</button>
                                        </div>
                                        <div class="col-md-6">
                                        <a class="log-teal-btn"
                                        href="{{ route('user.resend.code', $user->id ) }}">
                                            @lang('user.Resend Code')
                                        </a>
                                        </div>
                                    </div>

                                </form>
                            </div>

                            <div class="col-md-12">
                                <p class="helper">@lang('user.or') <a href="{{route('login')}}">@lang('user.sing_in')</a>@lang('user.with_your_user_accont')</p>
                                <p class="helper"> <a href="{{ url('/') }}">@lang('user.back')</a></p>
                            </div>

                        </div>
                        <div class="log-copy"><p class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}</p></div>
                    </div>
                </div>
            </div>
        </div>



    <script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.js'></script>
    <script  type="text/javascript">


    </script>


</div>
    @endsection
