@extends('provider.layout.app')

@section('content')
    <div class="pro-dashboard-head">
        <div class="container">
            <a href="{{ route('provider.profile.index') }}" class="pro-head-link">@lang('user.Profile')</a>
            {{-- <a href="{{ route('provider.documents') }}" class="pro-head-link">@lang('user.Update Documents')</a> --}}
            <a href="{{ route('provider.cars') }}" class="pro-head-link active">@lang('user.My Cars')</a>
            <a href="{{ route('provider.location.index') }}" class="pro-head-link">@lang('user.Update Location')</a>
        </div>
    </div>

    <div class="pro-dashboard-content container">
        <form class="form-horizontal" role="form" method="POST" action="{{ url(route('provider.updateCar',$car->id)) }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="text-center">
                <h3>@lang('user.Update Car')</h3>
            </div>

            <div class="form-group col-md-12">
                <label> @lang('user.car_front') </label>
                <div>
                    @if(isset($car->car_front))
                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                             src="{{asset($car->car_front)}}">
                    @endif
                </div>
                <input id="car-front-model" type="file" class="form-control" name="car_front">

                @if ($errors->has('car_front'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('car_front') }}</strong>
                                    </span>
                @endif
            </div>

            <div class="form-group col-md-12">
                <label> @lang('user.car_back') </label>
                <div>
                    @if(isset($car->car_back))
                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                             src="{{asset($car->car_back)}}">
                    @endif
                </div>
                <input id="car-front-model" type="file" class="form-control" name="car_back">
                @if ($errors->has('car_back'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('car_back') }}</strong>
                                    </span>
                @endif
            </div>

            <div class="form-group col-md-12">
                <label> @lang('user.car_left') </label>
                <div>
                    @if(isset($car->car_left))
                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                             src="{{asset($car->car_left)}}">
                    @endif
                </div>
                <input id="car-front-model" type="file" class="form-control" name="car_left">
                @if ($errors->has('car_left'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('car_left') }}</strong>
                                    </span>
                @endif
            </div>

            <div class="form-group col-md-12">
                <label> @lang('user.car_right') </label>
                <div>
                    @if(isset($car->car_right))
                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                             src="{{asset($car->car_right)}}">
                    @endif
                </div>
                <input id="car-front-model" type="file" class="form-control" name="car_right">
                @if ($errors->has('car_right'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('car_right') }}</strong>
                                    </span>
                @endif
            </div>

            <div class="form-group col-md-12">
                <label> @lang('user.car_licence_front') </label>
                <div>
                    @if(isset($car->car_licence_front))
                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                             src="{{asset($car->car_licence_front)}}">
                    @endif
                </div>
                <input id="car-front-model" type="file" class="form-control" name="car_licence_front">
                @if ($errors->has('car_licence_front'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('car_licence_front') }}</strong>
                                    </span>
                @endif
            </div>

            <div class="form-group col-md-12">
                <label> @lang('user.car_licence_back') </label>
                <div>
                    @if(isset($car->car_licence_back))
                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                             src="{{asset($car->car_licence_back)}}">
                    @endif
                </div>
                <input id="car-front-model" type="file" class="form-control" name="car_licence_back">
                @if ($errors->has('car_licence_back'))
                    <span class="help-block">
                                        <strong>{{ $errors->first('car_licence_back') }}</strong>
                                    </span>
                @endif
            </div>
            <br>
            <button type="submit" class="btn btn-primary btn-lg" style="margin-bottom: 20px">
                @lang('user.Update')
            </button>

        </form>

    </div>
@endsection
