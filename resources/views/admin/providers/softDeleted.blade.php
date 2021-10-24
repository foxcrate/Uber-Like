@extends('admin.layout.base')

@section('title', 'Providers ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            @include('flash::message')
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.ProvidersTrashed')
                    @if(Setting::get('app_status', 0) != 1)
                        <span class="pull-right">(*personal information hidden in demo)</span>
                    @endif
                </h5>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Full_Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>
                        <th>@lang('admin.Company Name')</th>
{{--                        <th>@lang('admin.Total_Requests')</th>--}}
{{--                        <th>@lang('admin.Accepted_Requests')</th>--}}
{{--                        <th>@lang('admin.Cancelled_Requests_User')</th>--}}
{{--                        <th>@lang('admin.Cancelled_Requests')</th>--}}
                        <th>@lang('admin.Wallet_Amount')</th>
                        @if(auth()->user()->can('استرجاع السائقين المحذوفين'))
                            <th>@lang('admin.restore')</th>
                        @endif
                        @if(auth()->user()->can('تأكيد حذف السائقين'))
                            <th>@lang('admin.delete')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($providers as $index => $provider)
                        <tr>
                            <td>{{ $provider->id }}</td>
                            <td>{{ base64_decode($provider->first_name) }} {{ base64_decode($provider->last_name) }}</td>
                            @if(Setting::get('demo_mode', 0) == 1)
                                <td>{{ substr($provider->email, 0, 3).'****'.substr($provider->email, strpos($provider->email, "@")) }}</td>
                            @else
                                <td>{{ $provider->email }}</td>
                            @endif
                            @if($provider->mobile == null)
                                <td>+919876543210</td>
                            @else
                                <td>{{ $provider->mobile }}</td>
                            @endif
                            @if($provider->fleet == null)
                                <td>لا توجد</td>
                            @else
                                <td>{{ $provider->fleets->name }}</td>
                            @endif
{{--                            <td>{{ \App\UserRequests::where('provider_id',$provider->id)->count() + \App\ProviderUserRequests::where('provider_id', $provider->id)->count() }}</td>--}}
{{--                            <td>{{ \App\UserRequests::where('provider_id',$provider->id)->where('status', 'COMPLETED')->count() }}</td>--}}
{{--                            <td>{{ \App\UserRequests::where('provider_id',$provider->id)->where('status', 'CANCELLED')->count() }}</td>--}}
{{--                            <td>{{ \App\ProviderUserRequests::where('provider_id', $provider->id)->count() }}</td>--}}

                            <td>{{ number_format($provider->wallet_balance, 2, '.', ',')}}</td>

                            @if(auth()->user()->can('تعديل السائقين') || auth()->user()->can('حذف السائقين'))
                                <td>
                                    @can('استرجاع السائقين المحذوفين')
                                        <a  class="btn btn-info" href="{{ route('admin.restore',['id'=>$provider->id]) }}">
                                            <i class="fa fa-edit"></i> @lang('admin.restore')
                                        </a>
                                    @endcan
                                </td>
                                <td>
                                    @can('تأكيد حذف السائقين')
                                        <a  class="btn btn-danger" href="{{ route('admin.hdelete',['id'=>$provider->id]) }}"
                                           onclick="return confirm('Are you sure？')">
                                            <i class="fa fa-trash"></i> @lang('admin.delete')
                                        </a>
                                    @endcan
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Full_Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>
                        <th>@lang('admin.Company Name')</th>
{{--                        <th>@lang('admin.Total_Requests')</th>--}}
{{--                        <th>@lang('admin.Accepted_Requests')</th>--}}
{{--                        <th>@lang('admin.Cancelled_Requests_User')</th>--}}
{{--                        <th>@lang('admin.Cancelled_Requests')</th>--}}
                        <th>@lang('admin.Wallet_Amount')</th>
                        @if(auth()->user()->can('استرجاع السائقين المحذوفين'))
                            <th>@lang('admin.restore')</th>
                        @endif
                        @if(auth()->user()->can('تأكيد حذف السائقين'))
                            <th>@lang('admin.delete')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
