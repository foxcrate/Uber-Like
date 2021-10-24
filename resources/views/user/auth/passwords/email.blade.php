@extends('user.layout.auth')

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
                                <h3>@lang('user.r_p')</h3>
                            </div>
                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif
                            <form role="form" method="POST" action="{{ url(route('auth.resetPasswordUser')) }}">
                                {{ csrf_field() }}

                                <div class="col-md-12">
                                    <input type="email" class="form-control" name="email"
                                           placeholder="@lang('user.email_address')" value="{{ old('email') }}">

                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                    @endif
                                </div>


                                <div class="col-md-12">
                                    <button class="log-teal-btn" type="submit">@lang('user.s_p_r_l')</button>
                                </div>
                            </form>

                            <div class="col-md-12">
                                <p class="helper">@lang('user.or') <a
                                            href="{{route('login')}}">@lang('user.sing_in')</a>@lang('user.with_your_user_accont')
                                </p>
                            </div>
                        </div>

                        <div class="log-copy"><p
                                    class="no-margin">{{ Setting::get('site_copyright', '&copy; '.date('Y').' Appoets') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
