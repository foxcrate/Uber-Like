@extends('user.layout.base')

@section('title', 'index ')

@section('content')
    <style>
        .carousel-inner {

            height: 290px;
        }
        .bg {

            /* Full height */
            height:  290px !important;

            /*!* Center and scale the image nicely *!*/
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
        .note_car{
            text-align: initial;
            margin: 40px 5px ;
        }
        .mt-2{
            margin-top: 20px;

        }
        .text-inf {
            background-color: #efefef47;
            margin-top: 25px;
            box-shadow: 0px 3px 7px 1px #00000096;
            padding: 10px 0;
        }
        .box-anuth-info{
            border: 1px solid #00000040;
            border-top: 0px;
            border-radius: 15px;
            box-shadow: 1px 1px 2px #0000004a;
        }
        strong.fl_ri {
            margin: 0 18%;
        }

        .note_car  i{
            margin: auto  0px 0px 5px ;
        }
        .btn-outline-info {
            color: #17a2b8;
            background-color: transparent;
            background-image: none;
            border-color: #17a2b8;
        }
        .btn-outline-info:not(:disabled):not(.disabled):active:focus, .show>.btn-outline-info.dropdown-toggle:focus {
            box-shadow: 0 0 0 0.2rem rgba(23,162,184,.5);
        }
        .btn-outline-info:not(:disabled):not(.disabled):active ,.btn-outline-info:hover {
            color: #fff;
            background-color: #17a2b8;
            border-color: #17a2b8;
        }
        i.fa-whatsapp {
            font-size: 15px;
            font-weight: bold;
            padding: 6px;
            background-color: #0b9c45;
            color: #fff;
            border-radius: 5px;
        }
        i.fa-whatsapp:hover {

            background-color: #fff;
            color: #0b9c45;

            box-shadow: 0 0 0 0.2rem rgba(7, 184, 34, 0.5);
        }
    </style>
    @if(app()->getLocale() == 'ar')
        <style>
            .note_car1 {
                text-align: right;
                direction: rtl;
            }

            span.fl_ri {
                float: left!important;
            }

        </style>
        @else
        <style>
            .fare-break h5 {
                direction: ltr;
                border-top: 1px solid #eee;
                padding-top: 20px;
                margin-top: 20px;
                text-align: left;
              }
        </style>
    @endif
    <div class="col-md-9">
        <div class="dash-content">
            <div class="row no-margin">
                <div class="col-md-12">
                    @if($car)
                    <h4 class="page-title">
                    @if(app()->getLocale() == 'ar')
                            {{$car->model->name}} - {{$car->model->date}}
                    @else
                            {{$car->model->name_en}} - {{$car->model->date}}
                    @endif

                    </h4>
                </div>
            </div>

            <div class="row no-margin ride-detail">
                <div class="col-md-12">

                        <table class=" table table-condensed text-center" style="border-collapse:collapse">

                            <tbody>
                                <tr class="">
                                    <td colspan="12">
                                        <div class="accordian-body row" id="trip_{{$car->id}}">
                                            <div class="col-md-8">
                                                <div class="my-trip-left">
                                                    <div class="map-static">
                                                        <div id="myCarousel" class="carousel slide" data-ride="carousel">
                                                            <!-- Indicators -->
                                                            <ol class="carousel-indicators">
                                                                <?php $Ky=0;?>
                                                                @if($car->photo1!= null)
                                                                 <li data-target="#myCarousel " data-slide-to="{{$Ky++}}" class="active"></li>
                                                                @endif
                                                                 @if($car->photo2!= null)
                                                                 <li data-target="#myCarousel" data-slide-to="{{$Ky++}}" {{ $car->photo1 == null ? 'class="active"' : '' }}></li>
                                                                @endif
                                                                 @if($car->photo3!= null)
                                                                 <li data-target="#myCarousel" data-slide-to="{{$Ky++}}" {{ $car->photo2 == null ? 'class="active"' : '' }}></li>
                                                                @endif
                                                                 @if($car->photo4!= null)
                                                                 <li data-target="#myCarousel" data-slide-to="{{$Ky++}}" {{ $car->photo3 == null ? 'class="active"' : '' }}></li>
                                                                @endif

                                                            </ol>

                                                            <!-- Wrapper for slides -->
                                                            <div class="carousel-inner">
                                                                @if($car->photo1!= null)
                                                                    <div class="item active">
                                                                        <img class="bg" src="{{asset($car->photo1)}}" alt="Los Angeles" style="width:100%;">
                                                                    </div>
                                                                @endif
                                                                @if($car->photo2!= null)
                                                                    <div class="item {{ $car->photo1 == null ? 'active' : '' }}">
                                                                        <img class="bg" src="{{asset($car->photo2)}}" alt="Los Angeles" style="width:100%;">
                                                                    </div>
                                                                @endif
                                                                @if($car->photo3!= null)
                                                                    <div class="item {{ $car->photo2 == null && $car->photo1 == null ? 'active' : '' }}">
                                                                        <img class="bg" src="{{asset($car->photo3)}}" alt="Los Angeles" style="width:100%;">
                                                                    </div>
                                                                @endif

                                                                @if($car->photo4!= null)
                                                                    <div  class="item {{ $car->photo3 == null && $car->photo2 == null && $car->photo1 == null ? 'active' : '' }}">
                                                                        <img class="bg" src="{{asset($car->photo4)}}" alt="Los Angeles" style="width:100%;">
                                                                    </div>
                                                                @endif
                                                                @if($car->photo1 == null && $car->photo2 == null  && $car->photo3 == null  && $car->photo4 == null )
                                                                        <div  class="item active">
                                                                            <img class="bg" src="{{asset('/uploads/images.png')}}" alt="Los Angeles" style="width:100%;">
                                                                        </div>
                                                                @endif
                                                            </div>

                                                            <!-- Left and right controls -->
                                                            <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                                                                <span class="glyphicon glyphicon-chevron-left"></span>
                                                                <span class="sr-only">Previous</span>
                                                            </a>
                                                            <a class="right carousel-control" href="#myCarousel" data-slide="next">
                                                                <span class="glyphicon glyphicon-chevron-right"></span>
                                                                <span class="sr-only">Next</span>
                                                            </a>
                                                        </div>
{{--                                                        <img src="{{asset($car->photo1)}}" height="280px;" style="max-width: 100%;">--}}
                                                    </div>
{{--                                                    <div class="from-to row no-margin">--}}
{{--                                                        <div class="from">--}}
{{--                                                            <h5>@lang('user.from')</h5>--}}
{{--                                                            <h6>{{date('H:i A - d-m-y', strtotime($car->from))}}</h6>--}}
{{--                                                            <p>{{$car->s_address}}</p>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="to">--}}
{{--                                                            <h5>@lang('user.to')</h5>--}}
{{--                                                            <h6>{{date('H:i A - d-m-y', strtotime($car->to))}}</h6>--}}
{{--                                                            <p>{{$car->location}}</p>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}





                                                </div>
                                            </div>
                                            <div class="col-md-4">

                                                <div class="mytrip-right">

                                                    <div class="fare-break">

                                                        <h4 class="text-center">
                                                            <strong>
                                                                @lang('user.car_user_inf')
                                                            </strong>
                                                        </h4>
                                                        <div class="trip-user">
                                                            <div class="user-img" style="background-image: url('{{asset($car->user->picture)}}')">
                                                            </div>
                                                            <div class="user-right">
                                                                {{$car->user->first_name}} {{$car->user->last_name}}
                                                            </div>
                                                        </div>
                                                        <h5 class="note_car1">
                                                            <i class="fas fa-dollar-sign"></i> @lang('user.car_price')
                                                            <span class="fl_ri">  {{$car->price}} </span>
                                                        </h5>
                                                        <h5>
                                                            <button id="show_bt" class="btn btn-outline-info ">@lang('user.car_user_inf_show')</button>
                                                            <button  id="hid_bt" class="btn btn-outline-info  " style="display: none">@lang('user.car_user_inf_hid')</button>
                                                        </h5>
                                                        <h5 hidden class="inf ">

                                                            <strong class="fl_ri">{{$car->user->mobile}}</strong>
                                                            <a href=" https://api.whatsapp.com/send?phone={{$car->user->mobile}}"><i class="fab fa-whatsapp"></i></a>
                                                        </h5>
                                                    </div>


                                                </div>

                                            </div>

                                        </div>
                                        <h4 class=" text-inf text-center">
                                            @lang('user.car_inf')
                                        </h4>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="row mt-2">
                                                    <h4 class="text-center">
                                                        <strong>
                                                            @lang('user.car_location')
                                                        </strong>
                                                    </h4>
                                                    <div id="map" style="height: 300px; width: 100%"></div>
                                                </div>

                                            </div>
                                            <div class="col-md-4">
                                                <div class="note_car note_car1 text-center">
                                                    <div class="fare-break">
                                                        <h4>
                                                            <strong>  <i class="fas fa-car-alt"></i> @if(app()->getLocale() == 'en'){{$car->model->name_en}}@else{{$car->model->name}}@endif - {{$car->model->date}}
                                                            </strong>
                                                        </h4>

                                                        <h5 > <i class="fas fa-dollar-sign"></i> @lang('user.car_price') <span class="fl_ri">  {{$car->price}} </span></h5>
                                                        <h5><i class="fas fa-chair"></i>@lang('user.car_number_seats') <span class="fl_ri"> {{$car->number_seats}} </span></h5>
                                                        <h5>
                                                            <strong><i class="fab fa-galactic-republic"></i>  @lang('user.gearbox')</strong>

                                                                <strong class="fl_ri">
                                                                    @if($car->gearbox == 1)
                                                                        @lang('user.mun')
                                                                    @elseif($car->gearbox == 2)
                                                                        @lang('user.auto_dr_mun')
                                                                    @elseif($car->gearbox == 3)
                                                                        @lang('user.auto_dr')
                                                                    @endif
                                                                </strong>

                                                        </h5>
                                                        <h5 class="big">
                                                            <strong> <i class="fas fa-gas-pump"></i> @lang('user.full_type')</strong>

                                                                <strong class="fl_ri">
                                                                    @if($car->full_type == 1)
                                                                        @lang('user.car_pt')
                                                                    @elseif($car->full_type == 2)
                                                                        @lang('user.car_Jazz')
                                                                    @elseif($car->full_type == 3)
                                                                        @lang('user.car_ghaz')
                                                                    @elseif($car->full_type == 4)
                                                                        @lang('user.car_el')
                                                                    @endif
                                                                </strong>

                                                        </h5>
                                                    </div>

                                                    <div class="trip-user">
                                                        <div class="user-img" style="background-color: {{$car->color}}">
                                                        </div>
                                                        <div class="user-right">
                                                            @lang('user.color_car')
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row box-anuth-info">
                                            <h4 class="text-center">
                                                <strong>
                                                    @lang('user.car_another_inf')
                                                </strong>
                                            </h4>
                                            <div class="note_car" >
                                                <p>

                                                    @if(app()->getLocale() == 'ar')
                                                        {!! $car->note_ar !!}
                                                    @else
                                                        {!! $car->note_en !!}
                                                    @endif
                                                </p>
                                            </div>
                                        </div>

                                    </td>
                                </tr>

                            </tbody>
                        </table>
                    @else
                        <hr>
                        <p style="text-align: center;">No't found</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAP_KEY') }}&callback=initMap&libraries=&v=weekly" defer></script>
    <script>
        // Initialize and add the map
        function initMap() {
            // The location of Uluru
        const uluru = { lat: {{$car->lat }}, lng: {{$car->long}} };
            // The map, centered at Uluru
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 10,
                center: uluru,
            });
            // The marker, positioned at Uluru
            const marker = new google.maps.Marker({
                position: uluru,
                map: map,
            });
        }
        $(document).ready(function(){

            // Show hidden paragraphs
            $("#show_bt").click(function(){
                $(".inf").show(1000);
                $(this).hide();
                $("#hid_bt").show();
            });
            $("#hid_bt").click(function(){
                $(".inf").hide(1000);
                $(this).hide();
                $("#show_bt").show();
            });

        });
    </script>
@endsection
