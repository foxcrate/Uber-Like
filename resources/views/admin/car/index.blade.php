@extends('admin.layout.base')

@section('title', 'Car Provider')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.List Car Providers')
                </h5>
                @can('اضافه السيارات الخاصه بالسائقين')
                    <a href="{{ route('admin.car.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i
                                class="fa fa-plus"></i> @lang('admin.Add New Car Provider')</a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.car_code')</th>
                        <th>@lang('admin.car_number')</th>
                        <th>@lang('admin.fleet_id')</th>
                        <th>@lang('admin.car_model_id')</th>
                        <th>@lang('admin.color')</th>
                        <th>@lang('admin.Send Message')</th>
                        <th>@lang('admin.Providers')</th>
                        {{--<th>@lang('admin.car_back')</th>--}}
                        {{--<th>@lang('admin.car_left')</th>--}}
                        {{--<th>@lang('admin.car_right')</th>--}}
                        {{--<th>@lang('admin.car_licence_front')</th>--}}
                        {{--<th>@lang('admin.car_licence_back')</th>--}}
                        @if(auth()->user()->can('تعديل السيارات الخاصه بالسائقين') || auth()->user()->can('حذف السيارات الخاصه بالسائقين'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $index => $account)
                        <tr>
                            <td>{{ $account->id}}</td>
                            <td>{{ $account->car_code }}</td>
                            <td>{{ $account->car_number }}</td>
                            @if($account->fleet_id == 0)
                                <td>لا يوجد</td>
                            @else
                                <td>{{ $account->fleet->name }}</td>
                            @endif
                            <td>{{ $account->carModel->name}} <br> {{ $account->carModel->date}}</td>
                            @if($account->color == null)
                                <td>لا يوجد</td>
                            @else
                                <td>{{ $account->color }}</td>
                            @endif
                            <td>
                                <a href="{{ route('admin.car.send_message', $account->id) }}"
                                   class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.Send Message')</a>
                            </td>
                            <td>
                                @if(count($account->providers) != 0)
                                    @foreach($account->providers as $provider)
                                        <div class="alert alert-success">{{base64_decode($provider->first_name)}} {{base64_decode($provider->last_name)}} _ {{$provider->id}}</div>
                                        {{-- <div class="alert alert-success">{{$provider->id}}</div> --}}
                                    @endforeach
                                @else
                                    <div class="alert alert-danger">لا يوجد</div>
                                @endif
                            </td>
                            @if(auth()->user()->can('تعديل السيارات الخاصه بالسائقين') || auth()->user()->can('حذف السيارات الخاصه بالسائقين'))
                                <td>
                                    <form action="{{ route('admin.car.destroy', $account->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('تعديل السيارات الخاصه بالسائقين')
                                            <a href="{{ route('admin.car.edit', $account->id) }}"
                                               class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                        @endcan
                                        @can('حذف السيارات الخاصه بالسائقين')
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
                        <th>@lang('admin.car_code')</th>
                        <th>@lang('admin.car_number')</th>
                        <th>@lang('admin.fleet_id')</th>
                        <th>@lang('admin.car_model_id')</th>
                        <th>@lang('admin.color')</th>
                        <th>@lang('admin.Send Message')</th>
                        <th>@lang('admin.Providers')</th>
                        {{--<th>@lang('admin.car_back')</th>--}}
                        {{--<th>@lang('admin.car_left')</th>--}}
                        {{--<th>@lang('admin.car_right')</th>--}}
                        {{--<th>@lang('admin.car_licence_front')</th>--}}
                        {{--<th>@lang('admin.car_licence_back')</th>--}}
                        @if(auth()->user()->can('تعديل السيارات الخاصه بالسائقين') || auth()->user()->can('حذف السيارات الخاصه بالسائقين'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection
