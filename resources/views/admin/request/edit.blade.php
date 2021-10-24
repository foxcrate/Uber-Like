@extends('admin.layout.base')

@section('title', 'Update Trip')

@section('content')

    <div class="content-area py-1">
        @include('flash::message')
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.car.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Update Trip')</h5>

                {{--            <form class="form-horizontal" action="{{route('admin.car.update', $model->id )}}" method="POST" enctype="multipart/form-data" role="form">--}}
                {!! Form::model($model, ['action' => ['Resource\TripResource@update',$model->id], 'method' =>'put']) !!}
                {{csrf_field()}}
                <input type="hidden" name="_method" value="PATCH">

                <div class="form-group row">
                    <label class="col-xs-2 col-form-label" for="provider_id">{{trans('admin.provider_id')}}</label>
                    <div class="col-xs-10">
                        <select required class="form-control select2" id="provider_id" name="provider_id">
                            <option value="0">لايوجد</option>
                            @foreach($providers as $index)
                                <option value="{{ $index->id}}" {{ $index->id == $model->provider_id ? 'selected' : '' }}>{{ $index->first_name }} {{ $index->last_name }} - {{ $index->email }} - {{ $index->mobile }}</option>
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
                    <label for="zipcode" class="col-xs-2 col-form-label"></label>
                    <div class="col-xs-10">
                        <button type="submit" class="btn btn-primary">@lang('admin.Update Account Manager')</button>
                        <a href="{{route('admin.admin.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

@endsection
