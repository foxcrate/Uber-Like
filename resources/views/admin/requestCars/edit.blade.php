@extends('admin.layout.base')

@section('title', 'Add Car ')

@section('content')
    <style>
        @if(lang()=='ar')
        form{
            direction: rtl;
            text-align: center;
        }
        .btn_div{
            text-align: right;
            direction: rtl;
        }
        @endif
        .color_red{
            font-size: large;
            color: #ff00000;

        }
        .h2_la{
            margin-top: 15px;
            padding-bottom: 30px;
            color: #0db02b;
        }
        .input_us{
            max-width: 50%;
            margin: 0 25%;

        }
        .image_cars{
            display: block;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            margin: 5px 40% 15px;
        }
    </style>
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.order.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> {{__('admin.back')}}</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Update car')</h5>

                <form class="form-horizontal" action="{{route('admin.order.update', $car->id)}}" method="POST" enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    <div class="form-group input_us">
                        <label>العارض</label><span class="color_red">*</span>
                        <select  class="form-control" disabled>
                            <option value="{{$car->user-> id }}" selected="selected">{{$car->user->email}}</option>
                        </select>
                    </div>
                    <h2 class="text-center h2_la">@lang('user.car_inf')</h2>
                    <input type="hidden"  value="{{$car->lat }}" id="latitude" name="latitude">
                    <input type="hidden" value="{{$car->long }}" id="longitude"  name="longitude">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('user.car_model')</label><span class="color_red">*</span>
                                <select name="id_models" class="form-control" id="country">
                                    @isset($cars_models)
                                        @foreach($cars_models as $cars_model)
                                            <option  value="{{$cars_model->id}}" {{$cars_model->id == $car->id_models? 'selected': ''}}>{{$cars_model->name}} - {{$cars_model->date}}</option>
                                        @endforeach
                                    @endisset
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('user.car_number_seats')</label><span class="color_red">*</span>
                                <input class="form-control" value="{{$car->number_seats}}" type="number" name="number_seats" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('user.color_car')</label><span class="color_red">*</span>
                                <input type="color" id="favcolor" name="color"  value="{{$car->color}}" style="display: block; width: 100%; height: 35px;">

                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('user.gearbox')</label><span class="color_red">*</span>
                                <select name="gearbox" class="form-control" id="country">
                                    <option value="1" {{$car->gearbox==1? 'selected': ''}} >@lang('user.mun')</option>
                                    <option value="2"{{$car->gearbox==2? 'selected': ''}}> @lang('user.auto_dr_mun')</option>
                                    <option value="3"{{$car->gearbox==3? 'selected': ''}}> @lang('user.auto_dr')</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>  @lang('user.full_type') </label><span class="color_red">*</span>
                                <select name="full_type" class="form-control" id="country">
                                    <option value="1"{{$car->full_type==1? 'selected': ''}}>@lang('user.car_pt')</option>
                                    <option value="2"{{$car->full_type==2? 'selected': ''}}>@lang('user.car_Jazz')</option>
                                    <option value="3"{{$car->full_type==3? 'selected': ''}}>@lang('user.car_ghaz')</option>
                                    <option value="4"{{$car->full_type==4? 'selected': ''}}>@lang('user.car_el')</option>

                                </select>
                            </div>
                        </div>
                        {{$car->price}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>@lang('user.car_price')</label><span class="color_red">*</span>
                                <input class="form-control" type="text" name="price" value="{{$car->price}}" required placeholder="">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>@lang('admin.governorate_id')</label><span class="color_red">*</span>
                                    <select name="id_governorate" class="form-control" id="country">
                                        @isset($governorates)
                                            @foreach($governorates as $governorate)
                                                @if(app()->getLocale()=="en")
                                                    <option value="{{$governorate->id}}" {{$car->id_governorate== $governorate->id ? 'selected': ''}}>{{$governorate->name_en}}</option>
                                                @else
                                                    <option value="{{$governorate->id}}" {{$car->id_governorate== $governorate->id ? 'selected': ''}}>{{$governorate->name}}</option>
                                                @endif
                                            @endforeach
                                        @endisset
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="projectinput1"> @lang('user.Address')   </label>
                                    <input type="text" id="pac-input"
                                           class="form-control"
                                           placeholder="  " value="{{$car->address }}" name="address">
                                    @error("address")
                                    <span class="text-danger"> {{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <div id="map" style="height: 250px;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group group">
                                <div class="text-group">
                                    <label for="textarea" class="input-label bar22">@lang('user.car_note_ar')</label><i class="bar"></i>

                                    <textarea required="required"name="note_ar" class="form-control" rows="9" id="myeditor">{{$car->note_ar}} </textarea>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group group">
                                <div class="text-group">
                                    <label for="textarea" class="input-label bar22">@lang('user.car_note_en')</label><i class="bar"></i>

                                    <textarea required="required" name="note_en" class="form-control" rows="9" id="myeditor1"> {{$car->note_en}}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>


                    <h2 class="text-center h2_la"> @lang('user.car_photo')</h2>


                    <div class="form-group row">
                        <div class="col-md-6">
                            @if($car->photo1 != null)
                                 <img class="image_cars"  class="image_cars" src="{{asset($car->photo1)}}">
                            @endif
                            <div class="col-xs-10">
                                <input type="file" accept="image/*" name="photo" class="dropify form-control-file" id="photo" aria-describedby="fileHelp">
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($car->photo2 != null)
                                 <img class="image_cars"  src="{{asset($car->photo2)}}">
                            @endif
                            <div class="col-xs-10">
                                <input type="file" accept="image/*" name="photo2" class="dropify form-control-file" id="photo2" aria-describedby="fileHelp">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">

                        <div class="col-md-6">
                            @if($car->photo3 != null)
                                <img class="image_cars"  src="{{asset($car->photo3)}}">
                            @endif
                            <div class="col-xs-10">
                                <input type="file" accept="image/*" name="photo3" class="dropify form-control-file" id="photo3" aria-describedby="fileHelp">
                            </div>
                        </div>
                        <div class="col-md-6">
                            @if($car->photo4 != null)
                             <img class="image_cars"  src="{{asset($car->photo4)}}">
                            @endif
                            <div class="col-xs-10">
                                <input type="file" accept="image/*" name="photo4" class="dropify form-control-file" id="photo4" aria-describedby="fileHelp">
                            </div>
                        </div>
                    </div>
                    <div class="form-group btn_div row">
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">@lang('admin.Save changes')</button>
                            <a href="{{route('admin.car.index')}}" class="btn btn-default">@lang('admin.cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('myeditor');
        CKEDITOR.replace('myeditor1');
    </script>
    <script>
        $("#pac-input").focusin(function() {
            $(this).val('');
        });
        $('#latitude').val('');
        $('#longitude').val('');
        // This example adds a search box to a map, using the Google Place Autocomplete
        // feature. People can enter geographical searches. The search box will return a
        // pick list containing a mix of places and predicted search terms.
        // This example requires the Places library. Include the libraries=places
        // parameter when you first load the API. For example:
        // <script src="https://maps.googleapis.com/maps/api/js?key=YOUR_API_KEY&libraries=places">
        function initAutocomplete() {
            var map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: 24.740691, lng: 46.6528521 },
                zoom: 13,
                mapTypeId: 'roadmap'
            });
            // move pin and current location
            infoWindow = new google.maps.InfoWindow;
            geocoder = new google.maps.Geocoder();
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    var pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };
                    map.setCenter(pos);
                    var marker = new google.maps.Marker({
                        position: new google.maps.LatLng(pos),
                        map: map,
                        title: 'موقعك الحالي'
                    });
                    markers.push(marker);
                    marker.addListener('click', function() {
                        geocodeLatLng(geocoder, map, infoWindow,marker);
                    });
                    // to get current position address on load
                    google.maps.event.trigger(marker, 'click');
                }, function() {
                    handleLocationError(true, infoWindow, map.getCenter());
                });
            } else {
                // Browser doesn't support Geolocation
                console.log('dsdsdsdsddsd');
                handleLocationError(false, infoWindow, map.getCenter());
            }
            var geocoder = new google.maps.Geocoder();
            google.maps.event.addListener(map, 'click', function(event) {
                SelectedLatLng = event.latLng;
                geocoder.geocode({
                    'latLng': event.latLng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            deleteMarkers();
                            addMarkerRunTime(event.latLng);
                            SelectedLocation = results[0].formatted_address;
                            console.log( results[0].formatted_address);
                            splitLatLng(String(event.latLng));
                            $("#pac-input").val(results[0].formatted_address);
                        }
                    }
                });
            });
            function geocodeLatLng(geocoder, map, infowindow,markerCurrent) {
                var latlng = {lat: markerCurrent.position.lat(), lng: markerCurrent.position.lng()};
                /* $('#branch-latLng').val("("+markerCurrent.position.lat() +","+markerCurrent.position.lng()+")");*/
                $('#latitude').val(markerCurrent.position.lat());
                $('#longitude').val(markerCurrent.position.lng());
                geocoder.geocode({'location': latlng}, function(results, status) {
                    if (status === 'OK') {
                        if (results[0]) {
                            map.setZoom(8);
                            var marker = new google.maps.Marker({
                                position: latlng,
                                map: map
                            });
                            markers.push(marker);
                            infowindow.setContent(results[0].formatted_address);
                            SelectedLocation = results[0].formatted_address;
                            $("#pac-input").val(results[0].formatted_address);
                            infowindow.open(map, marker);
                        } else {
                            window.alert('No results found');
                        }
                    } else {
                        window.alert('Geocoder failed due to: ' + status);
                    }
                });
                SelectedLatLng =(markerCurrent.position.lat(),markerCurrent.position.lng());
            }
            function addMarkerRunTime(location) {
                var marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
                markers.push(marker);
            }
            function setMapOnAll(map) {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                }
            }
            function clearMarkers() {
                setMapOnAll(null);
            }
            function deleteMarkers() {
                clearMarkers();
                markers = [];
            }
            // Create the search box and link it to the UI element.
            var input = document.getElementById('pac-input');
            $("#pac-input").val("أبحث هنا ");
            var searchBox = new google.maps.places.SearchBox(input);
            map.controls[google.maps.ControlPosition.TOP_RIGHT].push(input);
            // Bias the SearchBox results towards current map's viewport.
            map.addListener('bounds_changed', function() {
                searchBox.setBounds(map.getBounds());
            });
            var markers = [];
            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener('places_changed', function() {
                var places = searchBox.getPlaces();
                if (places.length == 0) {
                    return;
                }
                // Clear out the old markers.
                markers.forEach(function(marker) {
                    marker.setMap(null);
                });
                markers = [];
                // For each place, get the icon, name and location.
                var bounds = new google.maps.LatLngBounds();
                places.forEach(function(place) {
                    if (!place.geometry) {
                        console.log("Returned place contains no geometry");
                        return;
                    }
                    var icon = {
                        url: place.icon,
                        size: new google.maps.Size(100, 100),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25)
                    };
                    // Create a marker for each place.
                    markers.push(new google.maps.Marker({
                        map: map,
                        icon: icon,
                        title: place.name,
                        position: place.geometry.location
                    }));
                    $('#latitude').val(place.geometry.location.lat());
                    $('#longitude').val(place.geometry.location.lng());
                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                map.fitBounds(bounds);
            });
        }
        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.setContent(browserHasGeolocation ?
                'Error: The Geolocation service failed.' :
                'Error: Your browser doesn\'t support geolocation.');
            infoWindow.open(map);
        }
        function splitLatLng(latLng){
            var newString = latLng.substring(0, latLng.length-1);
            var newString2 = newString.substring(1);
            var trainindIdArray = newString2.split(',');
            var lat = trainindIdArray[0];
            var Lng  = trainindIdArray[1];
            $("#latitude").val(lat);
            $("#longitude").val(Lng);
        }
    </script>
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initAutocomplete&language=ar&region=EG
         async defer"></script>
@endsection
