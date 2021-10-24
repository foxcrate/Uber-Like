@extends('admin.layout.base')

@section('title', 'Transportation Types')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.Transportation Types')</h5>
                @can('اضافه انواع النقل')
                    <button class="btn btn-success pull-right Get_Modal" style="margin-left: 1em;" data-toggle="modal"
                            data-target="#New"><i class="fa fa-plus"></i> @lang('admin.New Transportation Types')
                    </button>
                @endcan

                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Name')</th>
                        <th>@lang('admin.name_en')</th>
                        <th>@lang('admin.Image')</th>
                        <th>@lang('admin.Capacity')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل انواع النقل') || auth()->user()->can('حذف انواع النقل'))
                            <th style="width:100px;">@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($TransportationTypes as $index => $transtype)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $transtype->name }}</td>
                            <td>{{ $transtype->name_en }}</td>
                            <td>
                                @if($transtype->image)
                                    <img src="{{url('/').$transtype->image}}" style="height: 50px">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $transtype->capacity }}</td>
                            <td>
                                <div class="col-xs-6">
                                    <input id="stripe_check "
                                           {{$transtype->status?'checked':''}} data-id="{{$transtype->id}}"
                                           data-model="TransportationType" type="checkbox"
                                           class="js-switch Change_Status" data-color="#43b968">
                                </div>
                            </td>
                            @if(auth()->user()->can('تعديل انواع النقل') || auth()->user()->can('حذف انواع النقل'))
                                <td>
                                    <div class="input-group-btn">
                                        <button type="button"
                                                class="btn btn-info dropdown-toggle"
                                                data-toggle="dropdown">@lang('admin.Action')
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('تعديل انواع النقل')
                                                <li>
                                                    <a class="btn btn-default Get_Modal" data-id="{{$transtype->id}}"
                                                       data-toggle="modal" data-target="#Edit"><i
                                                                class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                                </li>
                                            @endcan
                                            @can('حذف انواع النقل')
                                                <li>
                                                    <form action="{{ route('admin.transtype.destroy', $transtype->id) }}"
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
                        <th>@lang('admin.Name')</th>
                        <th>@lang('admin.name_en')</th>
                        <th>@lang('admin.Image')</th>
                        <th>@lang('admin.Capacity')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل انواع النقل') || auth()->user()->can('حذف انواع النقل'))
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
                <form class="form-horizontal" action="{{route('admin.transtype.store')}}" method="post"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.Wallet')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <input type="hidden" name="id">
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name"
                                       class="col-xs-12 col-form-label">@lang('admin.Transportation Name')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name" id="name"
                                       placeholder="@lang('admin.Transportation Name')'">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name_en"
                                       class="col-xs-12 col-form-label">@lang('admin.Transportation Name En')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name_en" id="name_en"
                                       placeholder="@lang('admin.Transportation Name En')'">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="image"
                                       class="col-xs-12 col-form-label">@lang('admin.Transportation image')</label>
                            </div>
                            <div class="col-xs-5">
                                <input type="file" accept="image/*" name="image" class="dropify form-control-file"
                                       id="picture" aria-describedby="fileHelp">
                            </div>
                            <div class="col-xs-4">
                                <img class="img-responsive" src="" alt="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="capacity"
                                       class="col-xs-12 col-form-label">@lang('admin.Transportation capacity')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="number" name="capacity" id="capacity"
                                       placeholder="@lang('admin.Transportation capacity')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('admin.Update Transportation Type')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="New" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <form class="form-horizontal" action="{{route('admin.transtype.store')}}" method="post"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.Wallet')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name"
                                       class="col-xs-12 col-form-label">@lang('admin.Transportation Name')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name" id="name"
                                       placeholder="@lang('admin.Transportation Name')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name_en"
                                       class="col-xs-12 col-form-label">@lang('admin.Transportation Name En')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name_en" id="name_en"
                                       placeholder="@lang('admin.Transportation Name En')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="image"
                                       class="col-xs-12 col-form-label">@lang('admin.Transportation image')</label>
                            </div>
                            <div class="col-xs-9">
                                <input type="file" accept="image/*" name="image" class="dropify form-control-file"
                                       id="picture" aria-describedby="fileHelp">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="capacity"
                                       class="col-xs-12 col-form-label">@lang('admin.Transportation capacity')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="number" name="capacity" id="capacity"
                                       placeholder="@lang('admin.Transportation capacity')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-success">@lang('admin.Create Transportation Type')</button>
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
                    url: "{{route('admin.transtype.index')}}/" + $(this).data('id'),
                    success: function (data) {
                        $.each(data, function (key, value) {
                            $(Target_Modal).find('input[name="' + key + '"]').not('input[type="file"]').val(value);
                            $(Target_Modal).find('input[name="' + key + '"][type="file"]').closest('.row').find('img').attr('src', value);
                        });
                    }
                });
            }
        })
    </script>
@endsection