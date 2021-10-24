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
        <p>{{ $errors }}</p>

        <a href="{{ route('company.index') }}" class="btn btn-default pull-right"><i
                    class="fa fa-angle-left"></i> @lang('admin.Back')</a>

        <h2 style="margin-bottom: 2em;">@lang('admin.Add Provider')</h2>

        {!! Form::model($model, ['action' => 'Company\CompanyController@postAddProvider', 'files' => true]) !!}

        <div class="form-group row">
            <label for="first_name" class="col-xs-12 col-form-label">@lang('admin.First_Name')</label>
            <div class="col-xs-12">
                <input class="form-control" type="text" value="{{ old('first_name') }}" name="first_name" required
                       id="first_name" placeholder="@lang('admin.First_Name')">
            </div>
            @if ($errors->has('first_name'))
                <span class="help-block">
                        <strong>{{ $errors->first('first_name') }}</strong>
                    </span>
            @endif
        </div>

        <div class="form-group row">
            <label for="last_name" class="col-xs-12 col-form-label">@lang('admin.Last_Name')</label>
            <div class="col-xs-12">
                <input class="form-control" type="text" value="{{ old('last_name') }}" name="last_name" required
                       id="last_name" placeholder="@lang('admin.Last_Name')">
            </div>
            @if ($errors->has('last_name'))
                <span class="help-block">
                        <strong>{{ $errors->first('last_name') }}</strong>
                    </span>
            @endif
        </div>

        <div class="form-group row">
            <label for="email" class="col-xs-12 col-form-label">@lang('admin.Email')</label>
            <div class="col-xs-12">
                <input class="form-control" type="email" required name="email" value="{{old('email')}}" id="email"
                       placeholder="@lang('admin.Email')">
            </div>
            @if ($errors->has('email'))
                <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
            @endif
        </div>

        <div class="form-group row">
            <label for="mobile" class="col-xs-12 col-form-label">@lang('admin.Mobile')</label>
            <div class="col-md-2">
                <input disabled value="+2" type="text" placeholder="+2" id="country_code1" class="form-control"
                       name="country_code1"/>
                <input type='hidden' value="+2"  placeholder="+2" id="country_code" class="form-control"
                name="country_code"/>
            </div>

            <div class="col-md-10">
                <input type="text" autofocus id="phone_number" class="form-control" placeholder="@lang('admin.Mobile')"
                       name="phone_number" value="{{ old('phone_number') }}"/>
            </div>

            @if ($errors->has('phone_number'))
                <span class="help-block">
                        <strong>{{ $errors->first('phone_number') }}</strong>
                    </span>
            @endif
        </div>

        <div class="form-group row">
            <label for="password" class="col-xs-12 col-form-label">@lang('admin.Password')</label>
            <div class="col-xs-12">
                <input class="form-control" type="password" name="password" id="password" required
                       placeholder="@lang('admin.Password')">
            </div>
            @if ($errors->has('password'))
                <span class="help-block">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
            @endif
        </div>

        <div class="form-group row">
            <label for="password_confirmation"
                   class="col-xs-12 col-form-label">@lang('admin.Password Confirmation')</label>
            <div class="col-xs-12">
                <input class="form-control" type="password" name="password_confirmation" id="password_confirmation"
                       placeholder="@lang('admin.Re_type_Password')">
            </div>
        </div>

        <div class="form-group row">
            <label for="avatar" class="col-xs-12 col-form-label">الصور الشخصية</label>
            <div class="col-xs-12">
                <input type="file" required accept="image/*" name="avatar" class="dropify form-control-file form-control"
                       aria-describedby="fileHelp">
            </div>
        </div>

        <div class="form-group row">
            <label for="driver_licence_front"
                   class="col-xs-12 col-form-label">@lang('admin.Driver_Licence Front')</label>
            <div class="col-xs-12">
                <input  type="file" accept="image/*" name="driver_licence_front"
                       class="dropify form-control-file form-control" id="driver_licence front" aria-describedby="fileHelp">
            </div>
        </div>


        <div class="form-group row">
            <label for="driver_licence_back" class="col-xs-12 col-form-label">@lang('admin.Driver_Licence Back')</label>
            <div class="col-xs-12">
                <input  type="file" accept="image/*" name="driver_licence_back"
                       class="dropify form-control-file form-control" id="driver_licence back" aria-describedby="fileHelp">
            </div>
        </div>

        <div class="form-group row">
            <label for="identity_front" class="col-xs-12 col-form-label">@lang('admin.Identity_Front')</label>
            <div class="col-xs-12">
                <input  type="file" accept="image/*" name="identity_front" class="dropify form-control-file form-control"
                       id="identity_front" aria-describedby="fileHelp">
            </div>
        </div>

        <div class="form-group row">
            <label for="identity_back" class="col-xs-12 col-form-label">@lang('admin.Identity_back')</label>
            <div class="col-xs-12">
                <input  type="file" accept="image/*" name="identity_back" class="dropify form-control-file form-control"
                       id="identity_back" aria-describedby="fileHelp">
            </div>
        </div>


        <div class="form-group row">
            <label for="criminal_feat" class="col-xs-12 col-form-label">الفيش الجنائى</label>
            <div class="col-xs-12">
                <input  type="file" accept="image/*" name="criminal_feat" class="dropify form-control-file form-control"
                       id="criminal_feat" aria-describedby="fileHelp">
            </div>
        </div>


        <div class="form-group row">
            <label for=" drug_analysis_licence " class="col-xs-12 col-form-label">تحليل الدم</label>
            <div class="col-xs-12">
                <input  type="file" accept="image/*" name="drug_analysis_licence"
                       class="dropify form-control-file form-control" id="drug_analysis_licence" aria-describedby="fileHelp">
            </div>
        </div>

        <div class="form-group  text-center col-md-12">
            <div style="font-size: 15px" class="pr-5 col-md-6">
                {!! Form::radio('car_type', 'true', true,['id' => 'accept']) !!} @lang('user.A car already exists')
            </div>
            <div style="font-size: 15px" class="pr-5 col-md-6">
                {!! Form::radio('car_type', 'false', null,['id' => 'desaple']) !!} @lang('user.private car')
            </div>
        </div>

        <div class="form-group row">
            <label for="zipcode" class="col-xs-2 col-form-label"></label>
            <div class="col-xs-12">
                <button type="submit" class="btn btn-primary">@lang('admin.Add Provider')</button>
                <a href="{{route('admin.admin.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
            </div>
        </div>
        {!! Form::close() !!}

    </div>

@endsection
