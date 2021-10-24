@extends('company.layout.base')

@section('title', 'index ')

@section('content')

    <h3 class="alert alert-success text-center">@lang('admin.company_cars')</h3>
    <div>
        <a href="{{route('company.addCar')}}" class="btn btn-block btn-primary"
           style="color: white;width: 100%;margin-bottom: 20px;margin-top: 10px;font-weight: bold;padding-top: 15px;padding-bottom: 15px">{{trans('user.Add Car')}}</a>
    </div>
    @include('flash::message')
    <table class="table table-striped table-bordered dataTable" id="table-2">
        <thead>
        <tr>
            <th>ID</th>
            <th>@lang('admin.car_code')</th>
            <th>@lang('admin.car_number')</th>
            <th>@lang('admin.car_model_id')</th>
            <th>@lang('admin.color')</th>
            <th>@lang('admin.car_front')</th>
            <th>@lang('admin.car_back')</th>
            <th style="width:100px;">@lang('admin.action')</th>
        </tr>
        </thead>
        <tbody>
        @foreach(auth('fleet')->user()->cars as $index => $car)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $car->car_code }}</td>
                <td>{{ $car->car_number }}</td>
                <td>{{ $car->car_model_id }}</td>
                <td>{{ $car->color }}</td>
                <td>
                    @if($car->car_front)
                        <img src="{{asset($car->car_front)}}" style="height: 50px">
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @if($car->car_back)
                        <img src="{{asset($car->car_back)}}" style="height: 50px">
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    <a href="{{ url('company/show/car', $car->id) }}" class="btn btn-primary"
                       style="width: 150px">@lang('admin.show')</a>
                    {{-- <a href="{{ url('company/car/edit', $car->id) }}" class="btn btn-success"
                       style="width: 150px;margin-top: 10px">Edit</a>
                    <form action="{{ route('company.removeCar', $car->id) }}"
                          method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="btn btn-danger look-a-like" style="width: 150px;margin-top: 10px"
                                onclick="return confirm('Are you sure?')"><i
                                    class="fa fa-trash"></i> @lang('admin.Delete')
                        </button>
                    </form> --}}
                </td>
            </tr>
        @endforeach
        </tbody>
        <tfoot>
        <tr>
            <th>ID</th>
            <th>@lang('admin.car_code')</th>
            <th>@lang('admin.car_number')</th>
            <th>@lang('admin.car_model_id')</th>
            <th>@lang('admin.color')</th>
            <th>@lang('admin.car_front')</th>
            <th>@lang('admin.car_back')</th>
            <th style="width:100px;">@lang('admin.action')</th>
        </tr>
        </tfoot>
    </table>



    <h3 class="alert alert-success text-center" style="margin-top: 90px">@lang('admin.company_workers')</h3>
    <div>
        <a href="{{route('company.addProvider')}}" class="btn btn-block btn-primary"
           style="color: white;width: 100%;margin-bottom: 20px;margin-top: 10px;font-weight: bold;padding-top: 15px;padding-bottom: 15px">{{trans('user.Add Provider')}}</a>
    </div>
    <table class="table table-striped table-bordered dataTable" id="table-2">
        <thead>
        <tr>
            <th>ID</th>
            <th>@lang('user.name')</th>
            <th>@lang('user.email')</th>
            <th>@lang('user.mobile')</th>
            <th>@lang('user.avatar')</th>
            <th>@lang('user.status')</th>
            <th style="width:100px;">@lang('admin.action')</th>
        </tr>
        </thead>
        <tbody>
        @foreach(auth('fleet')->user()->providers as $index => $provider)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ base64_decode($provider->first_name) }} {{ base64_decode($provider->last_name) }}</td>
                <td>{{ $provider->email }}</td>
                <td>{{ $provider->mobile }}</td>
                <td>
                    @if($provider->avatar)
                        <img src="{{asset($provider->avatar)}}" style="height: 50px">
                    @else
                        N/A
                    @endif
                </td>
                <td>
                    @isset($provider->service->status)
                        @if($provider->service->status == 'active')
                                <div class="alert alert-success text-center">@lang('admin.Online')</div>
                        @elseif($provider->service->status == 'offline')
                                <div class="alert alert-danger text-center">@lang('admin.Offline')</div>
                        @else
                            N/A
                        @endif
                    @endisset
                </td>

                <td>
                    <a href="{{ url('company/show/provider', $provider->id) }}" class="btn btn-primary"
                       style="width: 150px">@lang('admin.show')</a>
                    {{-- <a href="{{ url('company/provider/edit', $provider->id) }}" class="btn btn-success"
                       style="width: 150px;margin-top: 10px">Edit</a> --}}
                    {{-- <form action="{{ route('company.removeProvider', $provider->id) }}"
                          method="POST">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="DELETE">
                        <button class="btn btn-danger look-a-like" style="width: 150px;margin-top: 10px"
                                onclick="return confirm('Are you sure?')"><i
                                    class="fa fa-trash"></i> @lang('admin.Delete')
                        </button>
                    </form> --}}
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
            <th style="width:100px;">@lang('admin.action')</th>
        </tr>
        </tfoot>
    </table>
@endsection
