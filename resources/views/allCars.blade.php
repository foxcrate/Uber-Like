@extends('user.layout.app')

@section('title', 'Cars')
@section('style')
    <link href="{{asset('asset/css/style-ad.css')}}" rel="stylesheet">

    @if(app()->getLocale() == 'ar')
        <style>
            .dash-left{
                float:right;
            }
            @media screen and (max-width: 990px) {
                .dash-left{
                    float:none!important;
                }
            }

            .dropdown,.nav-item_der{
                direction: rtl;
            }
            .col-md-2 li.dropdown,.nav-item_der {
                float: right;
            }

        </style>
    @endif
@endsection
@section('content')
<style>

    body {
        background-image: url("{{asset('/uploads/background.png')}}");
        /*background: #f1f1f1;*/
    }
</style>
<div class="container container1 mT-60 mB-20">
    <div class="row">
{{--      secation 1 navebar --}}
         <div class="col-md-2 dash-left">
          <ul class="nav navbar-nav navbar-right">
              <li class="nav-item nav-item_der">

                  <a href="{{url('/cars')}}" class="nav-link"><i class="fas fa-home"></i> @lang('user.Back All') </a>
              </li>
               <li class="dropdown">
                  <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button"
                     aria-haspopup="true" aria-expanded="false"> <i class="fas fa-map-marker-alt"></i> &nbsp; @lang('user.car_Gon') <span
                          class="caret"></span></a>
                  <ul class="dropdown-menu">
                      @isset($governorates)
                          @foreach($governorates as $governorate)
                              <li>
                                  <a href="{{url('/cars/Provinces/'.$governorate->id)}}">
                                      @if(app()->getLocale() == 'ar')
                                          {{ $governorate-> name }}
                                      @else
                                          {{ $governorate-> name_en }}
                                      @endif

                                  </a>
                              </li>
                              <li role="separator" class="divider"></li>
                        @endforeach
                      @endisset
                  </ul>
              </li>
              <li class="dropdown">
                  <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button"
                     aria-haspopup="true" aria-expanded="false"> <i class="fas fa-car-alt"></i> &nbsp;@lang('user.car_model') <span
                          class="caret"></span></a>
                  <ul class="dropdown-menu">
                      @isset($models)
                          @foreach($models as $model)
                              <li>
                                  <a href="{{url('/cars/Model/'.$model->id)}}">
                                      @if(app()->getLocale() == 'ar')
                                          {{ $model-> name }} - {{ $model-> date }}
                                      @else
                                          {{ $model-> name_en }} - {{ $model-> date }}
                                      @endif

                                  </a>
                              </li>
                              <li role="separator" class="divider"></li>
                          @endforeach
                      @endisset
                  </ul>
              </li>
              <li class="dropdown">
                  <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button"
                     aria-haspopup="true" aria-expanded="false"> <i class="far fa-calendar-alt"></i> &nbsp;@lang('user.year_car2') <span
                          class="caret"></span></a>
                  <ul class="dropdown-menu">
                      @isset($models)
                          @foreach($models as $model1)
                              <li>
                                  <a href="{{url('/cars/Model-year/'.$model1->date)}}">
                                      {{ $model1-> date }}
                                  </a>
                              </li>
                              <li role="separator" class="divider"></li>
                          @endforeach
                      @endisset
                  </ul>
              </li>
              <li class="dropdown">
                  <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button"
                     aria-haspopup="true" aria-expanded="false"> <i class="fas fa-gas-pump"></i> &nbsp;@lang('user.full_type')
                      <span class="caret"></span>
                  </a>
                  <ul class="dropdown-menu">
                      <li><a href="{{url('/cars/Full-type/1')}}">@lang('user.car_pt')</a></li>
                      <li role="separator" class="divider"></li>
                      <li><a href="{{url('/cars/Full-type/2')}}">@lang('user.car_Jazz')</a></li>
                      <li role="separator" class="divider"></li>
                      <li><a href="{{url('/cars/Full-type/3')}}">@lang('user.car_ghaz')</a></li>
                      <li role="separator" class="divider"></li>
                      <li><a href="{{url('/cars/Full-type/4')}}">@lang('user.car_el')</a></li>
                  </ul>
              </li>
              <li class="dropdown">
                  <a href="#" class="dropdown-toggle " data-toggle="dropdown" role="button"
                     aria-haspopup="true" aria-expanded="false"> <i class="fab fa-galactic-republic"></i> &nbsp;@lang('user.gearbox') <span
                          class="caret"></span></a>
                  <ul class="dropdown-menu">
                      <li><a href="{{url('/cars/Bax-type/1')}}">@lang('user.mun')</a></li>
                      <li role="separator" class="divider"></li>
                      <li><a href="{{url('/cars/Bax-type/2')}}">@lang('user.auto_dr_mun')</a></li>
                      <li role="separator" class="divider"></li>
                      <li><a href="{{url('/cars/Bax-type/3')}}">@lang('user.auto_dr')</a></li>
                  </ul>
              </li>
          </ul>
      </div>
{{--      secation 2 contante --}}

          <div class="col-md-10">
              @isset($cars)
    {{--              {{$cars->count()}}--}}

                  @if($cars->count() == 0)
                      <div class="row">
                          <div class="alert alert-danger" role="alert">
                              <strong>Oooooops! </strong> @lang('user.not_found_cars')
                          </div>
                     </div>
                  @else
                      <div class="row">
                          @foreach($cars as $car)
                              <div class="col-md-6">
                                 <div class="blog-card">
                                  <div class="meta">
                                      <div class="photo" style="
                                          @if($car->photo1 != null)
                                            background-image:url({{asset($car->photo1)}});
                                          @elseif($car->photo2 != null)
                                             background-image:url({{asset($car->photo2)}});
                                          @elseif($car->photo3 != null)
                                            background-image:url({{asset($car->photo3)}});
                                          @elseif($car->photo4 != null)
                                            background-image:url({{asset($car->photo4)}});
                                          @else
                                          background-image:url({{asset('/uploads/images.png')}});
                                          @endif

                                          "></div>
                                      <ul class="details">
                                          <li class="author c-wh">{{base64_decode($car->user->first_name) }} {{base64_decode($car->user->last_name) }}</li>
                                          <li class="date c-wh">{{date('d-M-Y', strtotime($car->created_at)) }}</li>
                                          <li class="tags c-wh">
                                              <ul>
                                                  <li>
                                                      <a href="{{url('/cars/Model/'.$car->model->id)}}">
                                                          @if(app()->getLocale() == 'ar')
                                                              {{$car->model-> name }}
                                                          @else
                                                              {{ $car->model-> name_en }}
                                                          @endif
                                                      </a>
                                                  </li>
                                                  <li>
                                                      <a href="{{url('/cars/Model-year/'.$car->model->date)}}">
                                                          {{ $car->model-> date }}
                                                      </a>
                                                  </li>
                                              </ul>
                                          </li>
                                      </ul>
                                  </div>
                                  <div class="description">
                                      <h1>
                                          @if(app()->getLocale() == 'ar')
                                              {{$car->model-> name }}
                                          @else
                                              {{ $car->model-> name_en }}
                                          @endif
                                      </h1>
                                      <h2>
                                          @if(app()->getLocale() == 'ar')
                                              {{$car->model-> name }} - {{$car->model-> date}}
                                          @else
                                              {{ $car->model-> name_en }} - {{$car->model-> date}}
                                          @endif
                                      </h2>
                                      <p>

                                      </p>

                                      <ul>
                                          <li>{{$car->price}}<span>@lang('user.car_price')</span></li>
                                          <li>
                                              @if($car->full_type == 1)
                                                  @lang('user.car_pt')
                                              @elseif($car->full_type == 2)
                                                  @lang('user.car_Jazz')
                                              @elseif($car->full_type == 3)
                                                  @lang('user.car_ghaz')
                                              @elseif($car->full_type == 4)
                                                  @lang('user.car_el')
                                              @endif
                                              <span>@lang('user.full_type')</span>
                                          </li>
                                          <li>{{$car->number_seats}}<span>@lang('user.car_number_seats')</span></li>
                                          <li>
                                              @if($car->gearbox == 1)
                                                  @lang('user.mun')
                                              @elseif($car->gearbox == 2)
                                                  @lang('user.auto_dr_mun')
                                              @elseif($car->gearbox == 3)
                                                  @lang('user.auto_dr')
                                              @endif
                                                  <span>@lang('user.gearbox') </span>
                                          </li>
                                          <li>
                                              <span1 class="color_car p-1" style="background-color: {{$car->color}}"></span1> <span>@lang('user.color_car')</span></li>
                                      </ul>
                                      <div class="clearfix"></div>
                                      <p class="read-more">
                                          <a href="{{url('/order/car/View/'.$car->id)}}"> @lang('user.details') </a>
                                      </p>
                                  </div>
                              </div>
                              </div>
                          @endforeach
                     </div>
                  @endif
              @endisset
              <div class="links_pages">
                  {{ $cars->links() }}
              </div>
        </div>
  </div>
</div>


@endsection

@section('scripts')

@endsection
