@extends('user.layout.base')

@section('title', 'index ')

@section('content')
    <style>
        .carousel-inner {

            height: 275px;
        }
        .bg {

            /* Full height */
            height:  275px !important;

            /*!* Center and scale the image nicely *!*/
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
    <div class="col-md-9">
        <div class="dash-content">
            <div class="row no-margin">
                <div class="col-md-12">
                    <h4 class="page-title">@lang('user.order_mycars')</h4>
                </div>
            </div>
            @include('common.notify')
            <div class="row no-margin ride-detail">
                <div class="col-md-12">
                    @if($cars->count() > 0)
                        <table class=" table table-condensed text-center" style="border-collapse:collapse">

                            <thead>
                            <tr class="text-center">
                                <th></th>
                                <th class="text-center">@lang('user.Car Model')</th>
                                <th class="text-center">@lang('user.Made Year')</th>
                                <th class="text-center">@lang('user.date_to')</th>
                                <th class="text-center">@lang('user.date_creat')</th>
                                <th class="text-center">@lang('user.car_price')</th>
                                <th class="text-center" colspan="2">@lang('admin.Action')</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($cars as $car)

                                <tr data-toggle="collapse" data-target="#trip_{{$car->id}}" class="accordion-toggle collapsed">
                                    <td><span class="arrow-icon fa fa-chevron-right"></span></td>
                                    @if(app()->getLocale() == 'en')
                                        <td>{{$car->model->name_en}}</td>
                                    @else
                                         <td>{{$car->model->name}}</td>
                                    @endif
                                    <td>{{$car->model->date}}</td>
                                    <td>{{$car-> to}}</td>
                                    <td>{{date('Y-m-d',strtotime($car->created_at))}}</td>
                                    <td>{{$car->price}}</td>
                                    <td>
                                        <form action="{{url('order/car/delete/'.$car->id)}}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i
                                                    class="fa fa-trash"></i> @lang('admin.Delete')</button>
                                        </form>
                                    </td>
                                    @if($car->status == '0')
                                        <td>
                                                <form action="{{url('order/car/active/'.$car->id)}}" method="POST">
                                                    @csrf
                                                    <button class="btn btn-info" onclick="return confirm('Are you sure?')">@lang('admin.Enable')</button>
                                                </form>
                                        </td>
                                        @endif
                                </tr>
                                <tr class="hiddenRow">
                                    <td colspan="12">
                                        <div class="accordian-body collapse row" id="trip_{{$car->id}}">
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
                                                                    <div  class="item {{ $car->photo3 == null &&$car->photo2 == null && $car->photo1 == null ? 'active' : '' }}">
                                                                        <img class="bg" src="{{asset($car->photo4)}}" alt="Los Angeles" style="width:100%;">
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
                                                    <div class="from-to row no-margin">
                                                        <div class="from">
                                                            <h5>@lang('user.from')</h5>
                                                            <h6>{{date('H:i A - d-m-y', strtotime($car->from))}}</h6>
                                                            <p>{{$car->s_address}}</p>
                                                        </div>
                                                        <div class="to">
                                                            <h5>@lang('user.to')</h5>
                                                            <h6>{{date('H:i A - d-m-y', strtotime($car->to))}}</h6>
                                                            <p>{{$car->location}}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-4">

                                                <div class="mytrip-right">

                                                    <div class="fare-break">

                                                        <h4 class="text-center">
                                                            <strong>
                                                                @lang('user.car_inf')
                                                            </strong>
                                                        </h4>
                                                        <h4> <strong>  @if(app()->getLocale() == 'en'){{$car->model->name_en}}@else{{$car->model->name}}@endif - {{$car->model->date}}</strong></h4>

                                                        <h5>@lang('user.car_price') <span> {{$car->price}}</span></h5>
                                                        <h5>
                                                            <strong>@lang('user.gearbox') </strong>
                                                            <span>
                                                                <strong>
                                                                    @if($car->gearbox == 1)
                                                                        @lang('user.mun')
                                                                    @elseif($car->gearbox == 2)
                                                                        @lang('user.auto_dr_mun')
                                                                    @elseif($car->gearbox == 3)
                                                                        @lang('user.auto_dr')
                                                                    @endif
                                                                </strong>
                                                            </span>
                                                        </h5>
                                                        <h5 class="big">
                                                            <strong>@lang('user.full_type')</strong>
                                                            <span>
                                                                <strong>
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
                                                            </span>
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
                                    </td>
                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <hr>
                        <p style="text-align: center;">No trips Available</p>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection

@section('scripts')

@endsection
