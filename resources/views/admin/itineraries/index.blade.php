@extends('admin.layout.base')

@section('title', 'Itinerary ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.List Itineraries')
                </h5>
                @can('اضافه رحلات الباص المجدوله')
                    <a href="{{ route('admin.itinerary.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i
                                class="fa fa-plus"></i> @lang('admin.Add New Itineraries')</a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.s_address_ar')</th>
                        <th>@lang('admin.d_address_ar')</th>
                        <th>@lang('admin.from_time')</th>
                        <th>@lang('admin.to_time')</th>
                        <th>@lang('admin.day_trip_time_id')</th>
                        <th>@lang('admin.provider_id')</th>
                        <th>@lang('admin.car_id')</th>
                        <th>@lang('admin.transportation_type_id')</th>
                        <th>@lang('admin.capacity')</th>
                        <th>@lang('admin.itinerary')</th>
                        <th>@lang('admin.number_station')</th>
                        <th>@lang('admin.Users')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل رحلات الباص المجدوله') || auth()->user()->can('حذف رحلات الباص المجدوله'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $index => $account)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $account->s_address_ar }}</td>
                            <td>{{ $account->d_address_ar }}</td>
                            <td>{{ $account->from_time }}</td>
                            <td>{{ $account->to_time }}</td>
                            @if($account->day_trip_time_id == 0)
                                <td>لا يوجد</td>
                            @else
                                <td>{{ $account->dayTripTime->day }} - {{ $account->dayTripTime->period }}</td>
                            @endif
                            @if($account->provider_id == 0)
                                <td>لا يوجد</td>
                            @else
                                <td>{{ $account->provider->email }} - {{ $account->provider->mobile }}</td>
                            @endif
                            @if($account->car_id == 0)
                                <td>لا يوجد</td>
                            @else
                                <td>{{ $account->car->car_code }} - {{ $account->car->car_number }}</td>
                            @endif
                            @if($account->transportation_type_id == 0)
                                <td>لا يوجد</td>
                            @else
                                <td>{{ $account->transportationType->name }}</td>
                            @endif
                            <td>{{ $account->capacity }}</td>
                            <td>{{ $account->itinerary }}</td>
                            <td>{{ $account->number_station }}</td>
                            <td>
                                @if(count($account->users) != 0)
                                    @foreach($account->users as $users)
                                        <div class="alert alert-success">{{$users->first_name}} {{$users->last_name}}</div>
                                    @endforeach
                                @else
                                    <div class="alert alert-danger">لا يوجد</div>
                                @endif
                            </td>
                            <td>
                                <div class="col-xs-6">
                                    <input id="stripe_check "
                                           {{$account->status?'checked':''}} data-id="{{$account->id}}"
                                           data-model="Itinerary" type="checkbox" class="js-switch Change_Status"
                                           data-color="#43b968">
                                </div>
                            </td>
                            @if(auth()->user()->can('تعديل رحلات الباص المجدوله') || auth()->user()->can('حذف رحلات الباص المجدوله'))
                                <td>
                                    <form action="{{ route('admin.itinerary.destroy', $account->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('تعديل رحلات الباص المجدوله')
                                            <a href="{{ route('admin.itinerary.edit', $account->id) }}"
                                               class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                        @endcan
                                        @can('حذف رحلات الباص المجدوله')
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
                        <th>@lang('admin.s_address_ar')</th>
                        <th>@lang('admin.d_address_ar')</th>
                        <th>@lang('admin.from_time')</th>
                        <th>@lang('admin.to_time')</th>
                        <th>@lang('admin.day_trip_time_id')</th>
                        <th>@lang('admin.provider_id')</th>
                        <th>@lang('admin.car_id')</th>
                        <th>@lang('admin.transportation_type_id')</th>
                        <th>@lang('admin.capacity')</th>
                        <th>@lang('admin.itinerary')</th>
                        <th>@lang('admin.number_station')</th>
                        <th>@lang('admin.Users')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل رحلات الباص المجدوله') || auth()->user()->can('حذف رحلات الباص المجدوله'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection