@extends('provider.layout.auth')

@section('content')
    <div class="col-md-12">
        <!-- <a class="log-blk-btn" href="{{-- url('/provider/login') --}}">@lang('user.already_registered')</a> -->
        <h3>@lang('user.sing_up')</h3>
    </div>

    <div class="col-md-12">
        <form class="form-horizontal" role="form" method="POST" action="{{ url('/provider/register/new_car_type_false/'. $provider->id) }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <div id="second_step">
                <div class="form-group col-md-12">
                    <h4 style="font-weight: bold">سياره خاصه</h4>
                </div>

                <div class="form-group col-md-12">
                    <select class="form-control" name="service_type_id" id="service_type_id">
                        <option value="0">@lang('user.chose_car')</option>
                        @foreach( $car_models as $type)
                            <option value="{{$type->id}}">{{$type->name}} - {{$type->date}}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('service_type_id'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('service_type_id')}}</strong>
                                </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.number_car') </label>
                    <input id="car_number" type="text" class="form-control" name="car_number"
                           value="{{ old('car_number') }}" placeholder="@lang('user.number_car')">

                    @if ($errors->has('car_number'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('car_number') }}</strong>
                                </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.color') </label>
                    <input id="color" type="text" class="form-control" name="color"
                           value="{{ old('color') }}" placeholder="@lang('user.color')">

                    @if ($errors->has('color'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('color') }}</strong>
                                </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_front') </label>
                    <input id="car-front-model" type="file" class="form-control" name="car_front">
                    @if ($errors->has('car_front'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_front') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_back') </label>
                    <input id="car-front-model" type="file" class="form-control" name="car_back">
                    @if ($errors->has('car_back'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_back') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_left') </label>
                    <input id="car-front-model" type="file" class="form-control" name="car_left">
                    @if ($errors->has('car_left'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_left') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_right') </label>
                    <input id="car-front-model" type="file" class="form-control" name="car_right">
                    @if ($errors->has('car_right'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_right') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_licence_front') </label>
                    <input id="car-front-model" type="file" class="form-control" name="car_licence_front">
                    @if ($errors->has('car_licence_front'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_licence_front') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_licence_back') </label>
                    <input id="car-front-model" type="file" class="form-control" name="car_licence_back">
                    @if ($errors->has('car_licence_back'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_licence_back') }}</strong>
                                    </span>
                    @endif
                </div>

                <button type="submit" class="log-teal-btn">
                    @lang('user.rigister')
                </button>
            </div>
        </form>
    </div>
@endsection
