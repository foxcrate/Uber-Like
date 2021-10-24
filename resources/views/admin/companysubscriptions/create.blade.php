@extends('admin.layout.base')

@section('title', 'Add company subscription ')

@section('content')
    @include('flash::message')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.company-subscription.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Add company subscription')</h5>

                <form class="form-horizontal" action="{{route('admin.company-subscription.store')}}" method="POST"
                      enctype="multipart/form-data" role="form">
                    {{csrf_field()}}

                    <div class="form-group row">
                        <label class="col-xs-2 col-form-label" for="fleet_id">{{trans('admin.fleet_id')}}</label>
                        <div class="col-xs-10">
                            <select required class="form-control select2" id="fleet_id" name="fleet_id">
                                <option value="0">@lang('admin.You must choose the Fleet')</option>
                                @foreach($fleet as $index)
                                    <option value="{{ $index->id}}">{{ $index->name }} - {{ $index->email }} </option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('fleet_id'))
                            <span class="help-block">
                            <strong>{{ $errors->first('fleet_id')}}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group row">
                        <label for="money" class="col-xs-2 col-form-label">@lang('admin.money')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="text" value="{{ old('money') }}" name="money"
                                   required id="money" placeholder="@lang('admin.money')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="from" class="col-xs-2 col-form-label">@lang('admin.from')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="date" value="{{ old('from') }}" name="from"
                                   required id="from" placeholder="@lang('admin.from')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="to" class="col-xs-2 col-form-label">@lang('admin.to')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="date" value="{{ old('to') }}" name="to"
                                   required id="to" placeholder="@lang('admin.to')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-2 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">@lang('admin.Add company subscription')</button>
                            <a href="{{route('admin.company-subscription.index')}}"
                               class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
