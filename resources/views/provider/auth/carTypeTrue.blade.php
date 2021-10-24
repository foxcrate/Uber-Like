@extends('provider.layout.auth')

@section('content')
    <div class="col-md-12">
        <!-- <a class="log-blk-btn" href="{{-- url('/provider/login') --}}">@lang('user.already_registered')</a> -->
        <h3>@lang('user.sing_up')</h3>
    </div>

    <div class="col-md-12">
        <form class="form-horizontal" role="form" method="POST" action="{{route('postCarTypeTrue', [$provider->id ,$provider->id_url]) }}"
              enctype="multipart/form-data">

            {{ csrf_field() }}

            <div id="second_step">

                <div class="form-group col-md-12">
                    <h4 style="font-weight: bold">سياره موجود</h4>
                    <a class="log-blk-btn" href="{{ route('carTypeFalse', [$provider->id ,$provider->id_url]) }}">@lang('user.car_type_false')</a>
                </div>

                <input id="car_code" type="text" class="form-control" name="car_code" value="{{ old('car_code') }}"
                       placeholder="@lang('admin.car_code')" autofocus>

                @if ($errors->has('car_code'))
                    <span class="help-block">
                    <strong>{{ $errors->first('car_code') }}</strong>
                </span>
                @endif

                <button type="submit" class="log-teal-btn">
                    @lang('user.rigister')
                </button>

               
                <p class="helper"> <a href="{{ url('/') }}">@lang('user.back')</a></p>
            </div>
        </form>
    </div>
@endsection