@extends('user.layout.base')

@section('title', 'index ')

@section('content')
    <style type="text/css">
        /* Always set the map height explicitly to define the size of the div
         * element that contains the map. */
        #map {
            height: 100%;
        }

        /* Optional: Makes the sample page fill the window. */
        html,
        body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        #description {
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
        }

        #infowindow-content .title {
            font-weight: bold;
        }

        #infowindow-content {
            display: none;
        }

        #map #infowindow-content {
            display: inline;
        }

        .pac-card {
            margin: 10px 10px 0 0;
            border-radius: 2px 0 0 2px;
            box-sizing: border-box;
            -moz-box-sizing: border-box;
            outline: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
            background-color: #fff;
            font-family: Roboto;
        }

        #pac-container {
            padding-bottom: 12px;
            margin-right: 12px;
        }

        .pac-controls {
            display: inline-block;
            padding: 5px 11px;
        }

        .pac-controls label {
            font-family: Roboto;
            font-size: 13px;
            font-weight: 300;
        }

        #pac-input {
            background-color: #fff;
            font-family: Roboto;
            font-size: 15px;
            font-weight: 300;
            margin-left: 12px;
            padding: 0 11px 0 13px;
            text-overflow: ellipsis;
            width: 400px;
        }

        #pac-input:focus {
            border-color: #4d90fe;
        }

        #title {
            color: #fff;
            background-color: #4d90fe;
            font-size: 25px;
            font-weight: 500;
            padding: 6px 12px;
        }

        #target {
            width: 345px;
        }
    </style>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css?family=Roboto');

        body{
            font-family: 'Roboto', sans-serif;
        }
        * {
            margin: 0;
            padding: 0;
        }
        i {
            margin-right: 10px;
        }

        /*------------------------*/
        input:focus,
        button:focus,
        .form-control:focus{
            outline: none;
            box-shadow: none;
        }
        .form-control:disabled, .form-control[readonly]{
            background-color: #fff;
        }
        /*----------step-wizard------------*/
        .d-flex{
            display: flex;
        }
        .justify-content-center {
            margin-top: 25px;
            justify-content: center;
        }
        .align-items-center{
            align-items: center;
        }

        /*---------signup-step-------------*/
        .bg-color{
            background-color: #333;
        }
        .signup-step-container{
            padding: 25px 0px;
        }




        .wizard .nav-tabs {
            position: relative;
            margin-bottom: 0;
            border-bottom-color: transparent;
        }

        .wizard > div.wizard-inner {
            position: relative;
            margin-bottom: 50px;
            text-align: center;
        }

        .connecting-line {
            height: 2px;
            background: #e0e0e0;
            position: absolute;
            width: 95%;
            margin: 0 auto;
            left: 0;
            right: 0;
            top: 15px;
            z-index: 1;
        }

        .wizard .nav-tabs > li.active > a, .wizard .nav-tabs > li.active > a:hover, .wizard .nav-tabs > li.active > a:focus {
            color: #555555;
            cursor: default;
            border: 0;
            border-bottom-color: transparent;
        }

        span.round-tab {
            width: 30px;
            height: 30px;
            line-height: 30px;
            display: inline-block;
            border-radius: 50%;
            background: #fff;
            z-index: 2;
            position: absolute;
            left: 0;
            text-align: center;
            font-size: 16px;
            color: #0e214b;
            font-weight: 500;
            border: 1px solid #ddd;
        }
        span.round-tab i{
            color:#555555;
        }
        .wizard li.active span.round-tab {
            background: #0db02b;
            color: #fff;
            border-color: #0db02b;
        }
        .wizard li.active span.round-tab i{
            color: #5bc0de;
        }
        .wizard .nav-tabs > li.active > a i{
            color: #0db02b;
        }

        .wizard .nav-tabs > li {
            width: 43%;
        }

        .wizard li:after {
            content: " ";
            position: absolute;
            left: 46%;
            opacity: 0;
            margin: 0 auto;
            bottom: 0px;
            border: 5px solid transparent;
            border-bottom-color: red;
            transition: 0.1s ease-in-out;
        }



        .wizard .nav-tabs > li a {
            width: 30px;
            height: 30px;
            margin: 0px auto;
            border-radius: 100%;
            padding: 0;
            background-color: transparent;
            position: relative;
            top: 0;
        }
        .wizard .nav-tabs > li a i{
            position: absolute;
            top: -15px;
            font-style: normal;
            font-weight: 400;
            white-space: nowrap;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            font-weight: 700;
            color: #000;
        }

        .wizard .nav-tabs > li a:hover {
            background: transparent;
        }

        .wizard .tab-pane {
            position: relative;
            padding-top: 20px;
        }


        .wizard h3 {
            margin-top: 0;
        }
        .prev-step,
        .next-step{
            font-size: 13px;
            padding: 8px 24px;
            border: none;
            border-radius: 4px;
            margin-top: 30px;
        }
        .next-step {
            color: white;
            background-color: #0db02b;
        }
        .skip-btn{
            background-color: #cec12d;
        }
        .step-head{
            font-size: 20px;
            text-align: center;
            font-weight: 500;
            margin-bottom: 20px;
        }
        .term-check{
            font-size: 14px;
            font-weight: 400;
        }
        .custom-file {
            position: relative;
            display: inline-block;
            width: 100%;
            height: 40px;
            margin-bottom: 0;
        }
        .custom-file-input {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 40px;
            margin: 0;
            opacity: 0;
        }
        .custom-file-label {
            position: absolute;
            top: 0;
            right: 0;
            left: 0;
            z-index: 1;
            height: 40px;
            padding: .375rem .75rem;
            font-weight: 400;
            line-height: 2;
            color: #495057;
            background-color: #fff;
            border: 1px solid #ced4da;
            border-radius: .25rem;
        }
        .custom-file-label::after {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            z-index: 3;
            display: block;
            height: 38px;
            padding: .375rem .75rem;
            line-height: 2;
            color: #495057;
            content: "Browse";
            background-color: #e9ecef;
            border-left: inherit;
            border-radius: 0 .25rem .25rem 0;
        }
        .footer-link{
            margin-top: 30px;
        }
        .all-info-container{

        }
        .list-content{
            margin-bottom: 10px;
        }
        .list-content a{
            padding: 10px 15px;
            width: 100%;
            display: inline-block;
            background-color: #f5f5f5;
            position: relative;
            color: #565656;
            font-weight: 400;
            border-radius: 4px;
        }
        .list-content a[aria-expanded="true"] i{
            transform: rotate(180deg);
        }
        .list-content a i{
            text-align: right;
            position: absolute;
            top: 15px;
            right: 10px;
            transition: 0.5s;
        }
        .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
            background-color: #fdfdfd;
        }
        .list-box{
            padding: 10px;
        }
        .signup-logo-header .logo_area{
            width: 200px;
        }
        .signup-logo-header .nav > li{
            padding: 0;
        }
        .signup-logo-header .header-flex{
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .list-inline li{
            display: inline-block;
        }
        .pull-right{
            float: right;
        }
        /*-----------custom-checkbox-----------*/
        /*----------Custom-Checkbox---------*/
        input[type="checkbox"]{
            position: relative;
            display: inline-block;
            margin-right: 5px;
        }
        input[type="checkbox"]::before,
        input[type="checkbox"]::after {
            position: absolute;
            content: "";
            display: inline-block;
        }
        input[type="checkbox"]::before{
            height: 16px;
            width: 16px;
            border: 1px solid #999;
            left: 0px;
            top: 0px;
            background-color: #fff;
            border-radius: 2px;
        }
        input[type="checkbox"]::after{
            height: 5px;
            width: 9px;
            left: 4px;
            top: 4px;
        }
        input[type="checkbox"]:checked::after{
            content: "";
            border-left: 1px solid #fff;
            border-bottom: 1px solid #fff;
            transform: rotate(-45deg);
        }
        input[type="checkbox"]:checked::before{
            background-color: #18ba60;
            border-color: #18ba60;
        }

        .contact-h {
            font-size: 24px;
            text-transform: uppercase;
            font-weight: 700;
            margin-bottom: 40px;
            color: #EC407A;
        }
        .contact .form-group {
            margin-bottom: 40px;
        }
        .contact .form-control {
            border-radius: 0;
            border: none;
            border-bottom: 4px solid #00BCD4;
            box-shadow: none;
        }


        .group {
            position: relative;
            margin-bottom: 45px;
        }






        /* active state */

        /* ANIMATIONS ================ */




        .text-group {
            position: relative;
            margin-top: 2.25rem;
            margin-bottom: 4.25rem;
        }




        @media (max-width: 767px){
            .sign-content h3{
                font-size: 40px;
            }
            .wizard .nav-tabs > li a i{
                display: none;
            }
            .signup-logo-header .navbar-toggle{
                margin: 0;
                margin-top: 8px;
            }
            .signup-logo-header .logo_area{
                margin-top: 0;
            }
            .signup-logo-header .header-flex{
                display: block;
            }

        }
        select.form-control {


            border-radius: 10px;
        }
        input{
            height: 36px;
            border-radius: 10px;
        }

        .color_red{
            font-size: large;
            color: #ff0000;

        }
        .h2_la{
            padding-bottom: 30px;
            color: #0db02b;
        }
        /*************

        |                                                  |
        |                images styles                     |
        |__________________________________________________|
         */@import url('https://fonts.googleapis.com/icon?family=Material+Icons');
        @import url("https://fonts.googleapis.com/css?family=Raleway");


        h1 {
            font-family: inherit;
            margin: 0 0 0.75em 0;
            color: desaturate(cadetblue, 15%);
            text-align: center;
        }

        .box {
            padding: 0px !important;
            display: block;

            margin: 10px 15px;
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.12), 0 1px 2px rgba(0, 0, 0, 0.24);
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
            overflow: hidden;
        }

        .upload-options {
            position: relative;
            height: 75px;
            background-color: cadetblue;
            cursor: pointer;
            overflow: hidden;
            text-align: center;
            transition: background-color ease-in-out 150ms;
        }
        .upload-options:hover {
             background-color: lighten(cadetblue, 10%);
         }
        .upload-options input {
              width: 0.1px;
              height: 0.1px;
              opacity: 0;
              overflow: hidden;
              position: absolute;
              z-index: -1;
          }
        .upload-options label {
            display: flex;
            align-items: center;
            width: 100%;
            height: 100%;
            font-weight: 400;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
            overflow: hidden;
        }
        .upload-options::after {
             content: "add";
             font-family: "Material Icons";
             position: absolute;
             font-size: 2.5rem;
             color: rgba(230, 230, 230, 1);
             top: calc(50% - 2.5rem);
             left: calc(50% - 1.25rem);
             z-index: 0;
         }
        .upload-options span {
              display: inline-block;
              width: 50%;
              height: 100%;
              text-overflow: ellipsis;
              white-space: nowrap;
              overflow: hidden;
              vertical-align: middle;
              text-align: center;
        }

        span:hover i.material-icons {
             color: lightgray;
         }


        .js--image-preview {
            height: 225px;
            width: 100%;
            position: relative;
            overflow: hidden;
            background-image: url("");
            background-color: white;
            background-position: center center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .js--image-preview::after {
             content: "";
             font-family: "Material Icons";
             position: relative;
             font-size: 4.5em;
             color: rgba(230, 230, 230, 1);
             top: calc(50% - 3rem);
             left: calc(50% - 2.25rem);
             z-index: 0;
         }
        .js--image-preview.js--no-default::after {
             display: none;
         }
        .js--image-preview:nth-child(2) {
             background-image: url("http://bastianandre.at/giphy.gif");
         }


        i.material-icons {
            transition: color 100ms ease-in-out;
            font-size: 2.25em;
            line-height: 55px;
            color: white;
            display: block;
        }

        .drop {
            display: block;
            position: absolute;
            background: transparentize(cadetblue, 0.8);
            border-radius: 100%;
            transform: scale(0);
        }

        .animate {
            animation: ripple 0.4s linear;
        }

        @keyframes ripple {
            100% {
                opacity: 0;
                transform: scale(2.5);
            }
        }
        .profile-img-blk {
            width: 150px;
            height: 150px;
            margin-top: 10px;
            margin-bottom: 20px;
             border-radius:3%;
            border: 5px solid #eee;
            overflow: hidden;
        }
        @media (min-width: 992px){

            .col-md-6 {
                width: 45%;
            }
        }

    </style>
    <section class="signup-step-container">
        <div class="container">
            @include('common.notify')
            <div class="row d-flex justify-content-center">
                <div class="col-md-8" style="width: 95%;">
                    <div class="wizard">
                        <div class="wizard-inner">
                            <div class="connecting-line"></div>
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#step1" data-toggle="tab" aria-controls="step1" role="tab" aria-expanded="true"><span class="round-tab">1 </span> <i>Step 1</i></a>
                                </li>
                                <li role="presentation" class="disabled">
                                    <a href="#step2" data-toggle="tab" aria-controls="step2" role="tab" aria-expanded="false"><span class="round-tab">2</span> <i>Step 2</i></a>
                                </li>

                            </ul>
                        </div>

                        <form  action="{{route('order.cars.store')}}" class="login-box" method="POST" enctype="multipart/form-data" role="form" id="form_id">
                            @csrf
                            <input type="hidden"  value="" id="latitude" name="latitude">
                            <input type="hidden" value="" id="longitude"  name="longitude">
                            <input type="hidden" name="id_borr" style="display: none" value="{{Auth::user()->id}}">
                            <div class="tab-content" id="main_form">
                                <div class="alert alert-danger" role="alert"> @lang('user.car_commend')</div>
                                <div class="tab-pane active" role="tabpanel" id="step1">
                                    <h2 class="text-center h2_la">@lang('user.car_inf')</h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('user.car_model')</label><span class="color_red">*</span>
                                                <select name="id_models" class="form-control" id="country">
                                                    @isset($cars_models)
                                                        @foreach($cars_models as $cars_model)
                                                            <option value="{{$cars_model->id}}">{{app()->getlocale() =="ar" ? $cars_model->name : $cars_model->name_en}} - {{$cars_model->date}}</option>
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>

                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('admin.governorate_id')</label><span class="color_red">*</span>
                                                <select name="id_governorate" class="form-control" id="country">
                                                    @isset($governorates)
                                                        @foreach($governorates as $governorate)
                                                            @if(app()->getLocale()=="en")
                                                                <option value="{{$governorate->id}}" >{{$governorate->name_en}}</option>
                                                            @else
                                                                <option value="{{$governorate->id}}" >{{$governorate->name}}</option>
                                                            @endif
                                                        @endforeach
                                                    @endisset
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('user.gearbox')</label><span class="color_red">*</span>
                                                <select name="gearbox" class="form-control" id="gearbox">
                                                    <option value="1" selected="selected">@lang('user.mun')</option>
                                                    <option value="2">@lang('user.auto_dr_mun')</option>
                                                    <option value="3">@lang('user.auto_dr')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('user.car_number_seats')</label><span class="color_red">*</span>
                                                    <input class="form-control" type="number" name="number_seats" value="{{old('number_seats')}}" placeholder="">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('user.color_car')</label><span class="color_red">*</span>
                                                    <input type="color"  name="color" class="form-control"value="{{old('color')}}" id="color"style="padding: 0px;">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label> @lang('user.full_type') </label><span class="color_red">*</span>
                                                <select name="full_type" class="form-control" id="country">
                                                    <option value="1" selected="selected">@lang('user.car_pt')</option>
                                                    <option value="2">@lang('user.car_Jazz')</option>
                                                    <option value="3">@lang('user.car_ghaz')</option>
                                                    <option value="4">@lang('user.car_el')</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6 ">
                                            <div class="form-group">
                                                <label for="projectinput1"> @lang('admin.Address')  </label>
                                                <input type="text" id="pac-input"
                                                       class="form-control"
                                                       placeholder="  " value="{{old('address')}}" name="address">
                                                @error("address")
                                                <span class="text-danger"> {{$message}}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id="map" style="height: 250px;"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group group">
                                                <div class="text-group">
                                                <label for="textarea " class="input-label text-center" >@lang('user.car_note_ar')</label><i class="bar"></i>

                                                    <textarea required="required"name="note_ar" class="form-control" id="myeditor" rows="9">{{old('note_ar')}} </textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group group">
                                                <div class="text-group">
                                                <label for="textarea " class="text-center" >@lang('user.car_note_en')</label><i class="bar"></i>
                                                    <textarea required="required" name="note_en" class="form-control" rows="9"  id="myeditor1">{{old('note_en')}}</textarea>

                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <ul class="list-inline list-inline1 pull-right">
                                        <li><button type="button" class="default-btn next-step">@lang('user.Continue to next step')</button></li>
                                    </ul>
                                </div>
                                <div class="tab-pane" role="tabpanel" id="step2">
                                    <h2 class="text-center h2_la">@lang('user.car_price_photo')</h2>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>@lang('user.car_price')</label><span class="color_red">*</span>
                                                <input class="form-control" type="text" {{old('price')}} name="price" placeholder="">
                                            </div>
                                        </div>
                                    </div>
                                    <h4 class="text-center">@lang('user.car_photo')</h4>
                                    <div class="wrapper">
                                        <div class="row">
                                            <div class="box col-md-6">
                                                <div class="js--image-preview" style="background-image: url('{{asset('uploads/images.png')}}')"></div>
                                                <div class="upload-options">
                                                    <label>
                                                        <input type="file" name="photo"class="image-upload" accept="image/*" />
                                                    </label>
                                                </div>
                                            </div>

                                            <div class="box col-md-6">
                                                <div class="js--image-preview" style="background-image: url('{{asset('uploads/images.png')}}')"></div>
                                                <div class="upload-options">
                                                    <label>
                                                        <input type="file"name="photo2" class="image-upload" accept="image/*" />
                                                    </label>
                                                </div>
                                            </div>

                                        </div>
                                        <div class="row">
                                            <div class="box col-md-6">
                                                <div class="js--image-preview" style="background-image: url('{{asset('uploads/images.png')}}')"></div>
                                                <div class="upload-options">
                                                    <label>
                                                        <input type="file" name="photo3" class="image-upload" accept="image/*" />
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="box col-md-6">
                                                <div class="js--image-preview" style="background-image: url('{{asset('uploads/images.png')}}')"></div>
                                                <div class="upload-options">
                                                    <label>
                                                        <input type="file" name="photo4" class="image-upload" accept="image/*" />
                                                    </label>
                                                </div>
                                            </div>
                                        </div>


                                    </div>
                                    <ul class="list-inline pull-right">
                                        <li><button type="button" class="default-btn prev-step">@lang('user.back_Car')</button></li>
                                        <button type="submit" class="btn btn-primary">@lang('user.Add Car')</button>

                                    </ul>

                                </div>


                                <div class="clearfix"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
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
    <script src="//cdn.ckeditor.com/4.6.2/standard/ckeditor.js"></script>
    <script type="text/javascript">
        CKEDITOR.replace('myeditor');
        CKEDITOR.replace('myeditor1');
    </script>
    <script type="text/javascript" src="https://code.jquery.com/jquery-3.4.1.min.js" ></script>
    <script type="text/javascript" src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" ></script>
    <script type="text/javascript">
        function initImageUpload(box) {
            let uploadField = box.querySelector('.image-upload');

            uploadField.addEventListener('change', getFile);

            function getFile(e){
                let file = e.currentTarget.files[0];
                checkType(file);
            }

            function previewImage(file){
                let thumb = box.querySelector('.js--image-preview'),
                    reader = new FileReader();

                reader.onload = function() {
                    thumb.style.backgroundImage = 'url(' + reader.result + ')';
                }
                reader.readAsDataURL(file);
                thumb.className += ' js--no-default';
            }

            function checkType(file){
                let imageType = /image.*/;
                if (!file.type.match(imageType)) {
                    throw 'Datei ist kein Bild';
                } else if (!file){
                    throw 'Kein Bild gewählt';
                } else {
                    previewImage(file);
                }
            }

        }

        // initialize box-scope
        var boxes = document.querySelectorAll('.box');

        for (let i = 0; i < boxes.length; i++) {
            let box = boxes[i];
            initDropEffect(box);
            initImageUpload(box);
        }



        /// drop-effect
        function initDropEffect(box){
            let area, drop, areaWidth, areaHeight, maxDistance, dropWidth, dropHeight, x, y;

            // get clickable area for drop effect
            area = box.querySelector('.js--image-preview');
            area.addEventListener('click', fireRipple);

            function fireRipple(e){
                area = e.currentTarget
                // create drop
                if(!drop){
                    drop = document.createElement('span');
                    drop.className = 'drop';
                    this.appendChild(drop);
                }
                // reset animate class
                drop.className = 'drop';

                // calculate dimensions of area (longest side)
                areaWidth = getComputedStyle(this, null).getPropertyValue("width");
                areaHeight = getComputedStyle(this, null).getPropertyValue("height");
                maxDistance = Math.max(parseInt(areaWidth, 10), parseInt(areaHeight, 10));

                // set drop dimensions to fill area
                drop.style.width = maxDistance + 'px';
                drop.style.height = maxDistance + 'px';

                // calculate dimensions of drop
                dropWidth = getComputedStyle(this, null).getPropertyValue("width");
                dropHeight = getComputedStyle(this, null).getPropertyValue("height");

                // calculate relative coordinates of click
                // logic: click coordinates relative to page - parent's position relative to page - half of self height/width to make it controllable from the center
                x = e.pageX - this.offsetLeft - (parseInt(dropWidth, 10)/2);
                y = e.pageY - this.offsetTop - (parseInt(dropHeight, 10)/2) - 30;

                // position drop and animate
                drop.style.top = y + 'px';
                drop.style.left = x + 'px';
                drop.className += ' animate';
                e.stopPropagation();

            }
        }

        // ------------step-wizard-------------
        $(document).ready(function () {
            $('.nav-tabs > li a[title]').tooltip();

            //Wizard
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

                var target = $(e.target);

                if (target.parent().hasClass('disabled')) {
                    return false;
                }
            });

            $(".next-step").click(function (e) {

                var active = $('.wizard .nav-tabs li.active');
                active.next().removeClass('disabled');
                nextTab(active);

            });
            $(".prev-step").click(function (e) {

                var active = $('.wizard .nav-tabs li.active');
                prevTab(active);

            });
        });

        function nextTab(elem) {
            $(elem).next().find('a[data-toggle="tab"]').click();
        }
        function prevTab(elem) {
            $(elem).prev().find('a[data-toggle="tab"]').click();
        }


        $('.nav-tabs').on('click', 'li', function() {
            $('.nav-tabs li.active').removeClass('active');
            $(this).addClass('active');
        });



    </script>
@endsection
