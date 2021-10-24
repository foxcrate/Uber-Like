@extends('admin.layout.base')

@section('title', 'Update Provider ')

@section('content')

<div class="content-area py-1">
    @include('flash::message')
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('admin.provider.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i>@lang('admin.Back')</a>

            <h5 style="margin-bottom: 2em;">@lang('admin.Update_Provider')</h5>

            <form class="form-horizontal" action="{{route('admin.provider.update', $provider->id )}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">
                <div class="form-group row">
                    <label for="first_name" class="col-xs-12 col-form-label">@lang('admin.First_Name')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{base64_decode( $provider->first_name) }}" name="first_name" required id="first_name" placeholder="@lang('admin.First_Name')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="last_name" class="col-xs-12 col-form-label">@lang('admin.Last_Name')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{base64_decode( $provider->last_name) }}" name="last_name" required id="last_name" placeholder="@lang('admin.Last_Name')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-xs-12 col-form-label">@lang('admin.Email')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="text" value="{{ $provider->email }}" name="email"
                               required id="email" placeholder="@lang('admin.Email')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="mobile" class="col-xs-12 col-form-label">@lang('admin.Mobile')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="floa" value="{{ $provider->mobile }}" name="mobile" id="mobile" placeholder="@lang('admin.Mobile')">
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
                                    <option value="{{ $index->id}}" {{ $index->id == $provider->governorate_id ? 'selected' : '' }}>{{ $index->name_en }}</option>
                                @else
                                    <option value="{{ $index->id}}" {{ $index->id == $provider->governorate_id ? 'selected' : '' }}>{{ $index->name }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>


                <div class="form-group row">
                    <label class="col-xs-12 col-form-label" for="fleet">تعديل إسم الشركة</label>
                    <div class="col-xs-10">
                        <select required class="form-control" id="fleet" name="fleet">
                            <option selected  value="0">لايوجد</option>
                            @foreach($fleet as $index)
                                <option value="{{ $index->id}}" {{ $index->id == $provider->fleet ? 'selected' : '' }}>{{ $index->name }} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="avatar" class="col-xs-12 col-form-label">تحديث  الصورة الشخصية</label>
                    <div class="col-xs-10">
                        @if(! empty($provider->avatar))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"src="{{url('/').$provider->avatar}}">
                        @endif
                        <input type="file" accept="image/*" name="avatar" class=" dropify form-control-file" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="identity_number" class="col-xs-12 col-form-label">@lang('user.profile.identity_number')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="number" name="identity_number" value="{{ $provider->identity_number }}"
                               id="identity_number" placeholder="@lang('user.profile.identity_number')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="identity_front" class="col-xs-12 col-form-label">@lang('admin.Identity_Front')</label>
                    <div class="col-xs-10">
                        @if(! empty($provider->identity_front))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->identity_front}}">
                        @endif
                        <input type="file" accept="image/*" name="identity_front" class="dropify form-control-file" id="identity_front" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="identity_back" class="col-xs-12 col-form-label">@lang('admin.Identity_back')</label>
                    <div class="col-xs-10">
                        @if(! empty($provider->identity_back))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->identity_back}}">
                        @endif
                        <input type="file" accept="image/*" name="identity_back" class="dropify form-control-file" id="identity_back" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="driver_licence_front" class="col-xs-12 col-form-label">@lang('admin.Driver_Licence Front')</label>
                    <div class="col-xs-10">
                        @if(! empty($provider->driver_licence_front))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->driver_licence_front}}">
                        @endif
                        <input type="file" accept="image/*" name="driver_licence_front" class="dropify form-control-file" id="driver_licence_front" aria-describedby="fileHelp">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="driver_licence_back" class="col-xs-12 col-form-label">@lang('admin.Driver_Licence Back')</label>
                    <div class="col-xs-10">
                        @if(! empty($provider->driver_licence_back))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->driver_licence_back}}">
                        @endif
                        <input type="file" accept="image/*" name="driver_licence_back" class="dropify form-control-file" id="driver_licence_back" aria-describedby="fileHelp">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="criminal_feat" class="col-xs-12 col-form-label">الفيش الجنائى</label>
                    <div class="col-xs-10">
                        @if(! empty($provider->criminal_feat))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->criminal_feat}}">
                        @endif
                        <input type="file" accept="image/*" name="criminal_feat" class="dropify form-control-file" id="criminal_feat" aria-describedby="fileHelp">
                    </div>
                </div>



                <div class="form-group row">
                    <label for="drug_analysis_licence" class="col-xs-12 col-form-label"> تحليل الدم</label>
                    <div class="col-xs-10">
                        @if(! empty($provider->drug_analysis_licence))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->drug_analysis_licence}}">
                        @endif
                        <input type="file" accept="image/*" name="drug_analysis_licence" class="dropify form-control-file" id="drug_analysis_licence" aria-describedby="fileHelp">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="zipcode" class="col-xs-12 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">@lang('admin.Update_Provider')</button>
                        <a href="{{route('admin.provider.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
