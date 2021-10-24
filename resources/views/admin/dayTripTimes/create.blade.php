@extends('admin.layout.base')

@section('title', 'Add day trip time ')

@section('content')
    @include('flash::message')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.day-trip-time.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Add day trip time')</h5>

                <form class="form-horizontal" action="{{route('admin.day-trip-time.store')}}" method="POST"
                      enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    <div class="form-group row">
                        <label for="day" class="col-xs-12 col-form-label">@lang('admin.day')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="date" value="{{ old('day') }}" name="day" required
                                   id="day" placeholder="@lang('admin.day')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="period" class="col-xs-12 col-form-label">@lang('admin.period')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="text" value="{{ old('period') }}" name="period" required
                                   id="period" placeholder="@lang('admin.period')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="from" class="col-xs-12 col-form-label">@lang('admin.from')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="time" value="{{ old('from') }}" name="from" required
                                   id="from" placeholder="@lang('admin.from')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="to" class="col-xs-12 col-form-label">@lang('admin.to')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="time" value="{{ old('to') }}" name="to" required
                                   id="to" placeholder="@lang('admin.to')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-12 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">@lang('admin.Add day trip time')</button>
                            <a href="{{route('admin.day-trip-time.index')}}"
                               class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
