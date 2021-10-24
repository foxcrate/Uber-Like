@extends('user.layout.app')

@section('content')
    <div class="row white-section no-margin">
        <div class="container-fluid">
            <section class="why text-center">
                <div class="container">
                    @isset($transportationType)
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="title">
                                <h2> @lang('user.service_model')
                                @if(app()->getlocale() == 'ar')
                                {{$transportationType->name}} 
                                @else
                                     {{$transportationType->name_en}} 
                                 @endif
                                    
                             </h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        @if(count($transportationType->serviceTypes) != null)
                            @foreach($transportationType->serviceTypes as $serviceType)
                                @if($serviceType->status != 0)
                                    <div class="col-sm-4">
                                        <div class="service" style="
                                            background-color: rgba(248,248,249,1);padding: 15px;
                                            border-radius: 15px;line-height: 25px;margin-bottom: 5px;">

                                            <img src="{{asset($serviceType->image)}}"
                                                style="width: 100px;height: 100px">
                                            <p><strong>
                                                @if(app()->getlocale() == 'ar')
                                                    {{$serviceType->name}}
                                                    @else
                                                    {{$serviceType->name_en}}
                                                    @endif
                                                </strong><br>
                                               @lang('Base fixed') {{$serviceType->fixed}}
                                            </p>
                                            {{--                                    <a href="{{url('services-transportation/'.$transportationType->id)}}" class="btn btn-circle btn-primary">انواع الخدمات</a>--}}
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="alert alert-danger" style="margin-top: 50px">
                                <h2>لا توجد خدمات</h2>
                            </div>
                        @endif
                    </div>
                    @endisset
                </div>
            </section>
        </div>
    </div>

@endsection