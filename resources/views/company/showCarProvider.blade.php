@extends('company.layout.base')

@section('title', 'index ')

@section('content')

    <h3 class="alert alert-info text-center">بيانات السائق </h3>
    <table class="table table-striped table-bordered dataTable" id="table-2">
        <tbody>
        <tr>
            <th>@lang('user.name')</th>
            <td>{{ $provider->first_name }} {{ $provider->last_name }}</td>
        </tr>
        <tr>
            <th>@lang('user.email')</th>
            <td>{{ $provider->email }}</td>
        </tr>
        <tr>
            <th>@lang('user.mobile')</th>
            <td>{{ $provider->mobile }}</td>
        </tr>
        @if($provider->status == 'approved')
            <th>@lang('user.status')</th>
            <td>
                <div class="alert alert-success text-center">@lang('admin.approved')</div>
            </td>
        @elseif($provider->status == 'onboarding')
            <th>@lang('user.status')</th>
            <td>
                <div class="alert alert-danger text-center">@lang('admin.onboarding')</div>
            </td>
        @elseif($provider->status == 'banned')
            <th>@lang('user.status')</th>
            <td>
                <div class="alert alert-danger text-center">@lang('admin.banned')</div>
            </td>
        @else
            N/A
        @endif
        <tr>
            <th>@lang('user.avatar')</th>
            <td>
                @if($provider->avatar)
                    <img src="{{asset($provider->avatar)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>@lang('user.Driver Licence Front')</th>
            <td>
                @if($provider->driver_licence_front)
                    <img src="{{asset($provider->driver_licence_front)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>@lang('user.Driver Licence Back')</th>
            <td>
                @if($provider->driver_licence_back)
                    <img src="{{asset($provider->driver_licence_back)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>@lang('user.Identity Front')</th>
            <td>
                @if($provider->identity_front)
                    <img src="{{asset($provider->identity_front)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>@lang('user.Identity Back')</th>
            <td>
                @if($provider->identity_back)
                    <img src="{{asset($provider->identity_back)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>@lang('user.criminal_feat')</th>
            <td>
                @if($provider->criminal_feat)
                    <img src="{{asset($provider->criminal_feat)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>@lang('user.analysis_licence')</th>
            <td>
                @if($provider->drug_analysis_licence)
                    <img src="{{asset($provider->drug_analysis_licence)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        </tbody>
    </table>

@endsection