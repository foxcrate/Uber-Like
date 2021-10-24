@extends('admin.layout.base')

@section('title', trans('admin.boxes'))

@section('content')
    <script src="https://cdn.ckeditor.com/4.11.2/standard/ckeditor.js"></script>
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.Box List')</h5>
                @can('اضافه المنشورات')
                    <button class="btn btn-success pull-right Get_Modal" style="margin-left: 1em;" data-toggle="modal"
                            data-target="#New"><i class="fa fa-plus"></i> @lang('admin.New Box')</button>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.title')</th>
                        <th>@lang('admin.title_en')</th>
                        <th>@lang('admin.details')</th>
                        <th>@lang('admin.details_en')</th>
                        <th>@lang('admin.photo')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل المنشورات') || auth()->user()->can('حذف المنشورات'))
                            <th style="width:100px;">@lang('admin.Action')</th>
                        @endif

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($box as $index => $CarClass)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            @if($CarClass->title)
                                <td>{{ $CarClass->title }}</td>
                            @else
                                <td> N/A</td>
                            @endif
                            @if($CarClass->title_en)
                                <td>{{ $CarClass->title_en }}</td>
                            @else
                                <td> N/A</td>
                            @endif
                            @if($CarClass->details)
                                <td><p>{{ $CarClass->details }}</p></td>
                            @else
                                <td> N/A</td>
                            @endif
                            @if($CarClass->details_en)
                                <td>{{ $CarClass->details_en }}</td>
                            @else
                                <td> N/A</td>
                            @endif
                            <td>
                                @if($CarClass->photo)
                                    <img src="{{url("/").$CarClass->photo}}" style="height: 50px">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <div class="col-xs-6">
                                    <input id="stripe_check "
                                           {{$CarClass->status?'checked':''}} data-id="{{$CarClass->id}}"
                                           data-model="box" type="checkbox" class="js-switch Change_Status"
                                           data-color="#43b968">
                                </div>
                            </td>
                            @if(auth()->user()->can('تعديل المنشورات') || auth()->user()->can('حذف المنشورات'))
                                <td>
                                    <div class="input-group-btn">
                                        <button type="button"
                                                class="btn btn-info dropdown-toggle"
                                                data-toggle="dropdown">@lang('admin.Action')
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('تعديل المنشورات')
                                                <li>
                                                    <a class="btn btn-default Get_Modal" data-id="{{$CarClass->id}}"
                                                       data-toggle="modal" data-target="#Edit"><i
                                                                class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                                </li>
                                            @endcan
                                            @can('حذف المنشورات')
                                                <li>
                                                    <form action="{{ route('admin.box.destroy', $CarClass->id) }}"
                                                          method="POST">
                                                        {{ csrf_field() }}
                                                        <input type="hidden" name="_method" value="DELETE">
                                                        <button class="btn btn-default look-a-like"
                                                                onclick="return confirm('Are you sure?')"><i
                                                                    class="fa fa-trash"></i> @lang('admin.Delete')
                                                        </button>
                                                    </form>
                                                </li>
                                            @endcan
                                        </ul>
                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.title')</th>
                        <th>@lang('admin.title_en')</th>
                        <th>@lang('admin.details')</th>
                        <th>@lang('admin.details_en')</th>
                        <th>@lang('admin.photo')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل المنشورات') || auth()->user()->can('حذف المنشورات'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div id="Edit" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <form class="form-horizontal" action="{{route('admin.box.store')}}" method="post"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.Edit Box')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <input type="hidden" name="id">

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="title" class="col-xs-12 col-form-label">@lang('admin.title')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="title" id="title"
                                       placeholder="@lang('admin.title')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="title_en" class="col-xs-12 col-form-label">@lang('admin.title_en')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="title_en" id="title_en"
                                       placeholder="@lang('admin.title_en')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="details" class="col-xs-3 col-form-label">@lang('admin.details')</label>
                            <div class="col-xs-9">
                            <textarea class="form-control ckeditor" name="details"
                                      id="details">{{$CarClass->details}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="details_en" class="col-xs-3 col-form-label">@lang('admin.details_en')</label>
                            <div class="col-xs-9">
                            <textarea class="form-control ckeditor" name="details_en"
                                      id="details_en">{{$CarClass->details_en}}</textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="link" class="col-xs-12 col-form-label">@lang('admin.link')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="link" id="link"
                                       placeholder="@lang('admin.link')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="photo" class="col-xs-12 col-form-label">@lang('admin.photo')</label>
                            </div>
                            <div class="col-xs-9">
                                <input type="file" accept="image/*" name="photo" class="dropify form-control-file"
                                       id="photo" aria-describedby="fileHelp">
                            </div>
                            <div class="col-xs-4">
                                <img class="img-responsive" src="" alt="">
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('admin.Update Box')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="New" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <form class="form-horizontal" action="{{route('admin.box.store')}}" method="post"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.New Box')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="title" class="col-xs-12 col-form-label">@lang('admin.title')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="title" id="title"
                                       placeholder="@lang('admin.title')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="title_en" class="col-xs-12 col-form-label">@lang('admin.title_en')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="title_en" id="title_en"
                                       placeholder="@lang('admin.title_en')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="details" class="col-xs-3 col-form-label">@lang('admin.details')</label>
                            <div class="col-xs-9">
                            <textarea class="form-control ckeditor" name="details"
                                      id="details"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="details_en" class="col-xs-3 col-form-label">@lang('admin.details_en')</label>
                            <div class="col-xs-9">
                            <textarea class="form-control ckeditor" name="details_en"
                                      id="details_en"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="link" class="col-xs-12 col-form-label">@lang('admin.link')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="link" id="link"
                                       placeholder="@lang('admin.link')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="photo" class="col-xs-12 col-form-label">@lang('admin.photo')</label>
                            </div>
                            <div class="col-xs-9">
                                <input type="file" accept="image/*" name="photo" class="dropify form-control-file"
                                       id="photo" aria-describedby="fileHelp">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-success">@lang('admin.Create Box')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script>


        $('body').on('click', '.Get_Modal', function () {
            var Target_Modal = $(this).data('target');
            if ($(this).data('id')) {
                $.ajax({
                    type: 'get',
                    url: "{{route('admin.box.index')}}/" + $(this).data('id'),
                    success: function (data) {
                        $.each(data, function (key, value) {
                            $(Target_Modal).find('input[name="' + key + '"]').not('input[type="file"]').val(value);
                            $(Target_Modal).find('input[name="' + key + '"][type="file"]').closest('.row').find('img').attr('src', value);
                        });
                    }
                });
            }
        });

        // $(document).ready(function () {
        //     $('.popupimage').click(function (event) {
        //         event.preventDefault();
        //         $('.modal .ahaaaa').attr('src', $(this).attr('href'));
        //         $('.modal').modal('show');
        //     });
        // });
        $('#myModal').on('shown.bs.modal', function () {
            $('#myInput').trigger('focus')
        });
    </script>
@endsection
{{--@push('script')--}}
{{--    <script>--}}
{{--        --}}
{{--    </script>--}}
{{--@endpush--}}