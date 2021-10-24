@extends('provider.layout.auth')

<!-- Main Content -->
@section('content')
    @include('flash::message')
    <div class="col-md-12">
        <a class="log-blk-btn" href="{{url('/provider/login')}}">@lang('user.a_h_a_a')</a>
        <h3>@lang('user.r_p') </h3>
    </div>
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    <form role="form" method="POST" action="{{ url(route('auth.resetPasswordProvider')) }}">
        {{ csrf_field() }}

        <div class="col-md-12">
            <input type="email" class="form-control" name="email" placeholder="@lang('user.email_address') " value="{{ old('email') }}">

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


@endsection


