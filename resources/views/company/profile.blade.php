@extends('company.layout.base')
@section('title', 'Update Provider ')

@section('content')

    <div class="content-area py-1">
        @include('flash::message')
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <a href="{{ route('company.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i>@lang('admin.Back')</a>

                <h2 style="margin-bottom: 2em;">@lang('admin.Update_Fleet')</h2>

                {{-- <form class="form-horizontal" action="{{route('company.profile.update', auth('fleet')->user()->id )}}" method="POST" enctype="multipart/form-data" role="form">
                    {{csrf_field()}} --}}
                    <div class="form-group row">
                        <label for="name" class="col-xs-12 col-form-label">@lang('admin.company_owner_name')</label>
                        <div class="col-xs-12">
                            <input disabled class="form-control" type="text" value="{{ auth('fleet')->user()->name }}" name="name" required id="name" placeholder="@lang('admin.name')">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="company" class="col-xs-12 col-form-label">@lang('admin.company_name')</label>
                        <div class="col-xs-12">
                            <input disabled class="form-control" type="text" value="{{ auth('fleet')->user()->company }}" name="company" required id="company" placeholder="@lang('admin.company')">
                        </div>
                    </div>

                    {{--<div class="form-group row">--}}
                    {{--    <label for="email" class="col-xs-12 col-form-label">@lang('admin.Email')</label>--}}
                    {{--    <div class="col-xs-12">--}}
                    {{--        <input disabled class="form-control" type="text" value="{{ auth('fleet')->user()->email }}" name="email"--}}
                    {{--               required id="email" placeholder="@lang('admin.Email')">--}}
                    {{--    </div>--}}
                    {{--</div>--}}

                    <div class="form-group row">
                        <label for="logo" class="col-xs-12 col-form-label">@lang('admin.company_profile_picture')</label>
                        <div class="col-xs-12">
                            @if(! empty(auth('fleet')->user()->logo))
                                <img style="height: 90px; margin-bottom: 15px; border-radius:2em;"src="{{url('/'.auth('fleet')->user()->logo)}}">
                            @endif
                            <input disabled type="file" accept="image/*" name="logo" class=" dropify form-control" aria-describedby="fileHelp">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="mobile" class="col-xs-12 col-form-label">@lang('admin.Mobile')</label>
                        <div class="col-xs-12">
                            <input disabled class="form-control" type="floa" value="{{ auth('fleet')->user()->mobile }}" name="mobile" id="mobile" placeholder="@lang('admin.Mobile')">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="commercial_register" class="col-xs-12 col-form-label">@lang('admin.commercial_register')</label>
                        <div class="col-xs-12">
                            @if(! empty(auth('fleet')->user()->commercial_register))
                                <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/'.auth('fleet')->user()->commercial_register)}}">
                            @endif
                            <input disabled type="file" accept="image/*" name="commercial_register" class="dropify form-control" id="commercial_register" aria-describedby="fileHelp">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="tax_card" class="col-xs-12 col-form-label">@lang('admin.tax_card')</label>
                        <div class="col-xs-12">
                            @if(! empty(auth('fleet')->user()->tax_card))
                                <img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{url('/'.auth('fleet')->user()->tax_card)}}">
                            @endif
                            <input disabled type="file" accept="image/*" name="tax_card" class="dropify form-control" id="tax_card" aria-describedby="fileHelp">
                        </div>
                    </div>

                    {{-- <div class="form-group row">
                        <label for="zipcode" class="col-xs-12 col-form-label"></label>
                        <div class="col-xs-12">
                            <button type="submit" class="btn btn-primary">@lang('admin.Update_Provider')</button>
                            <a href="{{route('admin.provider.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
                        </div>
                    </div> --}}
                {{-- </form> --}}
            </div>
        </div>
    </div>

@endsection
