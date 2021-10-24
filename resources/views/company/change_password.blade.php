{{--@extends('company.layout.base')--}}

{{--@section('title', 'Change Password ')--}}

{{--@section('content')--}}

{{-- <div class="profile-content gray-bg pad50">--}}
{{--    <div class="container">--}}
{{--        <div class="dash-content">--}}
{{--            <div class="row no-margin">--}}
{{--                <div class="col-md-12">--}}
{{--                    <h4 class="page-title">@lang('user.profile.change_password')</h4>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--                @include('common.notify')--}}
{{--            <div class="row no-margin edit-pro">--}}
{{--                <form action="{{route('company.password.update')}}" method="post">--}}
{{--                {{ csrf_field() }}--}}
{{--                    <div class="col-md-12">--}}
{{--                        <div class="form-group">--}}
{{--                            <label>@lang('user.profile.old_password')</label>--}}
{{--                            <input type="password" name="old_password" class="form-control" placeholder="@lang('user.profile.old_password')">--}}
{{--                        </div>--}}
{{--                        <div class="form-group">--}}
{{--                            <label>@lang('user.profile.password')</label>--}}
{{--                            <input type="password" name="password" class="form-control" placeholder="@lang('user.profile.password')">--}}
{{--                        </div>--}}

{{--                        <div class="form-group">--}}
{{--                            <label>@lang('user.profile.confirm_password')</label>--}}
{{--                            <input type="password" name="password_confirmation" class="form-control" placeholder="@lang('user.profile.confirm_password')">--}}
{{--                        </div>--}}
{{--                      --}}
{{--                        <div>--}}
{{--                            <button type="submit" class="form-sub-btn big">@lang('user.profile.change_password')</button>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </form>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</div>--}}

{{--@endsection--}}


@extends('company.layout.base')
@section('title', 'Change Password ')
@section('content')
    <div class="container">
        <div class="row">
            @include('flash::message')
            <div class="col-md-12">
                <div class="card card-default">
                    <div class="card-heading">

                        <div class="card-body">
                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form class="form-horizontal" method="POST" action="{{ route('company.password.update') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('current-password') ? ' has-error' : '' }}" style="margin-top: 20px">
                                    <label for="new-password" class="control-label">{{trans('admin.current-password')}}</label>

                                    <div class="col-md-12">
                                        <input id="current-password" type="password" class="form-control" name="current-password" required>

                                        @if ($errors->has('current-password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('current-password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('new-password') ? ' has-error' : '' }}">
                                    <label for="new-password" class="control-label" style="margin-top: 20px">{{trans('admin.new-password')}}</label>

                                    <div class="col-md-12" style="margin-top: 20px">
                                        <input id="new-password" type="password" class="form-control" name="new-password" required>

                                        @if ($errors->has('new-password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('new-password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label style="margin-top: 20px" for="new-password-confirm" class="control-label">{{trans('admin.new-password-confirm')}}</label>

                                    <div class="col-md-12" style="margin-top: 20px">
                                        <input id="new-password-confirm" type="password" class="form-control" name="new-password_confirmation" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary" style="margin-top: 25px">
                                            {{trans('admin.changePassword')}}
                                        </button>
                                        @foreach($errors as $error)
                                            {{ error }}
                                        @endforeach
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
@endsection
