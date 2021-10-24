@extends('admin.layout.base')

@section('title', trans('admin.Edit Send Message'))

@section('content')


{{--    <!--Zoom effect-->--}}
{{--    <div class="view overlay zoom">--}}
{{--        <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/6-col/img%20(131).jpg" class="img-fluid " alt="zoom">--}}
{{--        <div class="mask flex-center waves-effect waves-light">--}}
{{--            <p class="white-text">Zoom effect</p>--}}
{{--        </div>--}}
{{--    </div>--}}

{{--    <!-- Hoverable -->--}}
{{--    <img src="https://mdbootstrap.com/img/Photos/Horizontal/Nature/6-col/img%20(132).jpg" class="img-fluid rounded-circle hoverable"--}}
{{--         alt="hoverable">--}}

    <div class="content-area py-1">
        @include('flash::message')
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.provider.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i>@lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Edit Send Message')</h5>

                <form class="form-horizontal" action="{{route('admin.post_send_message', $provider->id )}}"
                      method="POST" enctype="multipart/form-data" role="form">
                    {{csrf_field()}}

                    <div class="row">
                        @if($provider->avatar != null)
                            <div class="col-md-6">
                                <label class="col-md-12"> @lang('user.avatar') </label>
                                @if(isset($provider->avatar))
                                    <button style="background-color: white;border: 0;" type="button"
                                            class="btn btn-primary btn-lg"
                                            data-toggle="modal"
                                            data-target="#favoritesModal">


                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($provider->avatar)}}">
                                    </button>
                                @endif
                                <div class="col-md-12" style="margin-bottom: 20px">
                                    {!! Form::radio('avatar_status', '0', null) !!} @lang('user.Not Edit Image')

                                    {!! Form::radio('avatar_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                        @else
                        <div class="col-md-6">
                            <p>NULL</p>
                        </div>
                        @endif
                        @if($provider->driver_licence_front != null)
                            <div class="col-md-6">
                                <label class="col-md-12"> @lang('user.Driver Licence Front') </label>
                                @if(isset($provider->driver_licence_front))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal2">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                            src="{{asset($provider->driver_licence_front)}}">
                                    </button>
                                @endif

                                @if(isset($provider->identity_number))
                                <div class="col-md-12">
                                    <p class="col-sm-3"><mark >{{$provider->identity_number}}</mark></p>
                                    <p class="col-sm-4">@lang('user.profile.drivingLincenceNumber')</p>
                                </div>
                                @endif

                                <div class="col-md-12" style="margin-bottom: 20px">
                                     {!! Form::radio('driver_licence_front_status', '0', null) !!} @lang('user.Not Edit Image')
                                    {!! Form::radio('driver_licence_front_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                        @else
                        <div class="col-md-6">
                            <p>NULL</p>
                        </div>
                        @endif
                        @if($provider->driver_licence_back != null)
                            <div class="col-md-6">
                                <label class="col-md-12"> @lang('user.Driver Licence Back') </label>
                                @if(isset($provider->driver_licence_back))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal3">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($provider->driver_licence_back)}}">
                                    </button>
                                @endif
                                <div class="col-md-12" style="margin-bottom: 20px">
                                {!! Form::radio('driver_licence_back_status', '0', null) !!} @lang('user.Not Edit Image')
                                    {!! Form::radio('driver_licence_back_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                        @else
                            <div class="col-md-6">
                                <p>NULL</p>
                            </div>
                        @endif
                        @if($provider->identity_front != null)
                            <div class="col-md-6">
                                <label class="col-md-12">@lang('user.Identity Front') </label>
                                @if(isset($provider->identity_front))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal4">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($provider->identity_front)}}">
                                    </button>
                                @endif

                                @if(isset($provider->identity_number))
                                <div class="col-md-12">
                                    <p class="col-sm-3"><mark >{{$provider->identity_number}}</mark></p>
                                    <p class="col-sm-3">@lang('user.profile.identity_number')</p>
                                </div>
                                @endif

                                <div class="col-md-12" style="margin-bottom: 20px">
                                     {!! Form::radio('identity_front_status', '0', null) !!} @lang('user.Not Edit Image')
                                    {!! Form::radio('identity_front_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                            @else
                        <div class="col-md-6">
                            <p>NULL</p>
                        </div>
                        @endif
                        @if($provider->identity_back != null)
                            <div class="col-md-6">
                                <label class="col-md-12"> @lang('user.Identity Back') </label>
                                @if(isset($provider->identity_back))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal5">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($provider->identity_back)}}">
                                    </button>
                                @endif
                                <div class="col-md-12" style="margin-bottom: 20px">
                                     {!! Form::radio('identity_back_status', '0', null) !!} @lang('user.Not Edit Image')
                                    {!! Form::radio('identity_back_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                            @else
                        <div class="col-md-6">
                            <p>NULL</p>
                        </div>
                        @endif
                        @if($provider->criminal_feat != null)
                            <div class="col-md-6">
                                <label class="col-md-12"> @lang('user.criminal_feat') </label>
                                @if(isset($provider->criminal_feat))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal6">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($provider->criminal_feat)}}">
                                    </button>
                                @endif
                                <div class="col-md-12" style="margin-bottom: 20px">
                                    {!! Form::radio('criminal_feat_status', '0', null) !!} @lang('user.Not Edit Image')
                                    {!! Form::radio('criminal_feat_status', '1', null) !!} @lang('user.Edit Image')
                                </div>
                            </div>
                            @else
                        <div class="col-md-6">
                            <p>NULL</p>
                        </div>
                        @endif
                        @if($provider->drug_analysis_licence != null)

                            <div class="col-md-6">
                                <label class="col-md-12"> @lang('user.analysis_licence') </label>
                                @if(isset($provider->drug_analysis_licence))
                                    <button style="background-color: white;border: 0;" type="button" class="btn btn-primary btn-lg"
                                            data-toggle="modal" data-target="#favoritesModal7">
                                        <img style="height: 90px; margin-bottom: 15px; border-radius:2em;margin-top: 15px;"
                                             src="{{asset($provider->drug_analysis_licence)}}">
                                    </button>
                                @endif
                                <div class="col-md-12" style="margin-bottom: 20px">
                                    {!! Form::radio('drug_analysis_licence_status', '0', null) !!} @lang('user.Not Edit Image')

                                    {!! Form::radio('drug_analysis_licence_status', '1', null) !!} @lang('user.Edit Image')
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
                            <input class="form-control" type="text" value="{{ $provider->message }}" name="message"
                                   required id="message" placeholder="@lang('admin.message')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-12 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">@lang('admin.Send Message')</button>
                            <a href="{{route('admin.provider.index')}}"
                               class="btn btn-default">@lang('admin.Cancel')</a>
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
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.avatar')</h4>
                </div>
                <div class="modal-body">
                    @if($provider->avatar != null)
                        @if(isset($provider->avatar))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($provider->avatar)}}">
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
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.Driver Licence Front')</h4>
                </div>
                <div class="modal-body">
                    @if($provider->driver_licence_front != null)
                        @if(isset($provider->driver_licence_front))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($provider->driver_licence_front)}}">
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
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.Driver Licence Back')</h4>
                </div>
                <div class="modal-body">
                    @if($provider->driver_licence_back != null)
                        @if(isset($provider->driver_licence_back))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($provider->driver_licence_back)}}">
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
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.Identity Front')</h4>
                </div>
                <div class="modal-body">
                    @if($provider->identity_front != null)
                        @if(isset($provider->identity_front))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($provider->identity_front)}}">
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
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.Identity Back')</h4>
                </div>
                <div class="modal-body">
                    @if($provider->identity_back != null)
                        @if(isset($provider->identity_back))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($provider->identity_back)}}">
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
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.criminal_feat')</h4>
                </div>
                <div class="modal-body">
                    @if($provider->criminal_feat != null)
                        @if(isset($provider->criminal_feat))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($provider->criminal_feat)}}">
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="favoritesModal7"
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
                    <h4 class="modal-title" id="favoritesModalLabel">@lang('user.analysis_licence')</h4>
                </div>
                <div class="modal-body">
                    @if($provider->drug_analysis_licence != null)
                        @if(isset($provider->drug_analysis_licence))
                            <img style="height: 500px; width:100% ;"
                                 src="{{asset($provider->drug_analysis_licence)}}">
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script>
        // MDB Lightbox Init
        $(function () {
            $("#mdb-lightbox-ui").load("mdb-addons/mdb-lightbox-ui.html");
        });
    </script>
@endsection
