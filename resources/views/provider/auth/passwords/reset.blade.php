@extends('provider.layout.auth')

<!-- Main Content -->
@section('content')
    @include('flash::message')

    <div class="col-md-12">
        <a class="log-blk-btn" href="{{url('/provider/login')}}">@lang('user.a_h_a_a')</a>
        <h3>@lang('admin.Reset Password') </h3>
    </div>
{{--    @if (session('status'))--}}
{{--        <div class="alert alert-success">--}}
{{--            {{ session('status') }}--}}
{{--        </div>--}}
{{--    @endif--}}

    <form class="form-horizontal" role="form" method="POST" action="{{ url('provider/new-password-provider/'.$provider_id.'/'.$provider_code) }}">
        {{ csrf_field() }}
{{--        <input id="otp" type="text" class="form-control" name="otp" value="{{ old('otp') }}" placeholder="@lang('user.otp')" required autofocus>--}}

{{--    @if ($errors->has('otp'))--}}
{{--            <span class="help-block">--}}
{{--                <strong>{{ $errors->first('otp') }}</strong>--}}
{{--            </span>--}}
{{--        @endif--}}

{{--        <div class="col-md-12">--}}
{{--            <div class="form-group {{ $errors->has('email') ? ' has-error' : '' }}">--}}
{{--                <input type="email" name="email" value="{{ $email or old('email') }}" autofocus required--}}
{{--                       class="form-control" id="email" placeholder="@lang('admin.Email')">--}}
{{--                @if ($errors->has('email'))--}}
{{--                    <span class="help-block">--}}
{{--                                        <strong>{{ $errors->first('email') }}</strong>--}}
{{--                                    </span>--}}
{{--                @endif--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-md-12">
            <div class="form-group {{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" name="password" required class="form-control" id="password"
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
                <input type="password" name="password_confirmation" required class="form-control"
                       id="password_confirmation" placeholder="@lang('admin.Password Confirmation')">
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('password_confirmation') }}</strong>
                                    </span>
                @endif
            </div>
        </div>
        <div class="col-md-12">
            <button class="log-teal-btn" type="submit">@lang('admin.Reset Password')</button>
        </div>
    </form>


@endsection



