@extends('admin.layout.base')

@section('title', trans('admin.addGovernorate'))

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('admin.governorate.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

                <h5 style="margin-bottom: 2em;">@lang('admin.addGovernorate')</h5>

                <form class="form-horizontal" action="{{route('admin.governorate.store')}}" method="POST" enctype="multipart/form-data" role="form">
                    {{csrf_field()}}
                    <div class="form-group row">
                        <label for="name" class="col-xs-12 col-form-label">@lang('admin.name_ar')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="text" value="{{ old('name') }}" name="name" required id="name" placeholder="{{trans('admin.name_ar')}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="name_en" class="col-xs-12 col-form-label">@lang('admin.name_en')</label>
                        <div class="col-xs-10">
                            <input class="form-control" type="text" value="{{ old('name_en') }}" name="name_en" required id="name_en" placeholder="{{trans('admin.name_en')}}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="zipcode" class="col-xs-12 col-form-label"></label>
                        <div class="col-xs-10">
                            <button type="submit" class="btn btn-primary">@lang('admin.addGovernorate')</button>
                            <a href="{{route('admin.governorate.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection