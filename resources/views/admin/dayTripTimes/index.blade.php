@extends('admin.layout.base')

@section('title', 'dayTripTimes ')

@section('content')
    @include('flash::message')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.dayTripTimes')

                </h5>
                @can('اضافه المشرفين')
                    <a href="{{ route('admin.day-trip-time.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i
                                class="fa fa-plus"></i> @lang('admin.Add New dayTripTimes')</a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.day')</th>
                        <th>@lang('admin.period')</th>
                        <th>@lang('admin.from')</th>
                        <th>@lang('admin.to')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل المشرفين') || auth()->user()->can('حذف المشرفين'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $index => $record)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $record->day }}</td>
                            <td>{{ $record->period }}</td>
                            <td>{{ $record->from }}</td>
                            <td>{{ $record->to }}</td>
                            {{--                            <td>{{ $record->status }}</td>--}}
                            <td>
                                <div class="col-xs-6">
                                    <input id="stripe_check "
                                           {{$record->status?'checked':''}} data-id="{{$record->id}}"
                                           data-model="dayTripTime" type="checkbox" class="js-switch Change_Status"
                                           data-color="#43b968">
                                </div>
                            </td>
                            @if(auth()->user()->can('تعديل المشرفين') || auth()->user()->can('حذف المشرفين'))
                                <td>
                                    <form action="{{ route('admin.day-trip-time.destroy', $record->id) }}"
                                          method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('تعديل المشرفين')
                                            <a href="{{ route('admin.day-trip-time.edit', $record->id) }}"
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
                        <th>@lang('admin.day')</th>
                        <th>@lang('admin.period')</th>
                        <th>@lang('admin.from')</th>
                        <th>@lang('admin.to')</th>
                        <th>@lang('admin.Status')</th>
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