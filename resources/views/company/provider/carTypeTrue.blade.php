@extends('company.layout.base')

@section('title', 'Add Provider ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('company.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i>@lang('admin.Back') </a>

			<h5 style="margin-bottom: 2em;">@lang('admin.Add_Provider')</h5>

            <form class="form-horizontal" action="{{route('company.postCarTypeTrue',$provider->id)}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<input id="car_code" type="text" class="form-control" name="car_code" value="{{ old('car_code') }}"
					   placeholder="@lang('provider.car_code')" autofocus>

				@if ($errors->has('car_code'))
					<span class="help-block">
                    <strong>{{ $errors->first('car_code') }}</strong>
                </span>
				@endif

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.Add_Provider')</button>
						<a href="{{route('company.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
