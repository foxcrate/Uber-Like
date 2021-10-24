@extends('provider.layout.auth')

@section('content')
    @include('flash::message')
    <div class="col-md-12">
        <a class="log-blk-btn" href="{{ url('/provider/login') }}">@lang('user.already_registered')</a>
        <h3>@lang('user.sing_up')</h3>
    </div>

    <div class="col-md-12">
        <form class="form-horizontal" role="form" method="POST"
              action="{{ url('/provider/image-upload',[$provider->id,$provider->id_url]) }}"
              enctype="multipart/form-data">

            {{ csrf_field() }}

            <div id="second_step">
                @if($provider->avatar == null)
                    <label> @lang('user.avatar') </label>
                    <input id="car-front-model" type="file" class="form-control" name="avatar" required>
                    @if ($errors->has('avatar'))
                        <span class="help-block">
                            <strong>{{ $errors->first('avatar') }}</strong>
                        </span>
                    @endif
                @endif
                @if($provider->driver_licence_front == null)
                    <label> @lang('user.Driver Licence Front') </label>
                    <input id="car-front-model" type="file" class="form-control" name="driver_licence_front" required>
                    @if ($errors->has('driver_licence_front'))
                        <span class="help-block">
                            <strong>{{ $errors->first('driver_licence_front') }}</strong>
                        </span>
                    @endif
                @endif
                @if($provider->driver_licence_back == null)
                    <label> @lang('user.Driver Licence Back') </label>
                    <input id="car-front-model" type="file" class="form-control" name="driver_licence_back" required>
                    @if ($errors->has('driver_licence_back'))
                        <span class="help-block">
                        <strong>{{ $errors->first('driver_licence_back') }}</strong>
                    </span>
                    @endif
                @endif
                @if($provider->identity_front == null)
                    <label>@lang('user.Identity Front') </label>
                    <input id="car-front-model" type="file" class="form-control" name="identity_front" required>
                    @if ($errors->has('identity_front'))
                        <span class="help-block">
                        <strong>{{ $errors->first('identity_front') }}</strong>
                    </span>
                    @endif
                @endif
                @if($provider->identity_back == null)
                    <label> @lang('user.Identity Back') </label>
                    <input id="car-front-model" type="file" class="form-control" name="identity_back" required>
                    @if ($errors->has('identity_back'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('identity_back') }}</strong>
                                    </span>
                    @endif
                @endif
                @if($provider->criminal_feat == null)
                    <label> @lang('user.criminal_feat') </label>
                    <input id="car-front-model" type="file" class="form-control" name="criminal_feat" required>
                    @if ($errors->has('criminal_feat'))
                        <span class="help-block">
                                        <strong>{{ $errors->first('criminal_feat') }}</strong>
                                    </span>
                    @endif
                @endif
                @if($provider->drug_analysis_licence == null)
                    <label> @lang('user.analysis_licence') </label>
                    <input id="car-front-model" type="file" class="form-control" name="drug_analysis_licence" required>
                    @if ($errors->has('drug_analysis_licence'))
                        <span class="help-block">
                        <strong>{{ $errors->first('drug_analysis_licence') }}</strong>
                    </span>
                    @endif
                @endif
                <button type="submit" class="log-teal-btn">
                    @lang('user.Update')
                </button>
            </div>
        </form>
    </div>
@endsection