@extends('company.layout.auth')

@section('content')

    <?php $login_user = asset('asset/img/login-user-bg.jpg'); ?>
    <div class="full-page-bg" style="background-image: url({{$login_user}});">

        <div class="log-overlay"></div>
        <div class="full-page-bg-inner">
            <div class="row no-margin">
                <div class="col-md-6 log-left">
                    <span class="login-logo" style="background: none"><img
                                src="{{url('/').Setting::get('site_logo', asset('logo-black.png'))}}"
                                style="height: 120px;border-radius: 50%;border: white solid 2px"></span>
                    <h2>@lang('user.creat-your-account')</h2>
                    <p> @lang('user.creat-your-account') {{ Setting::get('site_title', 'Imcanat')  }}
                        , @lang('user.complet_welcom_to')</p>
                </div>
                <div class="col-md-6 log-right">
                    <div class="login-box-outer">
                        <div class="login-box row no-margin">
                            @include('flash::message')
                            <div class="col-md-12">
                                <a class="log-blk-btn" href="{{url('login')}}">@lang('user.a_h_a_a')</a>
                                <h3>@lang('admin.Reset Password')</h3>
                            </div>
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form role="form" method="POST" action="{{ url(route('company.resetPassword',$company->id)) }}">
                                {{ csrf_field() }}
                                <div class="col-md-12">
                                    <input id="otp" type="text" class="form-control" name="otp"
                                           placeholder="@lang('user.otp')" required autofocus>
                                    @if ($errors->has('otp'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('otp') }}</strong>
                                    </span>
                                    @endif
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                                        <input type="password" name="password" required class="form-control"
                                               id="password"
                                               placeholder="@lang('admin.Password')">
                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group {{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                                        <input type="password" name="password_confirmation" required
                                               class="form-control"
                                               id="password_confirmation"
                                               placeholder="@lang('admin.Password Confirmation')">
                                        @if ($errors->has('password_confirmation'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button class="log-teal-btn" type="submit">RESET PASSWORD</button>
                                </div>
                            </form>

                        </div>

                        <div class="log-copy">
                            <p class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
