@extends('admin.layout.base')

@section('title', 'Documents ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.Documents')</h5>
                @can('اضافه المستندات')
                    <a href="{{ route('admin.document.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.Add New Document')</a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Document Name')</th>
                        <th>@lang('admin.Type')</th>
                        @if(auth()->user()->can('تعديل المستندات') || auth()->user()->can('حذف المستندات'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($documents as $index => $document)
                        <tr>
                            <td>{{$index + 1}}</td>
                            <td>{{$document->name}}</td>
                            <td>{{$document->type}}</td>
                            @if(auth()->user()->can('تعديل المستندات') || auth()->user()->can('حذف المستندات'))
                                <td>
                                    <form action="{{ route('admin.document.destroy', $document->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        @can('تعديل المستندات')
                                            <a href="{{ route('admin.document.edit', $document->id) }}"
                                               class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                        @endcan
                                        @can('حذف المستندات')
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
                        <th>@lang('admin.Document Name')</th>
                        <th>@lang('admin.Type')</th>
                        @if(auth()->user()->can('تعديل المستندات') || auth()->user()->can('حذف المستندات'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
@endsection