@extends('provider.layout.app')

@section('content')
    <div class="pro-dashboard-head">
        <div class="container">
            <a href="{{url('provider/earnings')}}" class="pro-head-link">@lang('user.Payment Statements')</a>
            <a href="{{url('provider/upcoming')}}" class="pro-head-link">@lang('user.Upcoming')</a>
            <a href="{{url('provider/new_car')}}" class="pro-head-link active">@lang('user.New Car')</a>
            <a href="{{url('provider/trips')}}" class="pro-head-link">@lang('user.my_trips')</a>

        </div>
    </div>



     {{-- <div class="form-group col-md-12">
                    <label> @lang('user.number_car') </label>
                    <input id="car_number" type="text" class="form-control" name="car_number"
                           value="{{ old('car_number') }}" required placeholder="@lang('user.number_car')">

                    @if ($errors->has('car_number'))
                        <span class="help-block">
                            <strong>{{ $errors->first('car_number') }}</strong>
                        </span>
                    @endif
                </div> --}}

                {{-- <div class="col-xs-6"> --}}




    <div class="pro-dashboard-content container">
        {{-- <form class="form-horizontal" role="form" method="POST" action="{{ url('/provider/new_car') }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="text-center">
                <h3>@lang('user.Add New Car')</h3>
            </div>

            <div id="second_step" style="margin-top: 25px">
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

                    <div>
                        <div class="col-sm-6" style="padding-left: 0px">
                            <input id="car_number1" type="number" class="form-control p-0" name="car_number1"
                            value="{{ old('car_number1') }}" placeholder="@lang('user.numbersInCarNumber')">

                            @if ($errors->has('car_number1'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('car_number1') }}</strong>
                                        </span>
                            @endif
                        </div>

                        <div class="col-sm-1" style="margin-right:20px; padding-left: 0px; padding-right: 0px;">
                            <input id="car_number4" type="text" class="form-control p-0" name="car_number4"
                                value="{{ old('car_number4') }}" placeholder="@lang('user.thirdCharInCarNumber')">

                            @if ($errors->has('car_number4'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('car_number4') }}</strong>
                                        </span>
                            @endif
                        </div>

                        <div class="col-sm-1" style="margin-right:20px; padding-left: 0px; padding-right: 0px;">
                            <input id="car_number3" type="text" class="form-control p-0" name="car_number3"
                                value="{{ old('car_number3') }}" placeholder="@lang('user.secondCharInCarNumber')">

                            @if ($errors->has('car_number3'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('car_number3') }}</strong>
                                        </span>
                            @endif
                        </div>

                        <div class="col-sm-1" style="margin-right:20px; padding-left: 0px; padding-right: 0px;">
                            <input id="car_number2" type="text" class="form-control p-0" name="car_number2"
                                value="{{ old('car_number2') }}" placeholder="@lang('user.firstCharInCarNumber')">

                            @if ($errors->has('car_number2'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('car_number2') }}</strong>
                                        </span>
                            @endif
                        </div>


                    </div>
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

                <button type="submit" class="btn btn-primary btn-lg" style="margin-bottom: 20px">
                    @lang('user.Add')
                </button>
            </div>
        </form> --}}
        <div class="text-center">
            <div id="second_step" style="margin-top: 25px">
                <div class="form-group col-md-12">
                    <h1> .. إنتظرونا  قريبا</h1>
                </div>
            </div>
        </div>
    </div>
@endsection
