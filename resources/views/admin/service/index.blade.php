@extends('admin.layout.base')

@section('title', 'Service Types ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.Service Types')</h5>
                @can('اضافه أنواع الخدمات')
                    <button class="btn btn-success pull-right Get_Modal" style="margin-left: 1em;" data-toggle="modal"
                            data-target="#New"><i class="fa fa-plus"></i> @lang('admin.New')</button>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        {{-- <button onclick="changeStatus(5)"> Alo </button> --}}
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Status')</th>

                        <th>@lang('admin.Service Name Ar')</th>
                        <th>@lang('admin.Service Name En')</th>
                        <th>@lang('admin.Base Price')</th>
                        <th>@lang('admin.Base Distance')</th>
                        <th>@lang('admin.Base Waiting')</th>
                        <th>@lang('admin.Distance Price')</th>
                        <th>@lang('admin.Time Price')</th>
                        <th>@lang('admin.Waiting Price')</th>
                        <th>@lang('admin.sub_com')</th>
                        <th>@lang('admin.Price Calculation')</th>
                        <th>@lang('admin.Service Image')</th>
                        @can('تعديل أنواع الخدمات')
                            <th>@lang('admin.Action')</th>
                        @endcan
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($services as $index => $service)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <button onclick="changeStatus($service->id)> Alo </button>
                                <div class="col-xs-6">
                                    <input id="stripe_check " onchange="changeStatus($service->id)"
                                           {{$service->status?'checked':''}} data-id="{{$service->id}}"
                                           data-model="ServiceType" type="checkbox" class="js-switch Change_Status"
                                           data-color="#43b968">
                                </div>
                            </td>
                            <td>{{ $service->name }}</td>
                            <td>{{ $service->name_en }}</td>
                            <td>{{ currency($service->fixed) }}</td>
                            <td>{{ distance($service->distance) }}</td>
                            <td>{{ $service->waiting }}</td>
                            <td>{{ currency($service->price) }}</td>

                            <td>{{ currency($service->minute) }}</td>
                            <td>{{ currency($service->min_wait_price) }}</td>
                            <td>{{ currency($service->sub_com) }}</td>
                            <td>@lang('servicetypes.'.$service->calculator)</td>
                            <td>
                                @if($service->image)
                                    <img src="{{url('/').$service->image}}" style="height: 50px">
                                @else
                                    N/A
                                @endif
                            </td>

                            @can('تعديل أنواع الخدمات')
                                <td>
                                    <div class="input-group-btn">
                                        <a class="btn btn-success Get_Modal" data-id="{{$service->id}}"
                                           data-toggle="modal"
                                           data-target="#Edit"><i class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                    </div>
                                </td>
                            @endcan
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Status')</th>

                        <th>@lang('admin.Service Name Ar')</th>
                        <th>@lang('admin.Service Name En')</th>
                        <th>@lang('admin.Base Price')</th>
                        <th>@lang('admin.Base Distance')</th>
                        <th>@lang('admin.Base Waiting')</th>
                        <th>@lang('admin.Distance Price')</th>
                        <th>@lang('admin.Time Price')</th>
                        <th>@lang('admin.Waiting Price')</th>
                        <td>{{ currency($service->sub_com) }}</td>

                        <th>@lang('admin.Price Calculation')</th>
                        <th>@lang('admin.Service Image')</th>
                        @can('تعديل أنواع الخدمات')
                            <th>@lang('admin.Action')</th>
                        @endcan
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
                <form class="form-horizontal" action="{{route('admin.service.index')}}" method="post"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.Edit Service Type')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <input type="hidden" name="id">

                        <div class="form-group row">
                            <div class="col-md-3">
                                <label for="transportation_type_id">{{trans('admin.transportation_type_id')}}</label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control select3" style="width: 100%;" name="transportation_type_id" id="transportation_type_id">
                                    <option value="0">@lang('admin.chose_transportation_type')</option>
                                    @foreach($transportation_types as $type)
                                        <option value="{{$type->id}}" {{ $type->id == $service->transportation_type_id ? 'selected' : '' }}>{{$type->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name"
                                       class="col-xs-12 col-form-label">@lang('admin.Service Name Ar')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('name') }}" name="name" required
                                       id="name" placeholder="@lang('admin.Service Name Ar')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name_en"
                                       class="col-xs-12 col-form-label">@lang('admin.Service Name En')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('name_en') }}" name="name_en"
                                       required id="name_en" placeholder="@lang('admin.Service Name En')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="picture"
                                       class="col-xs-12 col-form-label">@lang('admin.Service Image')</label>
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
                                <label for="fixed" class="col-xs-12 col-form-label">@lang('admin.Base Price')
                                    ({{ currency() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('fixed') }}" name="fixed" required
                                       id="fixed" placeholder="@lang('admin.Base Price')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="distance" class="col-xs-12 col-form-label">@lang('admin.Base Distance')
                                    ({{ distance() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('distance') }}" name="distance"
                                       required id="distance" placeholder="@lang('admin.Base Distance')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="minute" class="col-xs-12 col-form-label">@lang('admin.Unit Time Pricing')
                                    (@lang('admin.For Rental amount per hour') / 60) ({{ currency() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('minute') }}" name="minute"
                                       required id="minute" placeholder="@lang('admin.Unit Time Pricing')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="price" class="col-xs-12 col-form-label">@lang('admin.Unit Distance Price')
                                    ({{ distance() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('price') }}" name="price" required
                                       id="price" placeholder="@lang('admin.Unit Distance Price')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="sub_com" class="col-xs-12 col-form-label">@lang('admin.sub_com')
                                    ({{ distance() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('sub_com') }}" name="sub_com" required
                                       id="sub_com" placeholder="@lang('admin.sub_com')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="waiting"
                                       class="col-xs-12 col-form-label">@lang('admin.Base Waiting')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('waiting') }}" name="waiting"
                                       required id="waiting"
                                       placeholder="@lang('admin.Base Waiting') ({{ currency() }})">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="min_wait_price"
                                       class="col-xs-12 col-form-label">@lang('admin.Waiting Per Minute')
                                    ({{ currency() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('fixed') }}" name="min_wait_price"
                                       required id="min_wait_price" placeholder="@lang('admin.Waiting Per Minute')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="calculator"
                                       class="col-xs-12 col-form-label">@lang('admin.Pricing Logic')</label>
                            </div>
                            <div class="col-xs-9">
                                <select class="form-control" id="calculator" name="calculator">
                                    <option value="MIN">@lang('servicetypes.MIN')</option>
                                    <option value="HOUR">@lang('servicetypes.HOUR')</option>
                                    <option value="DISTANCE">@lang('servicetypes.DISTANCE')</option>
                                    <option value="DISTANCEMIN">@lang('servicetypes.DISTANCEMIN')</option>
                                    <option value="DISTANCEHOUR">@lang('servicetypes.DISTANCEHOUR')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="description"
                                       class="col-xs-12 col-form-label">@lang('admin.Description')</label>
                            </div>
                            <div class="col-xs-9">
                                <textarea class="form-control" type="floa" value="{{ old('description') }}"
                                          name="description" required id="description"
                                          placeholder="@lang('admin.Description')" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('admin.Update Service Type')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="New" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <form class="form-horizontal" action="{{route('admin.service.store')}}" method="post"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.New')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="transportation_type_id">{{trans('admin.transportation_type_id')}}</label>
                            </div>
                            <div class="col-md-9">
                                <select class="form-control select2" style="width: 100%;" name="transportation_type_id"
                                        id="transportation_type_id">
                                    <option value="0">@lang('admin.chose_day_trip_time')</option>
                                    @foreach($transportation_types as $type)
                                        <option value="{{$type->id}}">{{$type->name}}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('transportation_type_id'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('transportation_type_id')}}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name"
                                       class="col-xs-12 col-form-label">@lang('admin.Service Name Ar')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('name') }}" name="name" required
                                       id="name" placeholder="@lang('admin.Service Name Ar')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name_en"
                                       class="col-xs-12 col-form-label">@lang('admin.Service Name En')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('name_en') }}" name="name_en"
                                       required id="name_en" placeholder="@lang('admin.Service Name En')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="picture"
                                       class="col-xs-12 col-form-label">@lang('admin.Service Image')</label>
                            </div>
                            <div class="col-xs-9">
                                <input type="file" accept="image/*" name="image" class="dropify form-control-file"
                                       id="picture" aria-describedby="fileHelp">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="fixed" class="col-xs-12 col-form-label">@lang('admin.Base Price')
                                    ({{ currency() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('fixed') }}" name="fixed" required
                                       id="fixed" placeholder="@lang('admin.Base Price')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="distance" class="col-xs-12 col-form-label">@lang('admin.Base Distance')
                                    ({{ distance() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('distance') }}" name="distance"
                                       required id="distance" placeholder="@lang('admin.Base Distance')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="minute" class="col-xs-12 col-form-label">@lang('admin.Unit Time Pricing')
                                    (@lang('admin.For Rental amount per hour') / 60) ({{ currency() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('minute') }}" name="minute"
                                       required id="minute" placeholder="@lang('admin.Unit Time Pricing')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="price" class="col-xs-12 col-form-label">@lang('admin.Unit Distance Price')
                                    ({{ distance() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('price') }}" name="price" required
                                       id="price" placeholder="@lang('admin.Unit Distance Price')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="sub_com" class="col-xs-12 col-form-label">@lang('admin.sub_com')
                                    ({{ distance() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('sub_com') }}" name="sub_com" required
                                       id="sub_com" placeholder="@lang('admin.sub_com')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="waiting"
                                       class="col-xs-12 col-form-label">@lang('admin.Base Waiting')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('waiting') }}" name="waiting"
                                       required id="waiting"
                                       placeholder="@lang('admin.Base Waiting') ({{ currency() }})">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="min_wait_price"
                                       class="col-xs-12 col-form-label">@lang('admin.Waiting Per Minute')
                                    ({{ currency() }})</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" value="{{ old('fixed') }}" name="min_wait_price"
                                       required id="min_wait_price" placeholder="@lang('admin.Waiting Per Minute')">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="calculator"
                                       class="col-xs-12 col-form-label">@lang('admin.Pricing Logic')</label>
                            </div>
                            <div class="col-xs-9">
                                <select class="form-control" id="calculator" name="calculator">
                                    <option value="MIN">@lang('servicetypes.MIN')</option>
                                    <option value="HOUR">@lang('servicetypes.HOUR')</option>
                                    <option value="DISTANCE">@lang('servicetypes.DISTANCE')</option>
                                    <option value="DISTANCEMIN">@lang('servicetypes.DISTANCEMIN')</option>
                                    <option value="DISTANCEHOUR">@lang('servicetypes.DISTANCEHOUR')</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="description"
                                       class="col-xs-12 col-form-label">@lang('admin.Description')</label>
                            </div>
                            <div class="col-xs-9">
                                <textarea class="form-control" type="floa" value="{{ old('description') }}"
                                          name="description" required id="description"
                                          placeholder="@lang('admin.Description')" rows="4"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-success">@lang('admin.Create Service Type')</button>
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
                    url: "{{route('admin.service.index')}}/" + $(this).data('id'),
                    success: function (data) {
                        $.each(data, function (key, value) {
                            $(Target_Modal).find('input[name="' + key + '"]').not('input[type="file"]').val(value);
                            $(Target_Modal).find('textarea[name="' + key + '"]').text(value);
                            $(Target_Modal).find('select[name="' + key + '"]').val(value).change();
                            $(Target_Modal).find('input[name="' + key + '"][type="file"]').closest('.row').find('img').attr('src', value);
                        });
                    }
                });
            }
        })

        function changeStatus($id) {
            //alert("Alo");
            $.ajax({
                type: 'post',
                data: {service_id: $id, status: $("#stripe_check").val()},
                url: "{{'admin/service/changeStatus'}}",
                success: function (data) {
                    alert(data);
                }
            });
        }
    </script>
@endsection
