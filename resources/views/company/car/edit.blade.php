@extends('company.layout.base')
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
                <a href="{{ route('company.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h2 style="margin-bottom: 2em;">@lang('admin.updateCar')</h2>

                {!! Form::model($model, ['action' => ['Company\CompanyController@updateCar',$model->id], 'method' =>'POST', 'files' => true]) !!}
                {{csrf_field()}}

                <div class="form-group row">
                    <label for="car_model_id" class="col-xs-2 col-form-label">{{trans('admin.car_model_id')}}</label>
                    <div class="col-xs-10">
                        <select class="form-control" name="car_model_id" id="car_model_id">
                            <option value="0">@lang('user.chose_car')</option>
                            @foreach( $car_models as $type)
                                <option value="{{$type->id}}" {{ $type->id == $model->car_model_id ? 'selected' : '' }}>{{$type->name}}
                                    - {{$type->date}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('car_model_id'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('car_model_id')}}</strong>
                                </span>
                        @endif
                    </div>
                </div>

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
                    <label for="car_number" class="col-xs-2 col-form-label">@lang('admin.car_number')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $model->car_number }}" name="car_number"
                               required
                               id="car_number" placeholder="@lang('admin.car_number')">
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
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                                 src="{{asset($model->car_front)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_front" class="dropify form-control" id="car_front"
                               aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_back" class="col-xs-2 col-form-label">@lang('admin.car_back')</label>
                    <div class="col-xs-10">
                        @if(isset($model->car_back))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                                 src="{{asset($model->car_back)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_back" class="dropify form-control" id="car_back"
                               aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_left" class="col-xs-2 col-form-label">@lang('admin.car_left')</label>
                    <div class="col-xs-10">
                        @if(isset($model->car_left))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                                 src="{{asset($model->car_left)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_left" class="dropify form-control" id="car_left"
                               aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_right" class="col-xs-2 col-form-label">@lang('admin.car_right')</label>
                    <div class="col-xs-10">
                        @if(isset($model->car_right))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                                 src="{{asset($model->car_right)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_right" class="dropify form-control" id="car_right"
                               aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_licence_front"
                           class="col-xs-2 col-form-label">@lang('admin.car_licence_front')</label>
                    <div class="col-xs-10">
                        @if(isset($model->car_licence_front))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                                 src="{{asset($model->car_licence_front)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_licence_front" class="dropify form-control"
                               id="car_licence_front" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="car_licence_back"
                           class="col-xs-2 col-form-label">@lang('admin.car_licence_back')</label>
                    <div class="col-xs-10">
                        @if(isset($model->car_licence_back))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                                 src="{{asset($model->car_licence_back)}}">
                        @endif
                        <input type="file" accept="image/*" name="car_licence_back" class="dropify form-control"
                               id="car_licence_back" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    {{--                    <label for="zipcode" class="col-xs-12 col-form-label"></label>--}}
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary">@lang('admin.Update_Provider')</button>
                        {{--                        <a href="{{route('admin.provider.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>--}}
                    </div>
                </div>
                {{--                </form>--}}
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
