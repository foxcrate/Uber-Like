@extends('admin.layout.base')

@section('title', 'Map View ')

@section('content')
<div class="alert alert-info" role="alert">
@lang('admin.Map View')
</div>
    <div class="content-area py-1">
        <div class="container-fluid">

            <div class="box box-block bg-white">
                <div class="row">
                    <div class="col-xs-12">
                        <div id="map"></div>
                        <div id="legend"><h3>@lang('admin.Note:')</h3></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@section('styles')
    <style type="text/css">
        #map {
            height: 100%;
            min-height: 500px;
        }

        #legend {
            font-family: Arial, sans-serif;
            background: rgba(255, 255, 255, 0.8);
            padding: 10px;
            margin: 10px;
            border: 2px solid #f3f3f3;
        }

        #legend h3 {
            margin-top: 0;
            font-size: 16px;
            font-weight: bold;
            text-align: center;
        }

        #legend img {
            vertical-align: middle;
            margin-bottom: 5px;
        }
        .alert {
    text-align: center;
    font-weight: bold;
    font-size: large;
    border-radius: 0;
    margin-top: 10px;
    border-color: rgba(0,0,0,.125);
}
    </style>
@endsection

@section('scripts')
        <script src="//maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&libraries=places&callback=initMap"
                async defer></script>
{{--       {{ env('GOOGLE_MAP_KEY') }} --}}
    <script>
        var map;
        var users;
        var providers;
        var ajaxMarkers = [];
        var googleMarkers = [];
        var mapIcons = {
            user: '{{ asset("asset/img/marker-user.png") }}',
            active: '{{ asset("asset/img/marker-car.png") }}',
            riding: '{{ asset("asset/img/marker-car.png") }}',
            offline: '{{ asset("asset/img/marker-home.png") }}',
            unactivated: '{{ asset("asset/img/marker-plus.png") }}'
        }

        function initMap() {
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 8,
          center: {
            lat: 30.0444,
            lng: 31.2357
          },
               
            });

            setInterval(ajaxMapData, 3000);

            var legend = document.getElementById('legend');

            var div = document.createElement('div');
            div.innerHTML = '<img src="' + mapIcons['user'] + '"> ' + 'User'+"=>{{$userCountOnline}}";
            legend.appendChild(div);

            var div = document.createElement('div');
            div.innerHTML = '<img src="' + mapIcons['offline'] + '"> ' + 'Unavailable Provider' + " => {{$unavailableProvider}}";
            legend.appendChild(div);

            var div = document.createElement('div');
            div.innerHTML = '<img src="' + mapIcons['active'] + '"> ' + 'Available Provider' + " => {{$providersCountOnline}}";
            legend.appendChild(div);

            var div = document.createElement('div');
            div.innerHTML = '<img src="' + mapIcons['unactivated'] + '"> ' + 'Unactivated Provider' + " => {{$providersCountOffline}}";
            legend.appendChild(div);
            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(legend);

            google.maps.Map.prototype.clearOverlays = function () {
                for (var i = 0; i < googleMarkers.length; i++) {
                    googleMarkers[i].setMap(null);
                }
                googleMarkers.length = 0;
            }

        }

        function ajaxMapData() {
            map.clearOverlays();
            $.ajax({
                url: '{{route('admin.map.ajax')}}',
                dataType: "JSON",
                headers: {'X-CSRF-TOKEN': window.Laravel.csrfToken},
                type: "GET",
                success: function (data) {
                    console.log('Ajax Response', data);
                    ajaxMarkers = data;
                }
            });

            ajaxMarkers ? ajaxMarkers.forEach(addMarkerToMap) : '';
        }

        function addMarkerToMap(element, index) {
            // if(element.status = 'user'){
            //     marker1 = new google.maps.Marker({
            //     position: {
            //         lat: element.latitude,
            //         lng: element.longitude
            //     },
            //     id: element.id,
            //     map: map,
            //     title: element.first_name + " " + element.last_name,
            //     icon: mapIcons[element.status],
            // }); 
            // }
            marker = new google.maps.Marker({
                position: {
                    lat: element.latitude,
                    lng: element.longitude
                },
                id: element.id,
                map: map,
                title: element.first_name + " " + element.last_name,
                icon: mapIcons[element.service ? element.service.status : element.status],
            });
           
            googleMarkers.push(marker);
            // googleMarkers.push(marker1);

            google.maps.event.addListener(marker, 'click', function () {
                window.location.href = '/admin/' + element.service ? 'provider' : 'user' + '/' + element.user_id;
            });
        }
    </script>
@endsection
