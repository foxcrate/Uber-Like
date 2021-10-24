@extends('admin.layout.base')
@inject('models', 'App\CarModel')

@inject('user', App\User)
@inject('station', App\Station)

<?php
$users = $user->pluck('email', 'id')->toArray();
?>

@section('title', 'Update Itinerary')

@section('content')

    <div class="content-area py-1">
        @include('flash::message')
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.itinerary.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Update Itinerary')</h5>

                {!! Form::model($model, ['action' => ['Resource\ItineraryController@update',$model->id], 'method' =>'put', 'files' => true]) !!}
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="s_latitude" id="s_latitude">
                <input type="hidden" name="s_longitude" id="s_longitude">
{{--                <input type="hidden" name="s_address_ar" id="s_address_ar">--}}
                <input type="hidden" name="d_latitude" id="d_latitude">
                <input type="hidden" name="d_longitude" id="d_longitude">
{{--                <input type="hidden" name="d_address_ar" id="d_address_ar">--}}
                <div class="form-group row">
                    <label for="s_address_ar" class="col-xs-2 col-form-label">@lang('admin.start')</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="text" value="{{ $model->s_address_ar }}" name="s_address_ar"  id="s_address_ar"
                               required placeholder="@lang('admin.start')">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 20px">
                    <div id="us1" style="width: 100%; height: 400px;"></div>
                </div>

                <div class="form-group row">
                    <label for="d_address_ar" class="col-xs-2 col-form-label">@lang('admin.finish')</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="text" value="{{ $model->d_address_ar }}" name="d_address_ar" id="d_address_ar"
                               required placeholder="@lang('admin.finish')">
                    </div>
                </div>
                <div class="form-group" style="margin-bottom: 50px">
                    <div id="us2" style="width: 100%; height: 400px;"></div>
                </div>

                <div class="form-group row">
                    <label for="user_list" class="col-xs-2">{{trans('admin.user_list')}}</label>
                    <div class="col-xs-10">
                        {!! Form::select('user_list[]',$users,null, [
                            'class'=>'select2 js-states form-control',
                            'multiple' => 'multiple',
                        ]) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="station_list" class="col-xs-2">{{trans('admin.station_list')}}</label>
                    <div class="col-xs-10">
                        {!! Form::select('station_list[]',$station_names,null, [
                            'class'=>'select2 js-states form-control',
                            'multiple' => 'multiple',
                        ]) !!}
                    </div>
                </div>

                <div class="form-group row">
                    <label for="day_trip_time_id"
                           class="col-xs-2 col-form-label">{{trans('admin.day_trip_time_id')}}</label>
                    <div class="form-group col-md-10">
                        <select class="form-control select2" name="day_trip_time_id" id="day_trip_time_id">
                            <option value="0">@lang('user.chose_day_trip_time')</option>
                            @foreach( $day_trip_times as $type)
                                <option value="{{$type->id}}" {{ $type->id == $model->day_trip_time_id ? 'selected' : '' }}>{{$type->period}}
                                    - {{$type->day}}</option>
                            @endforeach
                        </select>
                        @if ($errors->has('day_trip_time_id'))
                            <span class="help-block">
                                    <strong>{{ $errors->first('day_trip_time_id')}}</strong>
                                </span>
                        @endif
                    </div>
                </div>

                <div class="form-group row">
                    <label for="provider_id" class="col-xs-2">{{trans('admin.provider_id')}}</label>
                    <div class="col-xs-10">
                        <select class="form-control select2"
                                id="provider_id"
                                name="provider_id">
                            <option value="0">لايوجد</option>
                            @foreach($providers as $provider)
                                <option value="{{$provider->id}}" {{ $provider->id == $model->provider_id ? 'selected' : '' }} >{{$provider->email}}
                                    - {{$provider->mobile}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-xs-2 col-form-label" for="car_id">{{trans('admin.car_id')}}</label>
                    <div class="col-xs-10">
                        <select required class="form-control select2" id="car_id" name="car_id">
                            <option value="0">لايوجد</option>
                            @foreach($cars as $index)
                                <option value="{{ $index->id}}" {{ $index->id == $model->car_id ? 'selected' : '' }}>{{ $index->car_code }}
                                    - {{ $index->car_number }} </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('car_id'))
                        <span class="help-block">
                                            <strong>{{ $errors->first('car_id')}}</strong>
                                        </span>
                    @endif
                </div>


                <div class="form-group row">
                    <label class="col-xs-2 col-form-label"
                           for="transportation_type_id">{{trans('admin.transportation_type_id')}}</label>
                    <div class="col-xs-10">
                        <select required class="form-control select2" id="transportation_type_id"
                                name="transportation_type_id">
                            <option value="0">لايوجد</option>
                            @foreach($transportation_types as $index)
                                <option value="{{ $index->id}}" {{ $index->id == $model->transportation_type_id ? 'selected' : '' }}>{{ $index->name }} </option>
                            @endforeach
                        </select>
                    </div>
                    @if ($errors->has('transportation_type_id'))
                        <span class="help-block">
                            <strong>{{ $errors->first('transportation_type_id')}}</strong>
                        </span>
                    @endif
                </div>

                <div class="form-group row">
                    <label for="from_time" class="col-xs-2 col-form-label">@lang('admin.from_time')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="time" value="{{ $model->from_time }}" name="from_time"
                               required
                               id="from_time" placeholder="@lang('admin.from_time')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="to_time" class="col-xs-2 col-form-label">@lang('admin.to_time')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="time" value="{{ $model->to_time }}" name="to_time"
                               required
                               id="to_time" placeholder="@lang('admin.to_time')">
                    </div>
                </div>

{{--                <div class="form-group row">--}}
{{--                    <label for="number_station"--}}
{{--                           class="col-xs-2 col-form-label">@lang('admin.number_station')</label>--}}
{{--                    <div class="col-xs-10">--}}
{{--                        <input class="form-control" type="text" value="{{ $model->number_station }}"--}}
{{--                               name="number_station"--}}
{{--                               required--}}
{{--                               id="number_station" placeholder="@lang('admin.number_station')">--}}
{{--                    </div>--}}
{{--                </div>--}}

                <div class="form-group row" style="margin-top: 20px">
                    <label for="zipcode" class="col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">@lang('admin.Update Itinerary')</button>
                        <a href="{{route('admin.itinerary.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <?php
    $s_latitude = !empty($model->s_latitude) ? $model->s_latitude : '30.06515397591118';
    $s_longitude = !empty($model->s_longitude) ? $model->s_longitude : '31.226250648498528';
    $d_latitude = !empty($model->d_latitude) ? $model->d_latitude : '30.06515397591118';
    $d_longitude = !empty($model->d_longitude) ? $model->d_longitude : '31.226250648498528';
    //    $s_address_ar = !empty(old('s_address_ar')) ? old('s_address_ar'):'1191 Nile Corniche, Souq Al ASR, Bulaq, Cairo Governorate, Egypt';
    ?>

    <script>
        $('#us1').locationpicker({
            location: {
                latitude: {!! $s_latitude !!},
                longitude: {!! $s_longitude !!},
                {{--address: {!! $s_address_ar !!},--}}
            },
            radius: 300,
            markerIcon: "{{ asset('main/vendor/jquery/map-marker-2-xl.png') }}",
            inputBinding: {
                latitudeInput: $('#s_latitude'),
                longitudeInput: $('#s_longitude'),
                // radiusInput: $('#us2-radius'),
                locationNameInput: $('#s_address_ar')
            },
            enableAutocomplete: true

        });
        $('#us2').locationpicker({
            location: {
                latitude: {!! $d_latitude !!},
                longitude: {!! $d_longitude !!},
                {{--address: {!! $s_address_ar !!},--}}
            },
            radius: 300,
            markerIcon: "{{ asset('main/vendor/jquery/map-marker-2-xl.png') }}",
            inputBinding: {
                latitudeInput: $('#d_latitude'),
                longitudeInput: $('#d_longitude'),
                // radiusInput: $('#us2-radius'),
                locationNameInput: $('#d_address_ar')
            },
            enableAutocomplete: true

        });

        $("#provider_id").change(function () {
            var provider_id = $("#provider_id").val();
            // alert('ddddd');
            var url = "{{url('api/cars?provider_id=')}}" + provider_id;
            // console.log(url);
            $.ajax({
                url: url,
                type: 'get',
                dataType: 'json',
                success: function (data) {
                    $('#car_id').empty();
                    var option = '<option value="">{{trans('admin.car_id')}}</option>';
                    $("#car_id").append(option);
                    $.each(data.data, function (index, car) {
                        var option = '<option value="' + car.id + '">' + car.car_code + car.car_number + '</option>';
                        $("#car_id").append(option);
                    });
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(textStatus, errorThrown);
                }
            });
        });
    </script>
@endpush