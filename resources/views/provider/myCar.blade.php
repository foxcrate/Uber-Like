@extends('provider.layout.app')

@section('content')
    <div class="pro-dashboard-head">
        <div class="container">
            <a href="{{ route('provider.profile.index') }}" class="pro-head-link">@lang('user.Profile')</a>
{{--            <a href="{{ route('provider.documents.index') }}" class="pro-head-link">@lang('user.Update Documents')</a>--}}
            {{-- <a href="{{ route('provider.documents') }}" class="pro-head-link">@lang('user.Update Documents')</a> --}}
            <a href="#" class="pro-head-link active">@lang('user.My Cars')</a>
            <a href="{{ route('provider.location.index') }}" class="pro-head-link">@lang('user.Update Location')</a>
        </div>
    </div>

    <div class="pro-dashboard-content container">

        <div class="text-center">
            <h3>@lang('user.My Cars')</h3>
        </div>


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
                <th style="width:100px;">Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach(auth('provider')->user()->cars as $index => $car)
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
                        <a href="{{ url('provider/show-car', $car->id) }}" class="btn btn-primary"
                           style="width: 150px">show</a>
                        {{-- <a href="{{ url('provider/edit-car', $car->id) }}" class="btn btn-success"
                           style="width: 150px;margin-top: 10px">Edit</a> --}}
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
                <th style="width:100px;">Action</th>
            </tr>
            </tfoot>
        </table>
    </div>
@endsection
