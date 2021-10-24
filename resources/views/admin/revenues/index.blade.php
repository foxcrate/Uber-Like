@extends('admin.layout.base')

@section('title', 'revenues ')

@section('content')
    @include('flash::message')
    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.revenues')</h5>
                @can('اضافه التعاقدات')
                    <a href="{{ route('admin.revenue.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.Add New revenue')
                    </a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.provider_id')</th>
                        <th>@lang('admin.Service Type')</th>
                        <th>@lang('admin.money')</th>
                        <th>@lang('admin.from')</th>
                        <th>@lang('admin.to')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل التعاقدات') || auth()->user()->can('حذف التعاقدات'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                        {{-- <p>{{$records  }}</p> --}}
                    @foreach($records as $index => $promo)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>
                            {{ $promo->provider_id}}
                            </td>
                            <td>
                                {{-- @if($promo->provider->serviceTypes) --}}
                                    {{-- {{ $promo->provider->serviceTypes[0]->name}} --}}

                                    {{-- {{ $promo->provider->serviceTypes}} --}}
                                    @isset($promo->provider->serviceTypes)
                                    {{ $promo->provider->service->service_type->name}}
                                    @endisset

                                    {{-- {{ $promo->provider->service->service_type->name}} --}}
                                {{-- @else --}}

                                {{-- @endif --}}
                            </td>
                            @if($promo->gift==1)
                            <td>
                            <h5><span class="tag tag-primary">@lang('admin.gift')</span></h5>
                            </td>
                            @else
                            <td>{{$promo->money}}</td>
                            @endif

                            <td>
                                {{date('d-m-Y',strtotime($promo->from))}}
                            </td>
                            <td>
                                {{date('d-m-Y',strtotime($promo->to))}}
                            </td>
                            <td>
                                {{--                                                                @if(date("Y-m-d") <= $promo->to)--}}
                                @if($promo->status == 'active')
                                    <span class="tag tag-success">@lang('admin.active')</span>
                                @elseif($promo->status == 'not_active')
                                    <span class="tag tag-black">@lang('admin.not_active')</span>
                                @elseif($promo->status == 'time_finish')
                                    <span class="tag tag-danger">@lang('admin.time_finish')</span>
                                @elseif($promo->status == 'money_finish')
                                    <span class="tag tag-info">@lang('admin.money_finish')</span>
                                @endif
                            </td>
                            @if(auth()->user()->can('تعديل التعاقدات') || auth()->user()->can('حذف التعاقدات'))
                                <td>
                                    <form action="{{ route('admin.revenue.destroy', $promo->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @if($promo->status != 'active')
                                            <a href="{{route('admin.revenue.edit', $promo->id) }}"
                                               class="btn btn-success"><i class="fa fa-plus-square"></i> @lang('admin.update_active')</a>
                                        @endif
                                        @can('حذف التعاقدات')
                                            <button class="btn btn-danger" onclick="return confirm('Are you sure ?')"><i
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
                        <th>@lang('admin.provider_id')</th>
                        <th>@lang('admin.Service Type')</th>
                        <th>@lang('admin.money')</th>
                        <th>@lang('admin.from')</th>
                        <th>@lang('admin.to')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل التعاقدات') || auth()->user()->can('حذف التعاقدات'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
@endsection
