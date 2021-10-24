@extends('provider.layout.app')

@section('content')
    <div class="pro-dashboard-head">
        <div class="container">
            <a href="#" class="pro-head-link active">@lang('user.Profile')</a>
            {{--<a href="{{ route('provider.documents.index') }}" class="pro-head-link">@lang('user.Update Documents')</a>--}}

            {{-- <a href="{{ route('provider.documents') }}" class="pro-head-link">@lang('user.Update Documents')</a> --}}
            <a href="{{ route('provider.cars') }}" class="pro-head-link">@lang('user.My Cars')</a>
            <a href="{{ route('provider.location.index') }}" class="pro-head-link">@lang('user.Update Location')</a>
        </div>
    </div>
    <!-- Pro-dashboard-content -->

    <div class="pro-dashboard-content gray-bg">
        <div class="profile">
            <!-- Profile head -->
            <div class="container">

                <div class="profile-head white-bg row no-margin">
                    <div class="prof-head-left col-lg-2 col-md-2 col-sm-3 col-xs-12">
                        <div class="new-pro-img bg-img">
                            <img style="width: 200%;height: 85px;"
                                 src="{{asset(auth()->guard('provider')->user()->avatar)}}">
                        </div>

                    </div>

                    <div class="prof-head-right col-lg-10 col-md-10 col-sm-9 col-xs-12">
                        <h3 class="prof-name">{{ base64_decode(auth()->guard('provider')->user()->first_name) }} {{ base64_decode(auth()->guard('provider')->user()->last_name) }}</h3>
                        <p class="board-badge btn btn-primary">{{ strtoupper(auth()->guard('provider')->user()->status) }}</p>
                    </div>
                </div>
            </div>

            <!-- Profile-content -->
            <div class="profile-content gray-bg pad50">
                <div class="container">
                    <div class="row no-margin">
                        <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12 no-padding">
                            @include('common.notify')
                            {{-- <form class="profile-form" action="{{route('provider.profile.update')}}" method="POST"
                                  enctype="multipart/form-data" role="form">
                            {{csrf_field()}} --}}
                            <!-- Prof-form-sub-sec -->
                                <div class="prof-form-sub-sec">
                                    <div class="row no-margin">
                                        <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                            <div class="form-group">
                                                <label>@lang('user.First Name')</label>
                                                <input type="text" class="form-control" disabled required
                                                       placeholder="Contact Number" name="first_name" required
                                                       id="first_name"
                                                       value="{{ base64_decode(auth()->guard('provider')->user()->first_name )}}">

                                                       {{-- <button placeholder="Contact Number" disabled class="form-control" name="first_name">{{ base64_decode(auth()->guard('provider')->user()->first_name )}}</button> --}}

                                                @if ($errors->has('first_name'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('first_name') }}</strong>
                                                 </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="prof-sub-col col-sm-6 col-xs-12 no-right-padding">
                                            <div class="form-group">
                                                <label>@lang('user.Last Name')</label>
                                                <input type="text" class="form-control" disabled required
                                                       placeholder="Contact Number" name="last_name"
                                                       value="{{ base64_decode(auth()->guard('provider')->user()->last_name) }}">

                                                       {{-- <button placeholder="Contact Number" disabled class="form-control" name="last_name">{{ base64_decode(auth()->guard('provider')->user()->last_name) }}</button> --}}

                                                @if ($errors->has('last_name'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('last_name') }}</strong>
                                                 </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div class="row">
                                        <div class="prof-sub-col prof-1 col-xs-12">
                                            <div class="form-group">
                                                <label>@lang('user.Avatar')</label>
                                                <input type="file" class="form-control" name="avatar">
                                            </div>
                                        </div>
                                    </div> --}}

                                    <div class="row no-margin">
                                        <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                            <div class="form-group">
                                                <label>@lang('user.Phone')</label>
                                                <input type="text" class="form-control" disabled required
                                                       placeholder="Contact Number" name="mobile"
                                                       value="{{ auth()->guard('provider')->user()->mobile }}">

                                                       {{-- <button placeholder="Contact Number" disabled class="form-control" name="mobile">{{ auth()->guard('provider')->user()->mobile }}</button> --}}

                                                @if ($errors->has('mobile'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('mobile') }}</strong>
                                                 </span>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- <div class="prof-sub-col col-sm-6 col-xs-12 no-right-padding">
                                            <div class="form-group no-margin">
                                                <label for="exampleSelect1">@lang('user.Language')</label>
                                                <select class="form-control" name="language">
                                                    <option value="EN">English</option>
                                                    <option value="AR">Arabic</option>
                                                </select>
                                                @if ($errors->has('language'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('language') }}</strong>
                                                 </span>
                                                @endif
                                            </div>
                                        </div> --}}
                                    </div>
                                </div>
                                <!-- End of prof-sub-sec -->

                                <!-- Prof-form-sub-sec -->

                                {{-- <div class="prof-form-sub-sec border-top">
                                    <div class="form-group">
                                        <label>@lang('user.Address')</label>
                                        <input type="text" class="form-control" required placeholder="Enter Address"
                                               name="address"
                                               value="{{ auth()->guard('provider')->user()->profile ? auth()->guard('provider')->user()->profile->address : "" }}">



                                        <input type="text" class="form-control" required
                                               placeholder="Enter Suite, Floor, Apt (optional)"
                                               style="border-top: none;" name="address_secondary"
                                               value="{{ auth()->guard('provider')->user()->profile ? auth()->guard('provider')->user()->profile->address_secondary : "" }}">



                                        @if ($errors->has('address'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('address') }}</strong>
                                                 </span>
                                        @endif
                                        @if ($errors->has('address_secondary'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('address_secondary') }}</strong>
                                                 </span>
                                        @endif
                                    </div> --}}

                                    <div class="row no-margin">
                                        <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                            <div class="form-group no-margin">
                                                <label>@lang('user.City')</label>
                                                <input type="text" class="form-control" disabled  required
                                                       placeholder="Enter City" name="city"
                                                       value="{{ auth()->guard('provider')->user()->profile ? auth()->guard('provider')->user()->profile->city : "" }}">

                                                       {{-- <button placeholder="Contact Number" disabled class="form-control" name="city">{{ auth()->guard('provider')->user()->profile ? auth()->guard('provider')->user()->profile->city : "" }}</button> --}}

                                                @if ($errors->has('city'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('city') }}</strong>
                                                 </span>
                                                @endif
                                            </div>
                                        </div>
                                        {{-- <div class="prof-sub-col col-sm-6 col-xs-12 no-right-padding">
                                            <div class="form-group">
                                                <label>@lang('user.Country')</label>
                                                <select class="form-control" name="country">
                                                    <option value="EGYPT">@lang('user.EGYPT')</option>
                                                </select>
                                                @if ($errors->has('country'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('country') }}</strong>
                                                 </span>
                                                @endif
                                            </div>
                                        </div> --}}
                                    </div>


                                    <div class="row no-margin">
                                        <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                            <div class="form-group no-margin">
                                                <label>@lang('user.Postal Code')</label>
                                                <input type="text" class="form-control" disabled  required
                                                       placeholder="Postal Code" name="postal_code"
                                                       value="{{ auth()->guard('provider')->user()->profile ? auth()->guard('provider')->user()->profile->postal_code : "" }}">

                                                       {{-- <button placeholder="Contact Number" disabled class="form-control" name="postal_code">{{ auth()->guard('provider')->user()->profile ? auth()->guard('provider')->user()->profile->postal_code : "" }}</button> --}}

                                                @if ($errors->has('postal_code'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('postal_code') }}</strong>
                                                 </span>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="prof-sub-col col-sm-6 col-xs-12 no-right-padding">
                                            <div class="form-group">
                                                <label>@lang('user.Car Model')</label>
                                                <input type="text" placeholder="Car Model" disabled class="form-control"
                                                       name="car_type"
                                                     {{--  value="{{ auth()->guard('provider')->user()->service->carModel['name']}}"--}}>
                                                @if ($errors->has('car_type'))
                                                    <span class="help-block">
                                                <strong>{{ $errors->first('car_type')}}</strong>
                                                 </span>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="row no-margin">
                                            <div class="prof-sub-col col-sm-6 col-xs-12 no-left-padding">
                                                <div class="form-group no-margin">
                                                    <label>@lang('user.Car Number')</label>
                                                    <input type="text" class="form-control" disabled
                                                           placeholder="Car Number" name="car_number"
                                                           value="{{ auth()->guard('provider')->user()->service->car_number ? auth()->guard('provider')->user()->service->car_number : "" }}">

                                                           {{-- <button placeholder="Made Year" disabled class="form-control" name="car_history">{{  auth()->guard('provider')->user()->service->car_history ? auth()->guard('provider')->user()->service->car_history : "--"  }} </button> --}}

                                                    @if ($errors->has('car_number'))
                                                        <span class="help-block">
                                                <strong>{{ $errors->first('car_number') }}</strong>
                                                 </span>
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="prof-sub-col col-sm-6 col-xs-12 no-right-padding">
                                                <div class="form-group">
                                                    <label>@lang('user.Made Year')</label>
                                                    <input type="text" placeholder="Made Year" disabled
                                                           class="form-control" name="car_history"
                                                           value="{{ auth()->guard('provider')->user()->service->car_history ? auth()->guard('provider')->user()->service->car_history : "" }}">

                                                    {{-- <button placeholder="Made Year" disabled class="form-control" name="car_history">{{  auth()->guard('provider')->user()->service->car_history ? auth()->guard('provider')->user()->service->car_history : "--"  }} </button> --}}

                                                           @if ($errors->has('car_history'))
                                                        <span class="help-block">
                                                <strong>{{ $errors->first('car_history') }}</strong>
                                                 </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- End of prof-sub-sec -->

                                    <!-- Prof-form-sub-sec -->

                                    {{-- <div class="prof-form-sub-sec border-top">
                                        <div class="col-xs-12 col-md-6 col-md-offset-3">
                                            <button type="submit" class="btn btn-block btn-primary update-link">@lang('user.Update')
                                            </button>
                                        </div>
                                    </div> --}}

                                </div>    <!-- End of prof-sub-sec -->
                            {{-- </form> --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Pro-dashboard-content -->
    {{--<div class="pro-dashboard-content gray-bg">--}}
    {{--    <div class="profile">--}}
    {{--        <div class="container">--}}
    {{--            <div style="width: 665px;" class="profile-head white-bg row no-margin">--}}
    {{--                <h3 style=" font-size: 30px;margin-left: 200px;" > الجزء الخاص بالسيارة</h3>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--        <!-- Profile-content -->--}}
    {{--        <div class="profile-content gray-bg pad50">--}}
    {{--            <div class="container">--}}
    {{--                <div class="row no-margin">--}}
    {{--                    <div class="col-lg-7 col-md-7 col-sm-8 col-xs-12 no-padding">--}}
    {{--                        <form class="profile-form" action="{{route('provider.profile.update')}}" method="POST" enctype="multipart/form-data" role="form">--}}
    {{--                        {{csrf_field()}}--}}
    {{--                        <!-- Prof-form-sub-sec -->--}}
    {{--                            <div class="prof-form-sub-sec">--}}
    {{--                                <div class="row no-margin">--}}

    {{--                                    <div class="row">--}}
    {{--                                        <div class="prof-sub-col prof-1 col-xs-12">--}}
    {{--                                            <div class="form-group">--}}
    {{--                                        <label for="logo" class="col-xs-12 col-form-label">@lang('admin.logo')</label>--}}
    {{--                                            <input type="file" accept="image/*" name="logo" class="form-control" id="logo" aria-describedby="fileHelp">--}}
    {{--                                        </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}

    {{--                                    <div class="row">--}}
    {{--                                        <div class="row no-margin">--}}
    {{--                                        <div class="prof-sub-col prof-1 col-xs-6">--}}
    {{--                                            <div class="form-group">--}}
    {{--                                                <label for="car_front" class="col-xs-12 col-form-label">@lang('admin.Car_Front')</label>--}}
    {{--                                                <input type="file" accept="image/*" name="car_front" class="form-control" id="car_front" aria-describedby="fileHelp">--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}


    {{--                                        <div class="prof-sub-col prof-1 col-xs-6">--}}
    {{--                                            <div class="form-group">--}}
    {{--                                                <label for="Car_Back" class="col-xs-12 col-form-label">@lang('admin.Car_Back')</label>--}}
    {{--                                                <input type="file" accept="image/*" name="Car_Back" class="form-control" id="Car_Back" aria-describedby="fileHelp">--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}
    {{--                                    </div>--}}

    {{--                                    <div class="row">--}}
    {{--                                        <div class="prof-sub-col prof-1 col-xs-6">--}}
    {{--                                            <div class="form-group">--}}
    {{--                                                <label for="driver_licence_front" class="col-xs-12 col-form-label">@lang('admin.Driver_Licence Front')</label>--}}
    {{--                                                <input type="file" accept="image/*" name="driver_licence_front" class="form-control" id="driver_licence_front" aria-describedby="fileHelp">--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}



    {{--                                        <div class="prof-sub-col prof-1 col-xs-6">--}}
    {{--                                            <div class="form-group">--}}
    {{--                                                <label for="driver_licence_back" class="col-xs-12 col-form-label">@lang('admin.Driver_Licence Back')</label>--}}
    {{--                                                <input type="file" accept="image/*" name="driver_licence_back" class="form-control" id="driver_licence_back" aria-describedby="fileHelp">--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}

    {{--                                    <div class="row">--}}
    {{--                                        <div class="prof-sub-col prof-1 col-xs-6">--}}
    {{--                                            <div class="form-group">--}}
    {{--                                                <label for="car_licence_front" class="col-xs-12 col-form-label">رخصة السيارة(الامام)</label>--}}
    {{--                                                <input type="file" accept="image/*" name="car_licence_front" class="form-control" id="car_licence_front" aria-describedby="fileHelp">--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}



    {{--                                        <div class="prof-sub-col prof-1 col-xs-6">--}}
    {{--                                            <div class="form-group">--}}
    {{--                                                <label for="car_licence_back" class="col-xs-12 col-form-label">رخصة السيارة(الخلف)</label>--}}
    {{--                                                <input type="file" accept="image/*" name="car_licence_back" class="form-control" id="car_licence_back" aria-describedby="fileHelp">--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}


    {{--                                    <div class="row">--}}
    {{--                                        <div class="prof-sub-col prof-1 col-xs-6">--}}
    {{--                                            <div class="form-group">--}}
    {{--                                                <label for="identity_front" class="col-xs-12 col-form-label">@lang('admin.Identity_Front')</label>--}}
    {{--                                                <input type="file" accept="image/*" name="identity_front" class="form-control" id="identity_front" aria-describedby="fileHelp">--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}

    {{--                                        <div class="prof-sub-col prof-1 col-xs-6">--}}
    {{--                                            <div class="form-group">--}}
    {{--                                                <label for="identity_back" class="col-xs-12 col-form-label">@lang('admin.Identity_back')</label>--}}
    {{--                                                <input type="file" accept="image/*" name="identity_back" class="form-control" id="identity_back" aria-describedby="fileHelp">--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}

    {{--                                    <div class="row">--}}
    {{--                                        <div class="prof-sub-col prof-1 col-xs-12">--}}
    {{--                                            <div class="form-group">--}}
    {{--                                                <label for="criminal_feat" class="col-xs-12 col-form-label">الفيش الجنائى</label>--}}
    {{--                                                <input type="file" accept="image/*" name="criminal_feat" class="form-control" id="criminal_feat" aria-describedby="fileHelp">--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}


    {{--                                    <div class="row">--}}
    {{--                                        <div class="prof-sub-col prof-1 col-xs-12">--}}
    {{--                                            <div class="form-group">--}}
    {{--                                                <label for="drug_analysis_licence" class="col-xs-12 col-form-label">تحليل الدم</label>--}}
    {{--                                                <input type="file" accept="image/*" name="drug_analysis_licence" class="form-control" id="drug_analysis_licence" aria-describedby="fileHelp">--}}
    {{--                                            </div>--}}
    {{--                                        </div>--}}
    {{--                                    </div>--}}


    {{--                                    <div class="prof-form-sub-sec border-top">--}}
    {{--                                <div class="col-xs-12 col-md-6 col-md-offset-3">--}}
    {{--                                    <button type="submit" class="btn btn-block btn-primary update-link">Add Document Car</button>--}}
    {{--                                </div>--}}
    {{--                            </div>--}}
    {{--                            <!-- End of prof-sub-sec -->--}}
    {{--                        </form>--}}
    {{--                    </div>--}}
    {{--                </div>--}}
    {{--            </div>--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</div>--}}

@endsection
