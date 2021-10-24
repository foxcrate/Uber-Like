@extends('admin.layout.base')
@inject('models', 'App\CarModel')

@section('title', trans('admin.Update Governorate'))

@section('content')

    <div class="content-area py-1">
        @include('flash::message')
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.governorate.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Update Governorate')</h5>

                {!! Form::model($model, ['action' => ['GovernorateController@update',$model->id], 'method' =>'put', 'files' => true]) !!}
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">

                <div class="form-group row">
                    <label for="name" class="col-xs-2 col-form-label">@lang('admin.name_ar')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $model->name }}" name="name" required
                               id="name" placeholder="@lang('admin.name_ar')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="name_en" class="col-xs-2 col-form-label">@lang('admin.name_en')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $model->name_en }}" name="name_en" required
                               id="name_en" placeholder="@lang('admin.name_en')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="zipcode" class="col-xs-2 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">@lang('admin.Update Governorate')</button>
                        <a href="{{route('admin.governorate.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
