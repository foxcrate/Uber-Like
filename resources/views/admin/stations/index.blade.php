@extends('admin.layout.base')

@section('title', 'Station ')

@section('content')
    @include('flash::message')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.stationsList')
                </h5>
                @can('اضافه محطات الوقوف')
                    <a href="{{ route('admin.station.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i
                                class="fa fa-plus"></i> @lang('admin.Add New Station')</a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.station')</th>
                        <th>@lang('admin.substation')</th>
                        @if(auth()->user()->can('تعديل محطات الوقوف') || auth()->user()->can('حذف محطات الوقوف'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $index => $account)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $account->station }}</td>
                            <td>{{ $account->substation }}</td>
                            @if(auth()->user()->can('تعديل محطات الوقوف') || auth()->user()->can('حذف محطات الوقوف'))
                                <td>
                                    <form action="{{ route('admin.station.destroy', $account->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('تعديل محطات الوقوف')
                                            <a href="{{ route('admin.station.edit', $account->id) }}"
                                               class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                        @endcan
                                        @can('حذف محطات الوقوف')
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
                        <th>@lang('admin.station')</th>
                        <th>@lang('admin.substation')</th>
                        @if(auth()->user()->can('تعديل محطات الوقوف') || auth()->user()->can('حذف محطات الوقوف'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection