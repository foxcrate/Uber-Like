@extends('company.layout.base')
@inject('models', 'App\CarModel')

@inject('model', App\Provider)
@inject('provider', App\Provider)

<?php
$providers = $provider->pluck('email', 'id')->toArray();
?>

@section('title', 'Add Car for Company')

@section('content')

    <div class="content-area py-1">
        @include('flash::message')

                <a href="{{ route('company.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Add Car')</h5>

                {!! Form::model($model, ['action' => 'Company\CompanyController@postAddCar', 'files' => true]) !!}

                <div class="form-group row">
                    <label class="col-xs-2 col-form-label" for="car_model_id">{{trans('admin.car_model_id')}}</label>
                    <div class="col-xs-12">
                        <select required class="form-control" id="car_model_id" name="car_model_id">
                            <option value="0">@lang('admin.You must choose the car model')</option>
                            @foreach($car_models as $index)
                                <option value="{{ $index->id}}">{{ $index->name }} - {{ $index->date }} </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('car_model_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('car_model_id')}}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="provider_list" class="col-xs-2">{{trans('admin.provider_list')}}</label>
                    <div class="col-xs-12">
                        {!! Form::select('provider_list[]',$providers,null, [
                            'class'=>'select2 form-control',
                            'multiple' => 'multiple',
                        ]) !!}
                    </div>
                </div>

                {{-- <div class="form-group row">
                    <label for="car_number" class="col-xs-2 col-form-label">@lang('admin.car_number')</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="text" name="car_number" required
                               id="car_number" placeholder="@lang('admin.car_number')">
                    </div>
                </div> --}}

                <div class="form-group row">
                    <label for="car_number" class="col-xs-2 col-form-label">@lang('admin.car_number')</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="number" name="car_number1"required
                        id="car_number1" placeholder="@lang('user.numbersInCarNumber')">

                        @if ($errors->has('car_number1'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('car_number1') }}</strong>
                                        </span>
                        @endif
                    </div>

                    <div class="col-xs-1" style="margin-right:20px; padding-left: 0px; padding-right: 0px;">
                        <input id="car_number4" class="form-control" type="text" name="car_number4"
                            value="{{ old('car_number4') }}" placeholder="@lang('user.thirdCharInCarNumber')">

                        @if ($errors->has('car_number4'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('car_number4') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="col-xs-1" style="margin-right:20px; padding-left: 0px; padding-right: 0px;">
                        <input id="car_number3" class="form-control" type="text" name="car_number3"
                            value="{{ old('car_number3') }}" placeholder="@lang('user.secondCharInCarNumber')">

                        @if ($errors->has('car_number3'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('car_number3') }}</strong>
                                    </span>
                        @endif
                    </div>

                    <div class="col-xs-1" style="margin-right:20px; padding-left: 0px; padding-right: 0px;">
                        <input id="car_number2" class="form-control" type="text" name="car_number2"
                            value="{{ old('car_number2') }}" placeholder="@lang('user.firstCharInCarNumber')">

                        @if ($errors->has('car_number2'))
                            <span class="help-block">
                                        <strong>{{ $errors->first('car_number2') }}</strong>
                                    </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="color" class="col-xs-2 col-form-label">@lang('admin.color')</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="color" name="color" required
                               id="color" placeholder="@lang('admin.color')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_front" class="col-xs-2 col-form-label">@lang('admin.car_front')</label>
                    <div class="col-xs-12">

                        <input required type="file" accept="image/*" name="car_front" class="dropify form-control" id="car_front" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_back" class="col-xs-2 col-form-label">@lang('admin.car_back')</label>
                    <div class="col-xs-12">

                        <input required type="file" accept="image/*" name="car_back" class="dropify form-control" id="car_back" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_left" class="col-xs-2 col-form-label">@lang('admin.car_left')</label>
                    <div class="col-xs-12">

                        <input required type="file" accept="image/*" name="car_left" class="dropify form-control" id="car_left" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_right" class="col-xs-2 col-form-label">@lang('admin.car_right')</label>
                    <div class="col-xs-12">

                        <input required type="file" accept="image/*" name="car_right" class="dropify form-control" id="car_right" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_licence_front" class="col-xs-2 col-form-label">@lang('admin.car_licence_front')</label>
                    <div class="col-xs-12">

                        <input required type="file" accept="image/*" name="car_licence_front" class="dropify form-control" id="car_licence_front" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_licence_back" class="col-xs-2 col-form-label">@lang('admin.car_licence_back')</label>
                    <div class="col-xs-12">

                        <input required type="file" accept="image/*" name="car_licence_back" class="dropify form-control" id="car_licence_back" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="zipcode" class="col-xs-2 col-form-label"></label>
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary">@lang('admin.Add Car')</button>
                        <a href="{{route('admin.admin.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
                    </div>
                </div>
                {!! Form::close() !!}

    </div>

@endsection
