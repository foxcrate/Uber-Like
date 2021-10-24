@extends('admin.layout.base')

@section('title', 'Add revenue ')

@section('content')
    @include('flash::message')
    <div class="content-area py-1">
        <div class="container-fluid">
            {{-- <p>{{ $d3}}</p> --}}
            <div class="box box-block bg-white">
                <a href="{{ route('admin.revenue.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Add revenue')</h5>



                <form class="form-horizontal" action="{{route('admin.revenue.store')}}" method="POST"
                      enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    <div class="form-group row">
                        <label class="col-xs-2 col-form-label" for="provider_id">{{trans('admin.provider_id')}}</label>
                        <div class="col-xs-10">
                                <select required class="form-control select2" id="provider_id" name="provider_id">
                                <option value="0">@lang('admin.You must choose the provider')</option>
                                @foreach($providers as $provider)
                                    <option value="{{ $provider->id}}">{{ $provider->email }} </option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('provider_id'))
                            <span class="help-block">
                            <strong>{{ $errors->first('provider_id')}}</strong>
                        </span>
                        @endif
                    </div>
                    <!-- <div class="form-group row">
                        <label class="col-xs-2 col-form-label" for="money">{{trans('admin.money')}}</label>
                        <div class="col-xs-10">
                                <select required class="form-control select2" id="money" name="money">
                                <option value="0">@lang('admin.You must choose the money')</option>
                                @foreach($service_types as $service_type)
                                    <option value="{{ $service_type->sub_com}}">{{ $service_type->name }} </option>
                                @endforeach
                            </select>
                        </div>
                        @if ($errors->has('money'))
                            <span class="help-block">
                            <strong>{{ $errors->first('money')}}</strong>
                        </span>
                        @endif
                    </div> -->
                    <div class="form-group row">
                        <label for="from" class="col-xs-2 col-form-label">@lang('admin.from')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="date" value="{{ date('Y-m-d')}}" name="from"
                                   required id="from" placeholder="@lang('admin.from')"readonly >
                        </div>
                    </div>


                    <div class="form-group row " style="display: none;">
                        <label for="to" class="col-xs-2 col-form-label">@lang('admin.to')</label>
                        <div class="col-xs-10">
                        @isset($revenuesMonthly)
                            @if($revenuesMonthly == 0)
                                <input class="form-control" type="date" value="{{ $d20}}" name="to"
                                     id="to" placeholder="@lang('admin.to')" >
                            @else
                                {{-- @if($month == 12)
                                    <input class="form-control" type="date" value="1" name="to"
                                     id="to" placeholder="@lang('admin.to')" >
                                @else --}}

                                    {{-- <input class="form-control" type="date" value="{{date('Y')}}-{{date('m')+1}}-20" name="to"
                                     id="to" placeholder="@lang('admin.to')" > --}}

                                    <input class="form-control" type="date" value= "{{ $newDate  }}" name="to"
                                     id="to" placeholder="@lang('admin.from')" >
                                {{-- @endif --}}
                            @endif
                        @endisset
                        </div>
                    </div>

                    <div class="form-group row ">
                        <label class="form-check-label mr-3" for="gift">
                            @lang('admin.gift')
                        </label>
                        <input class="form-check-input " type="checkbox" value="1" id="gift" name="gift">
                        
                    </div>

                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-2 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">@lang('admin.Add revenue')</button>

                            <a href="{{route('admin.revenue.index')}}"
                               class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </div>

@endsection
