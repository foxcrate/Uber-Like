@extends('admin.layout.base')

@section('title', 'Update Company Subscription ')

@section('content')
    @include('flash::message')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.company-subscription.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Update company subscription')</h5>

                <form class="form-horizontal" action="{{route('admin.company-subscription.update', $model->id )}}"
                      method="POST" enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="PATCH">

                    <div class="form-group row">
                        <label class="col-xs-2 col-form-label" for="fleet_id">{{trans('admin.fleet_id')}}</label>
                        <div class="col-xs-10">
                            <select required class="form-control select2" id="fleet_id" name="fleet_id">
                                <option value="0">@lang('admin.You must choose the Fleet')</option>
                                @foreach($fleet as $index)
                                    <option value="{{ $index->id}}" {{ $index->id == $model->fleet_id ? 'selected' : '' }}>{{ $index->name }} - {{ $index->email }} </option>
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
                            <input class="form-control" type="floa" value="{{ $model->money }}" name="money"
                                   required id="money" placeholder="@lang('admin.money')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="from" class="col-xs-2 col-form-label">@lang('admin.from')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="date"
                                   value="{{ date('Y-m-d',strtotime($model->from)) }}" name="from"
                                   required id="from" placeholder="@lang('admin.from')">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="to" class="col-xs-2 col-form-label">@lang('admin.to')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="date"
                                   value="{{ date('Y-m-d',strtotime($model->to)) }}" name="to"
                                   required id="to" placeholder="@lang('admin.to')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-2 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                            <a href="{{route('admin.company-subscription.index')}}"
                               class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
