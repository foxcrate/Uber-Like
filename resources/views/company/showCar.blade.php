@extends('company.layout.base')

@section('title', 'index ')

@section('content')

    <h3 class="alert alert-success text-center">بيانات السياره</h3>
    <table class="table table-striped table-bordered dataTable" id="table-2">
        <tbody>
        <tr>
            <th>@lang('admin.car_code')</th>
            <td>{{ $car->car_code }}</td>
        </tr>
        <tr>
            <th>@lang('admin.car_number')</th>
            <td>{{ $car->car_number }}</td>
        </tr>
        <tr>
            <th>@lang('admin.car_model_id')</th>
            <td>{{ $car->carModel->name }}</td>
        </tr>
        {{--        <tr>--}}
        {{--            <th>@lang('user.fleet_id')</th>--}}
        {{--            <td>{{ $car->fleet->name }}</td>--}}
        {{--        </tr>--}}
        <tr>
            <th>@lang('admin.color')</th>
            <td>{{ $car->color }}</td>
        </tr>
        <tr>
            <th>@lang('admin.car_front')</th>
            <td>
                @if($car->car_front)
                    <img src="{{asset($car->car_front)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>@lang('admin.car_back')</th>
            <td>
                @if($car->car_back)
                    <img src="{{asset($car->car_back)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>@lang('admin.car_left')</th>
            <td>
                @if($car->car_left)
                    <img src="{{asset($car->car_left)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>@lang('admin.car_right')</th>
            <td>
                @if($car->car_right)
                    <img src="{{asset($car->car_right)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>@lang('admin.car_licence_front')</th>
            <td>
                @if($car->car_licence_front)
                    <img src="{{asset($car->car_licence_front)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        <tr>
            <th>@lang('admin.car_licence_back')</th>
            <td>
                @if($car->car_licence_back)
                    <img src="{{asset($car->car_licence_back)}}" style="height: 100px">
                @else
                    N/A
                @endif
            </td>
        </tr>
        </tbody>
    </table>

    <h3 class="alert alert-success text-center">بيانات السائقين العاملين على السياره</h3>
    <table class="table table-striped table-bordered dataTable" id="table-2">
        <thead>
        <tr>
            <th>ID</th>
            <th>@lang('user.name')</th>
            <th>@lang('user.email')</th>
            <th>@lang('user.mobile')</th>
            <th>@lang('user.avatar')</th>
            <th>@lang('user.status')</th>
            <th style="width:100px;">Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach($car->providers as $index => $provider)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $provider->first_name }} {{ $provider->last_name }}</td>
                <td>{{ $provider->email }}</td>
                <td>{{ $provider->mobile }}</td>
                <td>
                    @if($provider->avatar)
                        <img src="{{asset($provider->avatar)}}" style="height: 50px">
                    @else
                        N/A
                    @endif
                </td>
                @if($provider->service->status == 'active')
                    <td>
                        <div class="alert alert-success text-center">online</div>
                    </td>
                @elseif($provider->service->status == 'offline')
                    <td>
                        <div class="alert alert-danger text-center">offline</div>
                    </td>
                @else
                    N/A
                @endif
                <td>
                    <a href="{{ url('company/show/car/provider', $provider->id) }}" class="btn btn-primary" style="width: 150px">show</a>
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>@lang('user.name')</th>
            <th>@lang('user.email')</th>
            <th>@lang('user.mobile')</th>
            <th>@lang('user.avatar')</th>
            <th>@lang('user.status')</th>
            <th style="width:100px;">Action</th>
        </tr>
        </tfoot>
    </table>


@endsection