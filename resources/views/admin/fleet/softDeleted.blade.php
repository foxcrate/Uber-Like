@extends('admin.layout.base')

@section('title', 'Fleets ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.FleetsTrashed')
                </h5>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Full Name')</th>
                        <th>@lang('admin.Company Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>
                        <th>@lang('admin.Wallet_Amount')</th>
                        <th>@lang('admin.Company_logo')</th>
                        @can('استرجاع الشركات المحذوفه')
                            <th>@lang('admin.restoreFleet')</th>
                        @endcan
                        @can('تأكيد حذف الشركات')
                            <th>@lang('admin.deleteFleet')</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fleets as $index => $fleet)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $fleet->name }}</td>
                            <td>{{ $fleet->company }}</td>
                            @if(Setting::get('demo_mode', 0) == 1)
                                <td>{{ substr($fleet->email, 0, 3).'****'.substr($fleet->email, strpos($fleet->email, "@")) }}</td>
                            @else
                                <td>{{ $fleet->email }}</td>
                            @endif
                            @if(Setting::get('demo_mode', 0) == 1)
                                <td>+919876543210</td>
                            @else
                                <td>{{ $fleet->mobile }}</td>
                            @endif
                            <td>{{ number_format($fleet->wallet_balance, 2, '.', ',')}}</td>
                            <td>
                                <img src="{{asset($fleet->logo)}}" style="height: 75px;width: 75px">
                            </td>
                            <td>
                                @can('استرجاع الشركات المحذوفه')
                                    <a href="{{ route('admin.restoreFleet', $fleet->id) }}" class="btn btn-info"><i class="fa fa-edit"></i> @lang('admin.restoreFleet')</a>
                                @endcan
                            </td>
                            <td>
                                @can('تأكيد حذف الشركات')
                                    <a href="{{ route('admin.hdeleteFleet', $fleet->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> @lang('admin.deleteFleet')</a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Full Name')</th>
                        <th>@lang('admin.Company Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>
                        <th>@lang('admin.Wallet_Amount')</th>
                        <th>@lang('admin.Company_logo')</th>
                        @can('استرجاع الشركات المحذوفه')
                            <th>@lang('admin.restoreFleet')</th>
                        @endcan
                        @can('تأكيد حذف الشركات')
                            <th>@lang('admin.deleteFleet')</th>
                        @endcan
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@endsection
