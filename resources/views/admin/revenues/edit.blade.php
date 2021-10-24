@extends('admin.layout.base')

@section('title', 'Update revenues ')

@section('content')
    @include('flash::message')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.revenue.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Update revenues')</h5>

                <form class="form-horizontal" action="{{route('admin.revenue.update', $model->id )}}"
                      method="POST" enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    <input type="hidden" name="_method" value="PATCH">

                    <div class="form-group row">
                        <label class="col-xs-2 col-form-label" for="provider_id">{{trans('admin.provider_id')}}</label>
                        <div class="col-xs-10">
                            <select required class="form-control select2" id="provider_id" name="provider_id">
                                <option value="0">@lang('admin.You must choose the provider')</option>
                                @foreach($provider as $index)
                                    <option value="{{ $index->id}}" {{ $index->id == $model->provider_id ? 'selected' : '' }}>{{ $index->email }} </option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('provider_id'))
                            <span class="help-block">
                            <strong>{{ $errors->first('provider_id')}}</strong>
                        </span>
                        @endif
                    </div>

                    <div class="form-group row">
                        <label class="col-xs-2 col-form-label" for="money">{{trans('admin.money')}}</label>
                        <div class="col-xs-10">
                                <select required class="form-control select2" id="money" name="money">
                                <option value="0">@lang('admin.You must choose the money')</option>
                                @foreach($service_types as $service_type)
                                    <option value="{{ $service_type->sub_com}}"{{ $service_type->sub_com == $model->money ? 'selected' : '' }}>{{ $service_type->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('money'))
                            <span class="help-block">
                            <strong>{{ $errors->first('money')}}</strong>
                        </span>
                        @endif
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
                            <a href="{{route('admin.revenue.index')}}"
                               class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
