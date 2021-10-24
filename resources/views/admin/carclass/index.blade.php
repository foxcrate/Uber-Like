@extends('admin.layout.base')

@section('title', 'Car Classes')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.Car Classes')</h5>
                @can('اضافه السيارات')
                    <button class="btn btn-success pull-right Get_Modal" style="margin-left: 1em;" data-toggle="modal"
                            data-target="#New"><i class="fa fa-plus"></i> @lang('admin.New Car Class')</button>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Car Name Ar')</th>
                        <th>@lang('admin.Car Name En')</th>
                        <th>@lang('admin.logo')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل السيارات') || auth()->user()->can('حذف السيارات'))
                            <th style="width:100px;">@lang('admin.Action')</th>
                        @endif

                    </tr>
                    </thead>
                    <tbody>
                    @foreach($CarClasses as $index => $CarClass)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            @if($CarClass->name)
                                <td>{{ $CarClass->name }}</td>
                            @else
                                <td> N/A</td>
                            @endif
                            @if($CarClass->name_en)
                                <td>{{ $CarClass->name_en }}</td>
                            @else
                                <td> N/A</td>
                            @endif
                            <td>
{{--                                <button type="button" data-toggle="modal" data-target=".bd-example-modal-lg">--}}
{{--                                    <img src="{{url("/").$CarClass->logo}}" style="height: 50px">--}}
{{--                                </button>--}}
{{--                                <a href=""></a>--}}
{{--                                <div id='myModel" . {{$index}} . "' class="modal fade bd-example-modal-lg" tabindex="' . {{$index}} . '" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">--}}
{{--                                    <div class="modal-dialog modal-lg">--}}
{{--                                        <div class="modal-content">--}}
{{--                                            <img src="{{url("/").$CarClass->logo}}" style="height: 100%;width: 100%">--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
                                @if($CarClass->logo)
                                    <img src="{{url("/").$CarClass->logo}}" style="height: 50px">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                <div class="col-xs-6">
                                    <input id="stripe_check "
                                           {{$CarClass->status?'checked':''}} data-id="{{$CarClass->id}}"
                                           data-model="CarClass" type="checkbox" class="js-switch Change_Status"
                                           data-color="#43b968">
                                </div>
                            </td>
                            @if(auth()->user()->can('تعديل السيارات') || auth()->user()->can('حذف السيارات'))
                                <td>
                                    <div class="input-group-btn">
                                        <button type="button"
                                                class="btn btn-info dropdown-toggle"
                                                data-toggle="dropdown">@lang('admin.Action')
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('تعديل السيارات')
                                                <li>
                                                    <a class="btn btn-default Get_Modal" data-id="{{$CarClass->id}}"
                                                       data-toggle="modal" data-target="#Edit"><i
                                                                class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                                </li>
                                            @endcan
                                            @can('حذف السيارات')
                                                <li>
                                                    <form action="{{ route('admin.carclass.destroy', $CarClass->id) }}"
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
                        <th>@lang('admin.Car Name Ar')</th>
                        <th>@lang('admin.Car Name En')</th>
                        <th>@lang('admin.logo')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل السيارات') || auth()->user()->can('حذف السيارات'))
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
                <form class="form-horizontal" action="{{route('admin.carclass.store')}}" method="post"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.Edit Car Class')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <input type="hidden" name="id">
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name" class="col-xs-12 col-form-label">@lang('admin.Car Name Ar')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name" id="name"
                                       placeholder="@lang('admin.Car Name Ar')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name_en" class="col-xs-12 col-form-label">@lang('admin.Car Name En')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name_en" id="name_en"
                                       placeholder="@lang('admin.Car Name En')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="logo" class="col-xs-12 col-form-label">@lang('admin.Car_Logo')</label>
                            </div>
                            <div class="col-xs-5">
                                <input type="file" accept="image/*" name="logo" class="dropify form-control-file"
                                       id="logo" aria-describedby="fileHelp">
                            </div>
                            <div class="col-xs-4">
                                <img class="img-responsive" src="" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('admin.Update Type')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="New" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <form class="form-horizontal" action="{{route('admin.carclass.store')}}" method="post"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.New Car Class')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name" class="col-xs-12 col-form-label">@lang('admin.Car Name Ar')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name" id="name"
                                       placeholder="@lang('admin.Car Name Ar')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name_en" class="col-xs-12 col-form-label">@lang('admin.Car Name En')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name_en" id="name_en"
                                       placeholder="@lang('admin.Car Name En')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="logo" class="col-xs-12 col-form-label">@lang('admin.Car_Logo')</label>
                            </div>
                            <div class="col-xs-9">
                                <input type="file" accept="image/*" name="logo" class="dropify form-control-file"
                                       id="logo" aria-describedby="fileHelp">
                            </div>


                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-success">@lang('admin.Create Type')</button>
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
                    url: "{{route('admin.carclass.index')}}/" + $(this).data('id'),
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