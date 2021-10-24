@extends('admin.layout.base')

@section('title', 'Role ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.List Roles')
                    @if(Setting::get('demo_mode', 0) == 1)
                        <span class="pull-right">(*personal information hidden in demo)</span>
                    @endif
                </h5>
                @can('اضافه الرتب')
                    <a href="{{ route('admin.role.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i
                                class="fa fa-plus"></i> @lang('admin.Add New Role')</a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.name_ar')</th>
                        <th>@lang('admin.name_en')</th>
                        @if(auth()->user()->can('تعديل الرتب') || auth()->user()->can('حذف الرتب'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $index => $record)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$record->name}}</td>
                            <td>{{$record->name_en}}</td>
                            @if(auth()->user()->can('تعديل الرتب') || auth()->user()->can('حذف الرتب'))
                                <td>
                                    <form action="{{ route('admin.role.destroy', $record->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('تعديل الرتب')
                                            <a href="{{ route('admin.role.edit', $record->id) }}"
                                               class="btn btn-info"><i
                                                        class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                        @endcan
                                        @can('حذف الرتب')
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
                        <th>@lang('admin.name_ar')</th>
                        <th>@lang('admin.name_en')</th>
                        @if(auth()->user()->can('تعديل الرتب') || auth()->user()->can('حذف الرتب'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection