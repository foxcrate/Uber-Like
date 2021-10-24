@extends('provider.layout.auth')

@section('content')
    @include('flash::message')
    <div class="col-md-12">
        <a class="log-blk-btn" href="{{ url('/provider/login') }}">@lang('user.already_registered')</a>
        <h3>@lang('user.car_image_upload')</h3>
    </div>

    <div class="col-md-12">
        <form class="form-horizontal" role="form" method="POST"
              action="{{ url('/provider/car/image-upload',$car->id) }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            @if($car->car_front == null)
                <div class="form-group col-md-12">
                    <label> @lang('user.car_front') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_front">
                    @if ($errors->has('car_front'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_front') }}</strong>
                                    </span>
                    @endif
                </div>
            @endif
            @if($car->car_back == null)
                <div class="form-group col-md-12">
                    <label> @lang('user.car_back') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_back">
                    @if ($errors->has('car_back'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_back') }}</strong>
                                    </span>
                    @endif
                </div>
            @endif
            @if($car->car_left == null)
                <div class="form-group col-md-12">
                    <label> @lang('user.car_left') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_left">
                    @if ($errors->has('car_left'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_left') }}</strong>
                                    </span>
                    @endif
                </div>
            @endif
            @if($car->car_right == null)
                <div class="form-group col-md-12">
                    <label> @lang('user.car_right') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_right">
                    @if ($errors->has('car_right'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_right') }}</strong>
                                    </span>
                    @endif
                </div>
            @endif
            @if($car->car_licence_front == null)
                <div class="form-group col-md-12">
                    <label> @lang('user.car_licence_front') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_licence_front">
                    @if ($errors->has('car_licence_front'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_licence_front') }}</strong>
                                    </span>
                    @endif
                </div>
            @endif
            @if($car->car_licence_back == null)
                <div class="form-group col-md-12">
                    <label> @lang('user.car_licence_back') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_licence_back">
                    @if ($errors->has('car_licence_back'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_licence_back') }}</strong>
                                    </span>
                    @endif
                </div>
            @endif

            <button type="submit" class="log-teal-btn">
                @lang('user.Update')
            </button>
            {{--            </div>--}}
        </form>
    </div>
@endsection
