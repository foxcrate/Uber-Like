@extends('admin.layout.base')

@section('title', 'company-subscriptions ')

@section('content')
    @include('flash::message')
    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.company subscriptions')</h5>
                @can('اضافه تعاقدات الشركات')
                    <a href="{{ route('admin.company-subscription.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.Add New company subscription')
                    </a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.fleet_id')</th>
                        <th>@lang('admin.money')</th>
                        <th>@lang('admin.from')</th>
                        <th>@lang('admin.to')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل تعاقدات الشركات') || auth()->user()->can('حذف تعاقدات الشركات'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($records as $index => $promo)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$promo->fleet->name}} - {{$promo->fleet->email}}</td>
                            <td>{{$promo->money}}</td>
                            <td>
                                {{date('d-m-Y',strtotime($promo->from))}}
                            </td>
                            <td>
                                {{date('d-m-Y',strtotime($promo->to))}}
                            </td>
                            <td>
                                {{-- @if(date("Y-m-d") <= $promo->to)--}}
                                @if($promo->status == 'active')
                                    <span class="tag tag-success">@lang('admin.active')</span>
                                @elseif($promo->status == 'not_active')
                                    <span class="tag tag-black">@lang('admin.not_active')</span>
                                @elseif($promo->status == 'time_finish')
                                    <span class="tag tag-danger">@lang('admin.time_finish')</span>
                                @endif
                            </td>
                            @if(auth()->user()->can('تعديل تعاقدات الشركات') || auth()->user()->can('حذف تعاقدات الشركات'))
                                <td>
                                    <form action="{{ route('admin.company-subscription.destroy', $promo->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('تعديل تعاقدات الشركات')
                                            <a href="{{ route('admin.company-subscription.edit', $promo->id) }}"
                                               class="btn btn-info"><i
                                                        class="fa fa-plus-square"></i> @lang('admin.Edit')</a>
                                        @endcan
                                        @can('حذف تعاقدات الشركات')
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
                        <th>@lang('admin.fleet_id')</th>
                        <th>@lang('admin.money')</th>
                        <th>@lang('admin.from')</th>
                        <th>@lang('admin.to')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل تعاقدات الشركات') || auth()->user()->can('حذف تعاقدات الشركات'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
@endsection