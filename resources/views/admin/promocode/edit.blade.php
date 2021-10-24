@extends('admin.layout.base')
@inject('user', App\User)

<?php
$users = $user->pluck('mobile', 'id')->toArray();
?>
@section('title', 'Update Promocode ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.promocode.index') }}" class="btn btn-default pull-right"><i
                            class="fa fa-angle-left"></i> @lang('admin.Back')</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.Update Promocode')</h5>

                <form class="form-horizontal" action="{{route('admin.promocode.update', $promocode->id )}}"
                      method="POST" enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    {{--				{!! Form::model($model, [--}}
                    {{--                                'action' => ['Resource\PromocodeResource@update',$model->id],--}}
                    {{--                                'method' =>'put'--}}
                    {{--                            ]) !!}--}}
                    <input type="hidden" name="_method" value="PATCH">
                    <div class="form-group row">
                        <label for="promo_code" class="col-xs-2 col-form-label">@lang('admin.Promocode')</label>
                        <div class="col-xs-10">
                            <?php
                            $randomFloat = rand();
                            ?>
                            <input class="form-control" type="text" value="{{$randomFloat}}" name="promo_code" required
                                   id="promo_code" placeholder="@lang('admin.Promocode')">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="discount" class="col-xs-2 col-form-label">@lang('admin.Discount')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="floa" value="{{ $promocode->discount }}" name="discount"
                                   required id="discount" placeholder="@lang('admin.Discount')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="expiration" class="col-xs-2 col-form-label">@lang('admin.Expiration')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="date"
                                   value="{{ date('Y-m-d',strtotime($promocode->expiration)) }}" name="expiration"
                                   required id="expiration" placeholder="@lang('admin.Expiration')">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="users_list" class="col-xs-2 col-form-label">{{trans('admin.users_list')}}</label>
                        <div class="col-xs-10">
                            <select name="users_list[]" multiple class="select2 js-states form-control" id="users_list">
                                @foreach($fullName as $key =>$name)
                                    <option {{ in_array($key,$promocode->usersList) ? 'selected' : '' }} value="{{$key}}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-2 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">حفظ</button>
                            <a href="{{route('admin.promocode.index')}}"
                               class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
