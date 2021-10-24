@extends('admin.layout.base')

@section('title', trans('admin.Edit Send Message'))

@section('content')

    <div class="content-area py-1">
        @include('flash::message')
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.car.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i>@lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Edit Send Message')</h5>

                <form class="form-horizontal" action="{{route('admin.car.post_send_message', $car->id )}}" method="POST" enctype="multipart/form-data" role="form">
                    {{csrf_field()}}


                    <div class="row">
                        @if($car->car_front != null)
                            <div class="col-md-6">
                                <label class="col-md-12"> @lang('user.car_front') </label>
                                @if(isset($car->car_front))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($car->car_front)}}">
                                    </button>
{{--                                    <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"--}}
{{--                                         src="{{asset($car->car_front)}}">--}}
                                @endif
                                <div class="col-md-12" style="margin-bottom: 20px">
{{--                                    {!! Form::radio('car_front_status', '0', true) !!} @lang('user.Not Edit Image')--}}

                                    {!! Form::radio('car_front_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                        @else
                        <div class="col-md-6">
                            <p>NULL</p>
                        </div>
                        @endif
                        @if($car->car_back != null)
                            <div class="col-md-6">
                                <label class="col-md-12"> @lang('user.car_back') </label>
                                @if(isset($car->car_back))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal2">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($car->car_back)}}">
                                    </button>
                                @endif
                                <div class="col-md-12" style="margin-bottom: 20px">
                                    {!! Form::radio('car_back_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                        @else
                        <div class="col-md-6">
                            <p>NULL</p>
                        </div>
                        @endif
                        @if($car->car_left != null)
                            <div class="col-md-6">
                                <label class="col-md-12"> @lang('user.car_left') </label>
                                @if(isset($car->car_left))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal3">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($car->car_left)}}">
                                    </button>
                                @endif
                                <div class="col-md-12" style="margin-bottom: 20px">
                                    {!! Form::radio('car_left_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                        @else
                        <div class="col-md-6">
                            <p>NULL</p>
                        </div>
                        @endif
                        @if($car->car_right != null)
                            <div class="col-md-6">
                                <label class="col-md-12">@lang('user.car_right') </label>
                                @if(isset($car->car_right))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal4">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($car->car_right)}}">
                                    </button>
                                @endif
                                <div class="col-md-12" style="margin-bottom: 20px">
{{--                                    {!! Form::radio('car_right_status', '0', true) !!} @lang('user.Not Edit Image')--}}

                                    {!! Form::radio('car_right_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                        @else
                        <div class="col-md-6">
                            <p>NULL</p>
                        </div>
                        @endif
                        @if($car->car_licence_front != null)
                            <div class="col-md-6">
                                <label class="col-md-12"> @lang('user.car_licence_front') </label>
                                @if(isset($car->car_licence_front))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal5">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($car->car_licence_front)}}">
                                    </button>
                                @endif
                                <div class="col-md-12" style="margin-bottom: 20px">
{{--                                    {!! Form::radio('car_licence_front_status', '0', true) !!} @lang('user.Not Edit Image')--}}

                                    {!! Form::radio('car_licence_front_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                        @else
                        <div class="col-md-6">
                            <p>NULL</p>
                        </div>
                        @endif
                        @if($car->car_licence_back != null)
                            <div class="col-md-6">
                                <label class="col-md-12"> @lang('user.car_licence_back') </label>
                                @if(isset($car->car_licence_back))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal6">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($car->car_licence_back)}}">
                                    </button>

                                @endif
                                <div class="col-md-12" style="margin-bottom: 20px">
{{--                                    {!! Form::radio('car_licence_back_status', '0', true) !!} @lang('user.Not Edit Image')--}}

                                    {!! Form::radio('car_licence_back_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                        @else
                        <div class="col-md-6">
                            <p>NULL</p>
                        </div>
                        @endif
                    </div>
                    <br>

                    <div class="form-group row">
                        <label for="message" class="col-xs-12 col-form-label">@lang('admin.message')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="text" value="{{ $car->message }}" name="message" required id="message" placeholder="@lang('admin.message')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-12 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">@lang('admin.Send Message')</button>
                            <a href="{{route('admin.provider.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="favoritesModal"
         tabindex="-1" role="dialog"
         aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" id="atebbtngrow" > <span aria-hidden="true" > + </span>  <span> </span>
                        </button> -->
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.car_front')</h4>
                </div>
                <div class="modal-body">
                    @if($car->car_front != null)
                        @if(isset($car->car_front))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($car->car_front)}}">
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="favoritesModal2"
         tabindex="-1" role="dialog"
         aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" id="atebbtngrow" > <span aria-hidden="true" > + </span>  <span> </span>
                        </button> -->
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.car_back')</h4>
                </div>
                <div class="modal-body">
                    @if($car->car_back != null)
                        @if(isset($car->car_back))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($car->car_back)}}">
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="favoritesModal3"
         tabindex="-1" role="dialog"
         aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" id="atebbtngrow" > <span aria-hidden="true" > + </span>  <span> </span>
                        </button> -->
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.car_left')</h4>
                </div>
                <div class="modal-body">
                    @if($car->car_left != null)
                        @if(isset($car->car_left))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($car->car_left)}}">
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="favoritesModal4"
         tabindex="-1" role="dialog"
         aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" id="atebbtngrow" > <span aria-hidden="true" > + </span>  <span> </span>
                        </button> -->
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.car_right')</h4>
                </div>
                <div class="modal-body">
                    @if($car->car_right != null)
                        @if(isset($car->car_right))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($car->car_right)}}">
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="favoritesModal5"
         tabindex="-1" role="dialog"
         aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" id="atebbtngrow" > <span aria-hidden="true" > + </span>  <span> </span>
                        </button> -->
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.car_licence_front')</h4>
                </div>
                <div class="modal-body">
                    @if($car->car_licence_front != null)
                        @if(isset($car->car_licence_front))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($car->car_licence_front)}}">
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="favoritesModal6"
         tabindex="-1" role="dialog"
         aria-labelledby="favoritesModalLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <!-- <button type="button" id="atebbtngrow" > <span aria-hidden="true" > + </span>  <span> </span>
                        </button> -->
                    <button type="button" class="close"
                            data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.car_licence_back')</h4>
                </div>
                <div class="modal-body">
                    @if($car->car_licence_back != null)
                        @if(isset($car->car_licence_back))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($car->car_licence_back)}}">
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
