@extends('admin.layout.base')

@section('title', 'Admin ')

@section('content')
    @include('flash::message')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.adminsList')
                </h5>
                @can('اضافه المشرفين')
                    <a href="{{ route('admin.admin.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i
                                class="fa fa-plus"></i> @lang('admin.Add New Account Manager')</a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Full Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Picture')</th>
                        @if(auth()->user()->can('تعديل المشرفين') || auth()->user()->can('حذف المشرفين'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $index => $account)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $account->name }}</td>
                            @if(Setting::get('demo_mode', 0) == 1)
                                <td>{{ substr($account->email, 0, 3).'****'.substr($account->email, strpos($account->email, "@")) }}</td>
                            @else
                                <td>{{ $account->email }}</td>
                            @endif
                            @if($account->picture == 1)
                                <td>{{asset('/uploads/0eba298c049f856776bbdc473a7cabf684320a72.png')}}</td>
                            @else
                                <td>
                                    <img src="{{ asset($account->picture) }}" style="height: 50px" alt="">
                                </td>
                            @endif
                            @if(auth()->user()->can('تعديل المشرفين') || auth()->user()->can('حذف المشرفين'))
                                <td>
                                    <form action="{{ route('admin.admin.destroy', $account->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('تعديل المشرفين')
                                            <a href="{{ route('admin.admin.edit', $account->id) }}"
                                               class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                        @endcan
                                        @can('حذف المشرفين')
                                            <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i
                                                        class="fa fa-trash"></i> @lang('admin.Delete')</button>
                                        @endcan
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Full Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Picture')</th>
                        @if(auth()->user()->can('تعديل المشرفين') || auth()->user()->can('حذف المشرفين'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection