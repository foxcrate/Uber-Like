@extends('admin.layout.base')

@section('title', 'Add Provider ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            @include('flash::message')
            <div class="box box-block bg-white">
                <a href="{{ route('admin.provider.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i>@lang('admin.Back') </a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Add_Provider')</h5>

                <form class="form-horizontal" action="{{route('admin.provider.store')}}" method="POST"
                      enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    <div class="form-group row">
                        <label for="first_name" class="col-xs-12 col-form-label">@lang('admin.First_Name')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="text" value="{{ old('first_name') }}" name="first_name"
                                   required id="first_name" placeholder="@lang('admin.First_Name')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="last_name" class="col-xs-12 col-form-label">@lang('admin.Last_Name')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="text" value="{{ old('last_name') }}" name="last_name"
                                   required id="last_name" placeholder="@lang('admin.Last_Name')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="email" class="col-xs-12 col-form-label">@lang('admin.Email')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="email" required name="email" value="{{old('email')}}"
                                   id="email" placeholder="@lang('admin.Email')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mobile" class="col-xs-12 col-form-label">@lang('admin.Mobile')</label>
                        <div class="col-md-2">
                            <input value="+2" type="text" placeholder="+2" id="country_code" class="form-control"
                                   name="country_code"/>
                        </div>

                        <div class="col-md-8">
                            <input type="text" autofocus id="phone_number" class="form-control"
                                   placeholder=@lang('admin.Mobile')
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
                        <div class="col-xs-10">
                            <input class="form-control" type="password" name="password" id="password" required
                                   placeholder="@lang('admin.Password')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="password_confirmation"
                               class="col-xs-12 col-form-label">@lang('admin.Password Confirmation')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="password" name="password_confirmation"
                                   id="password_confirmation" placeholder="@lang('admin.Re_type_Password')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="governorate_id"
                               class="col-xs-12 col-form-label">{{trans('admin.governorate_id')}}</label>
                        <div class="col-xs-10">
                            <select required class="form-control select2" id="governorate_id" name="governorate_id">
                                <option value="0">@lang('admin.You must choose the governorate model')</option>
                                @foreach($governorates as $index)
                                    @if(app()->getLocale()=="en")
                                        <option value="{{ $index->id}}">{{ $index->name_en }}</option>
                                    @else
                                        <option value="{{ $index->id}}">{{ $index->name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="avatar" class="col-xs-12 col-form-label">الصور الشخصية</label>
                        <div class="col-xs-10">
                            <input type="file" required accept="image/*" name="avatar"
                                   class="dropify form-control-file" aria-describedby="fileHelp">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-xs-12 col-form-label" for="fleet"> إسم الشركة</label>
                        <div class="col-xs-10">
                            <select required class="form-control" id="fleet" name="fleet">
                                <option selected value="0">لايوجد</option>
                                @foreach($fleet as $index)
                                    <option value="{{ $index->id}}">{{ $index->name }} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="identity_number" class="col-xs-12 col-form-label">@lang('user.profile.identity_number')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="number" name="identity_number" value="{{old('identity_number')}}"
                                   id="identity_number" placeholder="@lang('user.profile.identity_number')">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="identity_front"
                               class="col-xs-12 col-form-label">@lang('admin.Identity_Front')</label>
                        <div class="col-xs-10">
                            <input required type="file" accept="image/*" name="identity_front"
                                   class="dropify form-control-file" id="identity_front"
                                   aria-describedby="fileHelp">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="identity_back"
                               class="col-xs-12 col-form-label">@lang('admin.Identity_back')</label>
                        <div class="col-xs-10">
                            <input required type="file" accept="image/*" name="identity_back"
                                   class="dropify form-control-file" id="identity_back" aria-describedby="fileHelp">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="driver_licence_front"
                               class="col-xs-12 col-form-label">@lang('admin.Driver_Licence Front')</label>
                        <div class="col-xs-10">
                            <input required type="file" accept="image/*" name="driver_licence_front"
                                   class="dropify form-control-file" id="driver_licence front"
                                   aria-describedby="fileHelp">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="driver_licence_back"
                               class="col-xs-12 col-form-label">@lang('admin.Driver_Licence Back')</label>
                        <div class="col-xs-10">
                            <input required type="file" accept="image/*" name="driver_licence_back"
                                   class="dropify form-control-file" id="driver_licence back"
                                   aria-describedby="fileHelp">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="criminal_feat" class="col-xs-12 col-form-label">الفيش الجنائى</label>
                        <div class="col-xs-10">
                            <input required type="file" accept="image/*" name="criminal_feat"
                                   class="dropify form-control-file" id="criminal_feat" aria-describedby="fileHelp">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for=" drug_analysis_licence " class="col-xs-12 col-form-label">تحليل الدم</label>
                        <div class="col-xs-10">
                            <input required type="file" accept="image/*" name="drug_analysis_licence"
                                   class="dropify form-control-file" id="drug_analysis_licence"
                                   aria-describedby="fileHelp">
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
                        <label for="zipcode" class="col-xs-12 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">@lang('admin.Add_Provider')</button>
                            <a href="{{route('admin.provider.index')}}"
                               class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
