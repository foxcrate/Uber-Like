@extends('admin.layout.base')

@section('title', 'Promocodes ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.Promocodes')</h5>
                @can('اضافه البروموكود')
                    <a href="{{ route('admin.promocode.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.Add New Promocode')
                    </a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Promocode')</th>
                        <th>@lang('admin.Discount')</th>
                        <th>@lang('admin.Expiration')</th>
                        <th>@lang('admin.Status')</th>
                        <th>@lang('admin.Used Count')</th>
                        <th>@lang('admin.user_mobile')</th>
                        @if(auth()->user()->can('تعديل البروموكود') || auth()->user()->can('حذف البروموكود'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($promocodes as $index => $promo)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$promo->promo_code}}</td>
                            <td>{{$promo->discount}}%</td>
                            <td>
                                {{date('d-m-Y',strtotime($promo->expiration))}}
                            </td>
                            <td>
                                @if(date("Y-m-d") <= $promo->expiration)
                                    <span class="tag tag-success">Valid</span>
                                @else
                                    <span class="tag tag-danger">Expiration</span>
                                @endif
                            </td>
                            <td>{{promo_used_count($promo->id)}}</td>
                            <td>{{$promo->user_mobile}}</td>
                            @if(auth()->user()->can('تعديل البروموكود') || auth()->user()->can('حذف البروموكود'))
                                <td>
                                    <form action="{{ route('admin.promocode.destroy', $promo->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('تعديل البروموكود')
                                            <a href="{{ route('admin.promocode.edit', $promo->id) }}"
                                               class="btn btn-info"><i
                                                        class="fa fa-plus-square"></i>@lang('admin.user_mobile_to')</a>
                                        @endcan
                                        @can('حذف البروموكود')
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
                        <th>@lang('admin.Promocode')</th>
                        <th>@lang('admin.Discount')</th>
                        <th>@lang('admin.Expiration')</th>
                        <th>@lang('admin.Status')</th>
                        <th>@lang('admin.Used Count')</th>
                        <th>@lang('admin.user_mobile')</th>
                        @if(auth()->user()->can('تعديل البروموكود') || auth()->user()->can('حذف البروموكود'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
@endsection
