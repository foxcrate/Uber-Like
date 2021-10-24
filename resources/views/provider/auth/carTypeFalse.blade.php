@extends('provider.layout.auth')

@section('content')

    @include('flash::message')

    <div class="col-md-12">
        <!-- <a class="log-blk-btn" href="{{-- url('/provider/login') --}}">@lang('user.already_registered')</a> -->
        <h3>@lang('user.sing_up')</h3>
    </div>

    <div class="col-md-12">


        <form class="form-horizontal" role="form" method="POST" action="{{ url('/provider/register/car_type_false', [$provider->id,$provider->id_url]) }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <div id="second_step">
                <div class="form-group col-md-12">
                    <h4 style="font-weight: bold">سياره خاصه</h4>
                    <a class="log-blk-btn" href="{{ route('carTypeTrue', [$provider->id ,$provider->id_url]) }}">@lang('user.car_type_true')</a>
                </div>

                <div class="form-group col-md-12">
                    <select class="form-control" name="service_type_id" id="service_type_id">
                        {{-- <option value="0">@lang('user.chose_car')</option> --}}
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
                    {{-- <div class="col-xs-6"> --}}
                    <div>
                        <div class="col-sm-4" style="margin-right:2px;margin-left:2px; padding-left: 0px;padding-right: 10px">
                            <input id="car_number1" type="number" class="form-control p-0" name="car_number1"
                            value="{{ old('car_number1') }}" placeholder="أرقام">
                            {{-- "@lang('user.numbersInCarNumber')" --}}
                            @if ($errors->has('car_number1'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('car_number1') }}</strong>
                                        </span>
                            @endif
                        </div>

                        {{-- <div class="col-xs-6"> --}}

                            <div class="col-sm-2" style="width: 60px; margin-right:2px; padding-left: 0px; padding-right: 0px;">
                                <input id="car_number4" type="text" class="form-control p-0" name="car_number4"
                                    value="{{ old('car_number4') }}" placeholder="حرف3">

                                @if ($errors->has('car_number4'))
                                    <span class="help-block">
                                                <strong>{{ $errors->first('car_number4') }}</strong>
                                            </span>
                                @endif
                            </div>

                            <div class="col-sm-2" style="width: 60px; margin-right:2px; padding-left: 0px; padding-right: 0px;">
                                <input id="car_number3" type="text" class="form-control p-0" name="car_number3"
                                    value="{{ old('car_number3') }}" placeholder="حرف2">

                                @if ($errors->has('car_number3'))
                                    <span class="help-block">
                                                <strong>{{ $errors->first('car_number3') }}</strong>
                                            </span>
                                @endif
                            </div>

                            <div class="col-sm-2" style="width: 60px; margin-right:0px; padding-left: 0px; padding-right: 0px;">
                                <input id="car_number2" type="text" class="form-control p-0" name="car_number2"
                                    value="{{ old('car_number2') }}" placeholder="حرف1">

                                @if ($errors->has('car_number2'))
                                    <span class="help-block">
                                                <strong>{{ $errors->first('car_number2') }}</strong>
                                            </span>
                                @endif
                            </div>

                            {{-- <div class="col-xs-6"> --}}



                    </div>
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.color') </label>
                    <input id="color" type="color" class="form-control" name="color"
                           value="{{ old('color') }}" placeholder="@lang('user.color')">

                    @if ($errors->has('color'))
                        <span class="help-block">
                                    <strong>{{ $errors->first('color') }}</strong>
                                </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_front') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_front">
                    @if ($errors->has('car_front'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_front') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_back') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_back">
                    @if ($errors->has('car_back'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_back') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_left') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_left">
                    @if ($errors->has('car_left'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_left') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_right') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_right">
                    @if ($errors->has('car_right'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_right') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_licence_front') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_licence_front">
                    @if ($errors->has('car_licence_front'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_licence_front') }}</strong>
                                    </span>
                    @endif
                </div>

                <div class="form-group col-md-12">
                    <label> @lang('user.car_licence_back') </label>
                    <input required id="car-front-model" type="file" class="form-control" name="car_licence_back">
                    @if ($errors->has('car_licence_back'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('car_licence_back') }}</strong>
                                    </span>
                    @endif
                </div>

                <button type="submit" class="log-teal-btn">
                    @lang('user.rigister')
                </button>


                <p class="helper"> <a href="{{ url('/') }}">@lang('user.back')</a></p>
            </div>
        </form>
    {{-- </div> --}}
@endsection
