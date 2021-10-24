@extends('admin.layout.base')
@inject('models', 'App\CarModel')

@inject('model', App\Provider)
@inject('user', App\User)

<?php
$users = $user->pluck('email', 'id')->toArray();
?>

@section('title', 'Create Itinerary')

@section('content')

    <div class="content-area py-1">
        @include('flash::message')
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.itinerary.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Create Itinerary')</h5>

                {!! Form::model($model, ['action' => 'Resource\ItineraryController@store', 'files' => true]) !!}
                <input type="hidden" name="s_latitude" id="s_latitude">
                <input type="hidden" name="s_longitude" id="s_longitude">
{{--                <input type="hidden" name="s_address_ar" id="s_address_ar">--}}
                <input type="hidden" name="d_latitude" id="d_latitude">
                <input type="hidden" name="d_longitude" id="d_longitude">
{{--                <input type="hidden" name="d_address_ar" id="d_address_ar">--}}

                <div class="form-group row">
                    <label for="s_address_ar" class="col-xs-2 col-form-label">@lang('admin.start')</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="text" id="s_address_ar" name="s_address_ar"
                               required placeholder="@lang('admin.start')">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom: 20px">
                    <div id="us1" style="width: 100%; height: 400px;"></div>
                </div>

                <div class="form-group row">
                    <label for="d_address_ar" class="col-xs-2 col-form-label">@lang('admin.finish')</label>
                    <div class="col-xs-12">
                        <input class="form-control" type="text" id="d_address_ar" name="d_address_ar"
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

{{--                <div class="form-group row">--}}
{{--                    <label for="station_list" class="col-xs-2 col-form-label">{{trans('admin.station_list')}}</label>--}}
{{--                    <div class="col-xs-10">--}}
{{--                        <select name="station_list[]" multiple class="select2 js-states form-control" id="station_list">--}}
{{--                            @foreach($station_names as $key =>$name)--}}
{{--                                <option value="{{$key}}">{{ $name }}</option>--}}
{{--                            @endforeach--}}
{{--                        </select>--}}
{{--                    </div>--}}
{{--                </div>--}}


                <div class="form-group row">
                    <label for="day_trip_time_id"
                           class="col-xs-2 col-form-label">{{trans('admin.day_trip_time_id')}}</label>
                    <div class="form-group col-md-10">
                        <select class="form-control select2" name="day_trip_time_id" id="day_trip_time_id">
                            <option value="0">@lang('admin.chose_day_trip_time')</option>
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
                                <option value="{{$provider->id}}">{{$provider->email}} - {{$provider->mobile}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="car_id" class="col-xs-2 ">{{trans('admin.car_id')}}</label>
                    <div class="col-xs-10">
                        <select required class="form-control select2"
                                id="car_id"
                                name="car_id">
                            <option value="" selected>{{trans('admin.car_id')}}</option>
                        </select>
                    </div>
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

{{--                <div class="prof-sub-col col-xs-12">--}}
{{--                    <br/>--}}
{{--                    <div class="form-group">--}}
{{--                        <input tabindex="2" id="s_address_ar" class="form-control" type="text" placeholder="Enter a location" name="s_address_ar">--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-xs-12">--}}
{{--                    <div id="map"  style="width: 100%; height: 400px;margin-bottom: 30px"></div>--}}
{{--                </div>--}}

{{--                <div class="prof-sub-col col-xs-12">--}}
{{--                    <br/>--}}
{{--                    <div class="form-group">--}}
{{--                        <input tabindex="2" id="pac-input" class="form-control" type="text" placeholder="Enter a location" name="d_address_ar">--}}
{{--                    </div>--}}
{{--                </div>--}}

{{--                <div class="col-xs-12">--}}
{{--                    <div id="map2"  style="width: 100%; height: 400px;margin-bottom: 30px"></div>--}}
{{--                </div>--}}

                <div class="form-group row">
                    <label for="from_time" class="col-xs-2 col-form-label">@lang('admin.from_time')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="time" name="from_time"
                               required
                               id="from_time" placeholder="@lang('admin.from_time')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="to_time" class="col-xs-2 col-form-label">@lang('admin.to_time')</label>
                    <div class="col-xs-10">
                        <input class="form-control" type="time" name="to_time"
                               required
                               id="to_time" placeholder="@lang('admin.to_time')">
                    </div>
                </div>

                <div class="form-group row">
                    <label for="zipcode" class="col-xs-2 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">@lang('admin.Create Itinerary')</button>
                        <a href="{{route('admin.itinerary.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
@section('scripts')
{{--    <script type="text/javascript" src='https://maps.google.com/maps/api/js?sensor=false&libraries=places&key=AIzaSyBwxuW2cdXbL38w9dcPOXfGLmi1J7AVVB8'></script>--}}

    <?php
    $s_latitude = !empty(old('s_latitude')) ? old('s_latitude'):'30.06515397591118';
    $s_longitude = !empty(old('s_longitude')) ? old('s_longitude'):'31.226250648498528';
    $d_latitude = !empty(old('d_latitude')) ? old('d_latitude'):'30.06515397591118';
    $d_longitude = !empty(old('d_longitude')) ? old('d_longitude'):'31.226250648498528';
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



@endsection

@section('styles')
    <style type="text/css">
        #map {
            height: 100%;
            min-height: 400px;
        }

        .controls {
            border: 1px solid transparent;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            height: 32px;
            outline: none;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.3);
            margin-bottom: 10px;
        }

        #pac-input2 {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 100%;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }
    </style>
@endsection