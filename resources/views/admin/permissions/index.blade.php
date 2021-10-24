@extends('admin.layout.base')

@section('title', 'Role ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.List Permissions')
                </h5>
                <a href="{{ route('admin.permission.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i
                            class="fa fa-plus"></i> @lang('admin.Add New Permission')</a>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.name_ar')</th>
                        <th>@lang('admin.name_en')</th>
                        <th>@lang('admin.routes')</th>
                        <th>@lang('admin.Action')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $index => $record)
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>{{$record->name}}</td>
                            <td>{{$record->name_en}}</td>
                            <td>{{$record->routes}}</td>
                            <td>
                                <form action="{{ route('admin.permission.destroy', $record->id) }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="_method" value="DELETE">
                                    <a href="{{ route('admin.permission.edit', $record->id) }}" class="btn btn-info"><i
                                                class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                    <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i
                                                class="fa fa-trash"></i> @lang('admin.Delete')</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.name_ar')</th>
                        <th>@lang('admin.name_en')</th>
                        <th>@lang('admin.routes')</th>
                        <th>@lang('admin.Action')</th>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection