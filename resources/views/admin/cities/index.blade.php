@extends('admin.layout.base')

@section('title', trans('admin.list_city'))

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.list_city')</h5>
                @can('اضافه المدينه')
                <a href="{{ route('admin.city.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.addCity')</a>
                @endcan
                    <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Name')</th>
                        <th>@lang('admin.name_en')</th>
                        <th>@lang('admin.governorate_id')</th>
                        @if(auth()->user()->can('تعديل المدينه') || auth()->user()->can('حذف المدينه'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $index => $record)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{$record->name}}</td>
                            <td>{{$record->name_en}}</td>
                            @if(app()->getLocale()=="en")
                                <td>{{$record->governorate->name_en}}</td>
                            @else
                                <td>{{$record->governorate->name}}</td>
                            @endif
                            @if(auth()->user()->can('تعديل المدينه') || auth()->user()->can('حذف المدينه'))
                                <td>
                                    <form action="{{ route('admin.city.destroy', $record->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('تعديل المدينه')
                                            <a href="{{ route('admin.city.edit', $record->id) }}"
                                               class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                        @endcan
                                        @can('حذف المدينه')
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
                        <th>@lang('admin.Name')</th>
                        <th>@lang('admin.name_en')</th>
                        <th>@lang('admin.governorate_id')</th>
                        @if(auth()->user()->can('تعديل المدينه') || auth()->user()->can('حذف المدينه'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection