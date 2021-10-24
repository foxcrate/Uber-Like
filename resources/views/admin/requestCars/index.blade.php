@extends('admin.layout.base')

@section('title', 'Cars')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">@lang('admin.Cars')</h5>
            @include('flash::message')
            @can('تعديل سيارات بدون سائق')
             <a href="{{ route('admin.order.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New Car Class</a>
            @endcan
             <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('admin.Name')</th>
                        <th>@lang('admin.User Name')</th>
                        <th>@lang('admin.phone user')</th>
                        <th>@lang('admin.carPrice')</th>
                        <th>@lang('admin.h_Amount')</th>
                        @can('تعديل سيارات بدون سائق')
                            <th>@lang('admin.Status')</th>
                            <th style="width:100px;">@lang('admin.Action')</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                @foreach($Requests as $index => $car)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><a href="{{ route('admin.carmodel.index') }}">{{app()->getlocale() == 'ar'? $car->model->name :$car->model->name_en }} - {{ $car->model->date }}</a></td>
                        @if (isset($car->user))
                        <td>{{base64_decode($car->user->first_name)}} {{base64_decode($car->user->last_name)}}</td>
                        <td>{{$car->user->mobile}}</td>
                        @else
                        <td>----</td>
                        <td>----</td>
                        @endif
                        <td>{{ $car->price }}</td>
                        <td>{{$car->created_at}}</td>
                        @can('تعديل سيارات بدون سائق')
                            <td>
                            @if($car->status == '0')
                                    <form action="{{route('admin.order.active',$car->id)}}" method="POST">
                                        @csrf

                                        <button class="btn btn-info" onclick="return confirm('Are you sure?')">@lang('admin.Enable')</button>
                                    </form>

                                @else
                                   <form action="{{route('admin.order.unActive',$car->id)}}" method="POST">
                                       @csrf
                                        <button class="btn btn-danger" onclick="return confirm('Are you sure?')">@lang('admin.Disable')</button>
                                   </form>
                                @endif
                            </td>
                            <td>
                                <div class="input-group-btn">

                                    <button type="button"
                                        class="btn btn-info dropdown-toggle"
                                        data-toggle="dropdown">@lang('admin.Action')
                                        <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a href="{{ route('admin.order.edit', $car->id) }}" class="btn btn-default"><i class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                        </li>

                                        <li>
                                            <form action="{{ route('admin.order.destroy', $car->id) }}" >
                                                {{ csrf_field() }}
                                                <button class="btn btn-default look-a-like" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> @lang('admin.Delete')</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        @endcan
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>#</th>
                        <th>@lang('admin.Name')</th>
                        <th>@lang('admin.User Name')</th>
                        <th>@lang('admin.phone user')</th>
                        <th>@lang('admin.carPrice')</th>
                        <th>@lang('admin.h_Amount')</th>
                        <th>@lang('admin.Status')</th>
                        <th>@lang('admin.Status')</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection
@section('scripts')

@endsection
