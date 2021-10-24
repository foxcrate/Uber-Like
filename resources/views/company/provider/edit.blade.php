@extends('company.layout.base')
@section('title', 'Update Provider ')

@section('content')

<div class="content-area py-1">
    @include('flash::message')
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <a href="{{ route('company.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i>@lang('admin.Back')</a>

            <h2 style="margin-bottom: 2em;">@lang('admin.Update_Provider')</h2>

            <form class="form-horizontal" action="{{route('company.provider.update', $provider->id )}}" method="POST" enctype="multipart/form-data" role="form">
                {{csrf_field()}}
                <div class="form-group row">
                    <label for="first_name" class="col-xs-12 col-form-label">@lang('admin.First_Name')</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="text" value="{{ $provider->first_name }}" name="first_name" required id="first_name" placeholder="@lang('admin.First_Name')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email" class="col-xs-12 col-form-label">@lang('admin.Email')</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="text" value="{{ $provider->email }}" name="email"
                               required id="email" placeholder="@lang('admin.Email')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="last_name" class="col-xs-12 col-form-label">@lang('admin.Last_Name')</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="text" value="{{ $provider->last_name }}" name="last_name" required id="last_name" placeholder="@lang('admin.Last_Name')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="avatar" class="col-xs-12 col-form-label">تحديث  الصورة الشخصية</label>
                    <div class="col-xs-12">
                        @if(! empty($provider->avatar))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"src="{{url('/').$provider->avatar}}">
                        @endif
                        <input type="file" accept="image/*" name="avatar" class=" dropify form-control" aria-describedby="fileHelp">
                    </div>
                </div>



                <div class="form-group row">
                    <label for="mobile" class="col-xs-12 col-form-label">@lang('admin.Mobile')</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="floa" value="{{ $provider->mobile }}" name="mobile" id="mobile" placeholder="@lang('admin.Mobile')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="driver_licence_front" class="col-xs-12 col-form-label">@lang('admin.Driver_Licence Front')</label>
                    <div class="col-xs-12">
                        @if(! empty($provider->driver_licence_front))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->driver_licence_front}}">
                        @endif
                        <input type="file" accept="image/*" name="driver_licence_front" class="dropify form-control" id="driver_licence_front" aria-describedby="fileHelp">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="driver_licence_back" class="col-xs-12 col-form-label">@lang('admin.Driver_Licence Back')</label>
                    <div class="col-xs-12">
                        @if(! empty($provider->driver_licence_back))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->driver_licence_back}}">
                        @endif
                        <input type="file" accept="image/*" name="driver_licence_back" class="dropify form-control" id="driver_licence_back" aria-describedby="fileHelp">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="identity_front" class="col-xs-12 col-form-label">@lang('admin.Identity_Front')</label>
                    <div class="col-xs-12">
                        @if(! empty($provider->identity_front))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->identity_front}}">
                        @endif
                        <input type="file" accept="image/*" name="identity_front" class="dropify form-control" id="identity_front" aria-describedby="fileHelp">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="identity_back" class="col-xs-12 col-form-label">@lang('admin.Identity_back')</label>
                    <div class="col-xs-12">
                        @if(! empty($provider->identity_back))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->identity_back}}">
                        @endif
                        <input type="file" accept="image/*" name="identity_back" class="dropify form-control" id="identity_back" aria-describedby="fileHelp">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="criminal_feat" class="col-xs-12 col-form-label">الفيش الجنائى</label>
                    <div class="col-xs-12">
                        @if(! empty($provider->criminal_feat))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->criminal_feat}}">
                        @endif
                        <input type="file" accept="image/*" name="criminal_feat" class="dropify form-control" id="criminal_feat" aria-describedby="fileHelp">
                    </div>
                </div>



                <div class="form-group row">
                    <label for="drug_analysis_licence" class="col-xs-12 col-form-label"> تحليل الدم</label>
                    <div class="col-xs-12">
                        @if(! empty($provider->drug_analysis_licence))
                            <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/').$provider->drug_analysis_licence}}">
                        @endif
                        <input type="file" accept="image/*" name="drug_analysis_licence" class="dropify form-control" id="drug_analysis_licence" aria-describedby="fileHelp">
                    </div>
                </div>


                <div class="form-group row">
                    <label for="zipcode" class="col-xs-12 col-form-label"></label>
                    <div class="col-xs-12">
                        <button type="submit" class="btn btn-primary">@lang('admin.Update_Provider')</button>
                        <a href="{{route('admin.provider.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
