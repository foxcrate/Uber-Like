@extends('admin.layout.base')

@section('title', 'Add Station ')

@section('content')
    @include('flash::message')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.station.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Add Station')</h5>

                <form class="form-horizontal" action="{{route('admin.station.store')}}" method="POST"
                      enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    <div class="form-group row">
                        <label for="station" class="col-xs-2 col-form-label">@lang('admin.station')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="text" name="station" required id="station" placeholder="@lang('admin.station')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="substation" class="col-xs-2 col-form-label">@lang('admin.substation')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="text" name="substation" id="substation" placeholder="@lang('admin.substation')">
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-12 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">@lang('admin.Add Station')</button>
                            <a href="{{route('admin.station.index')}}"
                               class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
