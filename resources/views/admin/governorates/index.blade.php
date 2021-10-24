@extends('admin.layout.base')

@section('title', trans('admin.list_governorate'))

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.list_governorate')</h5>
                @can('اضافه المحافظه')
                    <a href="{{ route('admin.governorate.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.addGovernorate')</a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        {{-- <input id="stripe_check2 " onchange="changeStatus2(2)"
                                type="checkbox" class="js-switch"> --}}
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.name_ar')</th>
                        <th>@lang('admin.name_en')</th>
                        @if(auth()->user()->can('تعديل المحافظه') || auth()->user()->can('حذف المحافظه'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    {{-- <button id="1111" onclick="changeText(this)">Click me</button> --}}
                    @foreach($records as $index => $record)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{$record->name}}</td>
                            <td>{{$record->name_en}}</td>

                            @if(auth()->user()->can('تعديل المحافظه') || auth()->user()->can('حذف المحافظه'))
                                <td>
                                    <form action="{{ route('admin.governorate.destroy', $record->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('تعديل المحافظه')
                                            <a href="{{ route('admin.governorate.edit', $record->id) }}"
                                                 class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.Edit')</a>

                                               <div class="col-xs-6">
                                                    <input id="stripe_check2 " onchange="changeStatus2({{ $record->id }})"
                                                        {{$record->available?'checked':''}} data-id="{{$record->id}}"
                                                         type="checkbox" class="js-switch">
                                                </div>

                                        @endcan
                                        @can('حذف المحافظه')
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
                        <th>@lang('admin.name_ar')</th>
                        <th>@lang('admin.name_en')</th>
                        @if(auth()->user()->can('تعديل المحافظه') || auth()->user()->can('حذف المحافظه'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
@endsection

<script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.js'></script>
<script src="/js/lang.js"></script>
<script  type="text/javascript">

    function changeStatus2(id) {
        $.ajax({
            type: 'post',
            data: {governorate_id: id,
                    _token: "{{ csrf_token() }}",
            },
            url: "{{'governorate/changeStatus'}}",
            success: function (data) {
                //alert(id);
            }
        });
    }

</script>
