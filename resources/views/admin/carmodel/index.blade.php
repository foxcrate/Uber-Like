@extends('admin.layout.base')

@section('title', 'Car Models')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.Car Models')</h5>
                @can('اضافه موديل السياره')
                    <button class="btn btn-success pull-right Get_Modal" style="margin-left: 1em;" data-toggle="modal"
                            data-target="#New"><i class="fa fa-plus"></i> @lang('admin.New Car Model')</button>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Model Name Ar')</th>
                        <th>@lang('admin.Model Name En')</th>
                        <th>@lang('admin.Model Production Date')</th>
                        <th>@lang('admin.Transportation Type')</th>
                        <th>@lang('admin.Service Type')</th>
                        <th>@lang('admin.Car Class')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل موديل السياره') || auth()->user()->can('حذف موديل السياره'))
                            <th style="width:100px;">@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($CarModels as $index => $CarModel)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $CarModel->name }}</td>
                            <td>{{ $CarModel->name_en }}</td>
                            <td>{{ $CarModel->date }}</td>
                            <td>{{ $CarModel->TransType['name']}}</td>
                            <td>{{ $CarModel->Service['name']}}</td>
                            <td>{{ $CarModel->CarClass['name']}}</td>
                            <td>
                                <div class="col-xs-6">
                                    <input id="stripe_check "
                                           {{$CarModel->status?'checked':''}} data-id="{{$CarModel->id}}"
                                           data-model="CarModel" type="checkbox" class="js-switch Change_Status"
                                           data-color="#43b968">
                                </div>
                            </td>
                            @if(auth()->user()->can('تعديل موديل السياره') || auth()->user()->can('حذف موديل السياره'))
                                <td>
                                    <div class="input-group-btn">
                                        <button type="button"
                                                class="btn btn-info dropdown-toggle"
                                                data-toggle="dropdown">@lang('admin.Action')
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @can('تعديل موديل السياره')

                                                <li>
                                                    <a class="btn btn-default Get_Modal" data-id="{{$CarModel->id}}"
                                                       data-toggle="modal" data-target="#Edit"><i
                                                                class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                                </li>
                                            @endcan
                                            @can('حذف موديل السياره')
                                                <li>
                                                    <form action="{{ route('admin.carmodel.destroy', $CarModel->id) }}"
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
                        <th>@lang('admin.Model Name Ar')</th>
                        <th>@lang('admin.Model Name En')</th>
                        <th>@lang('admin.Model Production Date')</th>
                        <th>@lang('admin.Transportation Type')</th>
                        <th>@lang('admin.Service Type')</th>
                        <th>@lang('admin.Car Class')</th>
                        <th>@lang('admin.Status')</th>
                        @if(auth()->user()->can('تعديل موديل السياره') || auth()->user()->can('حذف موديل السياره'))
                            <th style="width:100px;">@lang('admin.Action')</th>
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
                <form class="form-horizontal" action="{{route('admin.carmodel.store')}}" method="post"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.Edit Car Model')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <input type="hidden" name="id">
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name" class="col-xs-12 col-form-label">@lang('admin.Model Name Ar')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name" id="name"
                                       placeholder="@lang('admin.Model Name Ar')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name" class="col-xs-12 col-form-label">@lang('admin.Model Name En')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name_en" id="name_en"
                                       placeholder="@lang('admin.Model Name En')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="date"
                                       class="col-xs-12 col-form-label">@lang('admin.Production Date')</label>
                            </div>
                            <div class="col-xs-9">
                                <select class="form-control" name="date" required id="date">
                                    <option value="2004">2004</option>
                                    <option value="2005">2005</option>
                                    <option value="2006">2006</option>
                                    <option value="2007">2007</option>
                                    <option value="2008">2008</option>
                                    <option value="2009">2009</option>
                                    <option value="2010">2010</option>
                                    <option value="2011">2011</option>
                                    <option value="2012">2012</option>
                                    <option value="2013">2013</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="transtype_id"
                                       class="col-xs-12 col-form-label">@lang('admin.Transportation Type')</label>
                            </div>
                            <div class="col-xs-9">
                                <select class="form-control" name="transtype_id" required id="transtype_id">
                                    @foreach($Transtypes as $Transtype)
                                        <option value="{{$Transtype->id}}">{{$Transtype->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="service_id"
                                       class="col-xs-12 col-form-label">@lang('admin.Service Type')</label>
                            </div>
                            <div class="col-xs-9">
                                <select class="form-control" name="service_id" required id="service_id">
                                    @foreach($Services as $Service)
                                        <option value="{{$Service->id}}">{{$Service->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="carclass_id"
                                       class="col-xs-12 col-form-label">@lang('admin.Car Class')</label>
                            </div>
                            <div class="col-xs-9">
                                <select class="form-control" name="carclass_id" required id="carclass_id">
                                    @foreach($CarClasses as $CarClass)
                                        <option value="{{$CarClass->id}}">{{$CarClass->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-primary">@lang('admin.Update Car Model')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="New" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">
            <!-- Modal content-->
            <div class="modal-content">
                <form class="form-horizontal" action="{{route('admin.carmodel.store')}}" method="post"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.New Car Model')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name" class="col-xs-12 col-form-label">@lang('admin.Model Name Ar')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name" id="name"
                                       placeholder="@lang('admin.Model Name Ar')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="name" class="col-xs-12 col-form-label">@lang('admin.Model Name En')</label>
                            </div>
                            <div class="col-xs-9">
                                <input class="form-control" type="text" name="name_en" id="name_en"
                                       placeholder="@lang('admin.Model Name En')">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="date"
                                       class="col-xs-12 col-form-label">@lang('admin.Production Date')</label>
                            </div>
                            <div class="col-xs-9">
                                <select class="form-control select2" name="date" required id="date">
                                    <option value="2004">2004</option>
                                    <option value="2005">2005</option>
                                    <option value="2006">2006</option>
                                    <option value="2007">2007</option>
                                    <option value="2008">2008</option>
                                    <option value="2009">2009</option>
                                    <option value="2010">2010</option>
                                    <option value="2011">2011</option>
                                    <option value="2012">2012</option>
                                    <option value="2013">2013</option>
                                    <option value="2014">2014</option>
                                    <option value="2015">2015</option>
                                    <option value="2016">2016</option>
                                    <option value="2017">2017</option>
                                    <option value="2018">2018</option>
                                    <option value="2019">2019</option>
                                    <option value="2020">2020</option>
                                    <option value="2021">2021</option>
                                    <option value="2022">2022</option>
                                    <option value="2023">2023</option>
                                    <option value="2024">2024</option>
                                    <option value="2025">2025</option>
                                    <option value="2026">2026</option>
                                    <option value="2027">2027</option>
                                    <option value="2028">2028</option>
                                    <option value="2029">2029</option>
                                    <option value="2030">2030</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="transtype_id"
                                       class="col-xs-12 col-form-label">@lang('admin.Transportation Type')</label>
                            </div>
                            <div class="col-xs-9">
                                <select class="form-control" name="transtype_id" required id="transtype_id">
                                    @foreach($Transtypes as $Transtype)
                                        <option value="{{$Transtype->id}}">{{$Transtype->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="service_id"
                                       class="col-xs-12 col-form-label">@lang('admin.Service Type')</label>
                            </div>
                            <div class="col-xs-9">
                                <select class="form-control" name="service_id" required id="service_id">
                                    @foreach($Services as $Service)
                                        <option value="{{$Service->id}}">{{$Service->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-3">
                                <label for="carclass_id"
                                       class="col-xs-12 col-form-label">@lang('admin.Car Class')</label>
                            </div>
                            <div class="col-xs-9">
                                <select class="form-control" name="carclass_id" required id="carclass_id">
                                    @foreach($CarClasses as $CarClass)
                                        <option value="{{$CarClass->id}}">{{$CarClass->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-success">@lang('admin.Create Car Model')</button>
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
                    url: "{{route('admin.carmodel.index')}}/" + $(this).data('id'),
                    success: function (data) {
                        $.each(data, function (key, value) {
                            $(Target_Modal).find('input[name="' + key + '"]').not('input[type="file"]').val(value);
                            $(Target_Modal).find('select[name="' + key + '"]').val(value).change();
                            $(Target_Modal).find('input[name="' + key + '"][type="file"]').closest('.row').find('img').attr('src', value);
                        });
                    }
                });
            }
        })
    </script>
@endsection