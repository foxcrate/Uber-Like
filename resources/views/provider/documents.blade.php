@extends('provider.layout.app')

@section('content')
    <div class="pro-dashboard-head">
        <div class="container">
            <a href="{{ route('provider.profile.index') }}" class="pro-head-link">@lang('user.Profile')</a>
            <a href="#" class="pro-head-link active">@lang('user.Update Documents')</a>
            <a href="{{ route('provider.cars') }}" class="pro-head-link">@lang('user.My Cars')</a>
            <a href="{{ route('provider.location.index') }}" class="pro-head-link">@lang('user.Update Location')</a>
        </div>
    </div>

    <div class="pro-dashboard-content container">
        <form class="form-horizontal" role="form" method="POST" action="{{ url(route('provider.post.documents')) }}"
              enctype="multipart/form-data">
            {{ csrf_field() }}

            <div class="text-center">
                <h3>@lang('user.Update Documents')</h3>
            </div>

            <label> @lang('user.avatar') </label>
            <div>
                @if(isset(auth('provider')->user()->avatar))
                    <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                         src="{{asset(auth('provider')->user()->avatar)}}">
                @endif
            </div>
            <input id="car-front-model" type="file" class="form-control" name="avatar">
            @if ($errors->has('avatar'))
                <span class="help-block">
                            <strong>{{ $errors->first('avatar') }}</strong>
                        </span>
            @endif

            <label> @lang('user.Driver Licence Front') </label>
            <div>
                @if(isset(auth('provider')->user()->driver_licence_front))
                    <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                         src="{{asset(auth('provider')->user()->driver_licence_front)}}">
                @endif
            </div>
            <input id="car-front-model" type="file" class="form-control" name="driver_licence_front">
            @if ($errors->has('driver_licence_front'))
                <span class="help-block">
                            <strong>{{ $errors->first('driver_licence_front') }}</strong>
                        </span>
            @endif

            <label> @lang('user.Driver Licence Back') </label>
            <div>
                @if(isset(auth('provider')->user()->driver_licence_back))
                    <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                         src="{{asset(auth('provider')->user()->driver_licence_back)}}">
                @endif
            </div>
            <input id="car-front-model" type="file" class="form-control" name="driver_licence_back">
            @if ($errors->has('driver_licence_back'))
                <span class="help-block">
                        <strong>{{ $errors->first('driver_licence_back') }}</strong>
                    </span>
            @endif

            <label>@lang('user.Identity Front') </label>
            <div>
                @if(isset(auth('provider')->user()->identity_front))
                    <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                         src="{{asset(auth('provider')->user()->identity_front)}}">
                @endif
            </div>
            <input id="car-front-model" type="file" class="form-control" name="identity_front">
            @if ($errors->has('identity_front'))
                <span class="help-block">
                        <strong>{{ $errors->first('identity_front') }}</strong>
                    </span>
            @endif

            <label> @lang('user.Identity Back') </label>
            <div>
                @if(isset(auth('provider')->user()->identity_back))
                    <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                         src="{{asset(auth('provider')->user()->identity_back)}}">
                @endif
            </div>
            <input id="car-front-model" type="file" class="form-control" name="identity_back">
            @if ($errors->has('identity_back'))
                <span class="help-block">
                                        <strong>{{ $errors->first('identity_back') }}</strong>
                                    </span>
            @endif

            <label> @lang('user.criminal_feat') </label>
            <div>
                @if(isset(auth('provider')->user()->criminal_feat))
                    <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                         src="{{asset(auth('provider')->user()->criminal_feat)}}">
                @endif
            </div>
            <input id="car-front-model" type="file" class="form-control" name="criminal_feat">
            @if ($errors->has('criminal_feat'))
                <span class="help-block">
                                        <strong>{{ $errors->first('criminal_feat') }}</strong>
                                    </span>
            @endif

            <label> @lang('user.analysis_licence') </label>
            <div>
                @if(isset(auth('provider')->user()->drug_analysis_licence))
                    <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"
                         src="{{asset(auth('provider')->user()->drug_analysis_licence)}}">
                @endif
            </div>
            <input id="car-front-model" type="file" class="form-control" name="drug_analysis_licence">
            @if ($errors->has('drug_analysis_licence'))
                <span class="help-block">
                        <strong>{{ $errors->first('drug_analysis_licence') }}</strong>
                    </span>
            @endif
            <br>
            <button type="submit" class="btn btn-primary btn-lg" style="margin-bottom: 20px">
                @lang('user.Update')
            </button>

        </form>

    </div>
@endsection
