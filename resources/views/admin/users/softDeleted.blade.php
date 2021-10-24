@extends('admin.layout.base')

@section('title', 'Users ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            @include('flash::message')
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.Users')
                </h5>

                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.First_Name')</th>
                        <th>@lang('admin.Last_Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>
                        <th>@lang('admin.d_Amount')</th>
                        <th>@lang('admin.Rating')</th>
                        @can('استرجاع المستخدمين المحذوفين')
                            <th>@lang('admin.restoreUser')</th>
                        @endcan
                        @can('تأكيد حذف المستخدمين')
                            <th>@lang('admin.deleteUser')</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $index => $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->first_name }}</td>
                            <td>{{ $user->last_name }}</td>
                            @if(Setting::get('demo_mode', 0) == 1)
                                <td>{{ substr($user->email, 0, 3).'****'.substr($user->email, strpos($user->email, "@")) }}</td>
                            @else
                                <td>{{ $user->email }}</td>
                            @endif
                           
                            @if(Setting::get('demo_mode', 0) == 1)
                                <td>+919876543210</td>
                            @else
                                <td>{{ $user->mobile }}</td>
                            @endif
                            <td>{{ $user->deleted_at}}</td>
                            <td>{{ $user->rating }}</td>
                            <td>
                                @can('استرجاع المستخدمين المحذوفين')
                                    <a href="{{ route('admin.restoreUser', $user->id) }}" class="btn btn-info"><i class="fa fa-edit"></i> @lang('admin.restoreUser')</a>
                                @endcan
                            </td>
                            <td>
                                @can('تأكيد حذف المستخدمين')
                                    <a href="{{ route('admin.hdeleteUser', $user->id) }}" class="btn btn-danger"><i class="fa fa-trash"></i> @lang('admin.deleteUser')</a>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.First_Name')</th>
                        <th>@lang('admin.Last_Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>
                        <th>@lang('admin.d_Amount')</th>
                        <th>@lang('admin.Rating')</th>
                        @can('استرجاع المستخدمين المحذوفين')
                            <th>@lang('admin.restoreUser')</th>
                        @endcan
                        @can('تأكيد حذف المستخدمين')
                            <th>@lang('admin.deleteUser')</th>
                        @endcan
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

@endsection
