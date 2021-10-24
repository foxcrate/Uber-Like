@extends('admin.layout.base')

@section('title', 'Service Conditions ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.Service Conditions')</h5>
                @can('اضافه شروط الخدمه')
                <button class="btn btn-success pull-right Get_Modal" style="margin-left: 1em;" data-toggle="modal"
                        data-target="#new"><i class="fa fa-plus"></i> @lang('admin.New Condition Types')</button>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Condition Title')</th>
                        <th>@lang('admin.Condition Title_en')</th>
                        <th>@lang('admin.Condition Detaills')</th>
                        <th>@lang('admin.Condition Detaills_en')</th>
                        <th>@lang('admin.Status')</th>
                        @can('حذف شروط الخدمه')
                            <th>@lang('admin.Action')</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($conditions as $index => $service)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $service->title }}</td>
                            <td>{{ $service->title_en }}</td>
                            <td>{!! $service->details !!}</td>
                            <td>{!! $service->details_en !!}</td>
                            <td>
                                <div class="col-xs-6">
                                    <input id="con_status" onchange="change_condition_Status({{$service->id}})"
                                           {{($service->status)?'checked':''}} data-id="{{$service->id}}"
                                           data-model="con" type="checkbox" class="js-switch" data-color="#43b968">
                                </div>
                            </td>
                            @can('حذف شروط الخدمه')
                                <td>
                                    <div class="input-group-btn">
                                        <li>
                                            <form action="{{ route('admin.condition.destroy') }}" method="POST">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="id" value="{{$service->id}}">
                                                <button class="btn btn-danger look-a-like"
                                                        onclick="return confirm('Are you sure?')"><i
                                                            class="fa fa-trash"></i> @lang('admin.Delete')</button>
                                            </form>
                                        </li>
                                    </div>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Condition Title')</th>
                        <th>@lang('admin.Condition Title_en')</th>
                        <th>@lang('admin.Condition Detaills')</th>
                        <th>@lang('admin.Condition Detaills_en')</th>
                        <th>@lang('admin.Status')</th>
                        @can('حذف شروط الخدمه')
                            <th>@lang('admin.Action')</th>
                        @endcan
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div id="new" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <form class="form-horizontal" action="{{route('admin.condition_settings.store')}}" method="post"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.Edit Service Type')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <input type="hidden" name="id">


                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name" class="col-xs-12 col-form-label">حالة الشرط بالغة العربية</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('name') }}" name="title" required
                                       id="title" placeholder="@lang('admin.Condition Title')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name" class="col-xs-12 col-form-label">حالة الشرط بالغة الإنجليزية</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('name') }}" name="title_en"
                                       required id="title_en" placeholder="Condition Title">
                            </div>
                        </div>


                        <script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>

                        <div class="form-group row">
                            <label for="details" class="col-xs-2 col-form-label">تقاصيل الحالة بالغة العربية</label>
                            <div class="col-xs-10">
                                <textarea class="form-control ckeditor" name="details" id="details"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="details" class="col-xs-2 col-form-label">تقاصيل الحالة بالغة الإنجليزية</label>
                            <div class="col-xs-10">
                                <textarea class="form-control ckeditor" name="details_en" id="details_en"></textarea>
                            </div>
                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('admin.Add Condition')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        function change_condition_Status($id) {
            alert($("#con_status").val());
            $.ajax({
                type: 'post',
                data: {con_id: $id, status: $("#con_status").val()},
                url: "{{'admin.condition.Change_Status'}}",
                success: function (data) {
                    alert(data);
                }
            });
        }
    </script>
@endsection