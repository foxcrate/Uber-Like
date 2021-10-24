@extends('provider.layout.app')

@section('content')
    <div class="pro-dashboard-head">
        <div class="container">
            <a href="{{ route('provider.profile.index') }}" class="pro-head-link">@lang('user.Profile')</a>
{{--            <a href="{{ route('provider.documents.index') }}" class="pro-head-link">@lang('user.Update Documents')</a>--}}
            {{-- <a href="{{ route('provider.documents') }}" class="pro-head-link">@lang('user.Update Documents')</a> --}}
            <a href="{{ route('provider.cars') }}" class="pro-head-link active">@lang('user.My Cars')</a>
            <a href="{{ route('provider.location.index') }}" class="pro-head-link">@lang('user.Update Location')</a>
        </div>
    </div>

    <div class="pro-dashboard-content container">

        <div class="text-center">
            <h3>@lang('user.My Cars')</h3>
        </div>


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
    </div>
@endsection
