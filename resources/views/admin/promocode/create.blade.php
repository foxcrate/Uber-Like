@extends('admin.layout.base')

@section('title', 'Add Promocode ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.promocode.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Add Promocode')</h5>

                <form class="form-horizontal" action="{{route('admin.promocode.store')}}" method="POST"
                      enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    <div class="form-group row">
                        <label for="promo_code" class="col-xs-2 col-form-label">@lang('admin.Promocode')</label>
                        <div class="col-xs-10">
                            <?php
                            $randomFloat = rand();
                            ?>
                            <input class="form-control" autocomplete="off" type="text" value="{{$randomFloat}}"
                                   name="promo_code" required id="promo_code" placeholder="@lang('admin.Promocode')">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="discount" class="col-xs-2 col-form-label">@lang('admin.Discount')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="floa" value="{{ old('discount') }}" name="discount"
                                   required id="discount" placeholder="@lang('admin.Discount')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="expiration" class="col-xs-2 col-form-label">@lang('admin.Expiration')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="date" value="{{ old('expiration') }}" name="expiration"
                                   required id="expiration" placeholder="@lang('admin.Expiration')">
                        </div>
                    </div>
                    @if(count($fullName) != 0)
                    <div class="form-group row">
                        <label for="users_list" class="col-xs-2 col-form-label">{{trans('admin.users_list')}}</label>
                        <div class="col-xs-10">
                            {!! Form::select('users_list[]',$fullName,null, [
                                'class'=>'select2 js-states form-control',
                                'multiple' => 'multiple',
                            ]) !!}
                        </div>
                    </div>
                    @else
                        <div class="form-group">
                            <label for="users_list">{{trans('admin.users_list')}}</label>
                            <div class="alert alert-danger"> {{trans('admin.users_list_zero')}}</div>
                        </div>
                    @endif


                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-2 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">@lang('admin.Add Promocode')</button>
                            <a href="{{route('admin.promocode.index')}}"
                               class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
