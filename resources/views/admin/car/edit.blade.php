@extends('admin.layout.base')
@inject('models', 'App\CarModel')

@inject('provider', App\Provider)

<?php
$providers = $provider->pluck('email', 'id')->toArray();
?>

@section('title', 'Update Admin')

@section('content')

    <div class="content-area py-1">
        @include('flash::message')
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.car.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Update Car')</h5>

                {!! Form::model($model, ['action' => ['Resource\CarController@update',$model->id], 'method' =>'put', 'files' => true]) !!}
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">

                <div class="form-group row">
                    <label for="car_model_id" class="col-xs-2 col-form-label">{{trans('admin.car_model_id')}}</label>
                    <div class="col-xs-10">
                        {!! Form::select('car_model_id',$models->pluck('name', 'id') , old('car_model_id'),
                             ['class'=>'form-control select2', 'placeholder' => '..............']) !!}
                    </div>
                    @if ($errors->has('car_model_id'))
                        <span class="help-block">
							<strong>{{ $errors->first('car_model_id')}}</strong>
						</span>
                    @endif
                </div>
{{--                <div class="form-group col-md-12">--}}
{{--                    <select class="form-control" name="service_type_id" id="service_type_id">--}}
{{--                        <option value="0">@lang('user.chose_car')</option>--}}
{{--                        @foreach( $car_models as $type)--}}
{{--                            <option value="{{$type->id}}">{{$type->name}} - {{$type->date}}</option>--}}
{{--                        @endforeach--}}
{{--                    </select>--}}
{{--                    @if ($errors->has('service_type_id'))--}}
{{--                        <span class="help-block">--}}
{{--                                    <strong>{{ $errors->first('service_type_id')}}</strong>--}}
{{--                                </span>--}}
{{--                    @endif--}}
{{--                </div>--}}

                <div class="form-group row">
                    <label for="provider_list" class="col-xs-2">{{trans('admin.provider_list')}}</label>
                    <div class="col-xs-10">
                        {!! Form::select('provider_list[]',$providers,null, [
                            'class'=>'select2 js-states form-control',
                            'multiple' => 'multiple',
                        ]) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-xs-2 col-form-label" for="fleet_id">{{trans('admin.fleet_id')}}</label>
                    <div class="col-xs-10">
                        <select required class="form-control select2" id="fleet_id" name="fleet_id">
                            <option value="0">لايوجد</option>
                            @foreach($fleet as $index)
                                <option value="{{ $index->id}}" {{ $index->id == $model->fleet_id ? 'selected' : '' }}>{{ $index->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('fleet_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('fleet_id')}}</strong>
                        </span>
                    @endif
                </div>

                {{-- <div class="form-group row">
                    <label for="car_number" class="col-xs-2 col-form-label">@lang('admin.car_number')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $model->car_number }}" name="car_number" required
                               id="car_number" placeholder="@lang('admin.car_number')">
                    </div>
                </div> --}}

                <div class="form-group row">
                    <label for="car_number" class="col-xs-2 col-form-label">@lang('admin.car_number')</label>
                    <div class="col-xs-6">
                        <input class="form-control" type="number" name="car_number1"
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
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $model->color }}" name="color" required
                               id="color" placeholder="@lang('admin.color')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_front" class="col-xs-2 col-form-label">@lang('admin.car_front')</label>
                    <div class="col-xs-10">
                        @if(isset($model->car_front))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{asset($model->car_front)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_front" class="" id="car_front" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_back" class="col-xs-2 col-form-label">@lang('admin.car_back')</label>
                    <div class="col-xs-10">
                        @if(isset($model->car_back))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{asset($model->car_back)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_back" class="dropify form-control-file" id="car_back" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_left" class="col-xs-2 col-form-label">@lang('admin.car_left')</label>
                    <div class="col-xs-10">
                        @if(isset($model->car_left))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{asset($model->car_left)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_left" class="dropify form-control-file" id="car_left" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_right" class="col-xs-2 col-form-label">@lang('admin.car_right')</label>
                    <div class="col-xs-10">
                        @if(isset($model->car_right))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{asset($model->car_right)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_right" class="dropify form-control-file" id="car_right" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_licence_front" class="col-xs-2 col-form-label">@lang('admin.car_licence_front')</label>
                    <div class="col-xs-10">
                        @if(isset($model->car_licence_front))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{asset($model->car_licence_front)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_licence_front" class="dropify form-control-file" id="car_licence_front" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_licence_back" class="col-xs-2 col-form-label">@lang('admin.car_licence_back')</label>
                    <div class="col-xs-10">
                        @if(isset($model->car_licence_back))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{asset($model->car_licence_back)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_licence_back" class="dropify form-control-file" id="car_licence_back" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="zipcode" class="col-xs-2 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">@lang('admin.Update Account Manager')</button>
                        <a href="{{route('admin.admin.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
