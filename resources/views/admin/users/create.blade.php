@extends('admin.layout.base')

@section('title', 'Add User ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
		@include('flash::message')
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.user.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.Back')</a>

			<h5 style="margin-bottom: 2em;">@lang('admin.Add_User')</h5>

            <form class="form-horizontal" action="{{route('admin.user.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	@csrf
				<div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label">@lang('admin.First_Name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('first_name') }}" name="first_name" required id="first_name" placeholder="@lang('admin.First_Name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="last_name" class="col-xs-12 col-form-label">@lang('admin.Last_Name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('last_name') }}" name="last_name" required id="last_name" placeholder="@lang('admin.Last_Name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-12 col-form-label">@lang('admin.Mobile')</label>
					<div class="col-md-2">
						<input value="+2" type="text" placeholder="+2" id="country_code" class="form-control" name="country_code"/>
					</div>

					<div class="col-md-8">
						<input type="text" autofocus id="phone_number" class="form-control" placeholder="01010209147"
							   name="phone_number" value="{{ old('phone_number') }}"/>
					</div>

					@if ($errors->has('phone_number'))
						<span class="help-block">
                        <strong>{{ $errors->first('phone_number') }}</strong>
                    </span>
					@endif
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-12 col-form-label">@lang('admin.Email')</label>
					<div class="col-xs-10">
						<input class="form-control" type="email" required name="email" value="{{old('email')}}" id="email" placeholder="@lang('admin.Email')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password" class="col-xs-12 col-form-label">@lang('admin.Password')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password" id="password" placeholder="@lang('admin.Password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password_confirmation" class="col-xs-12 col-form-label">@lang('admin.Password Confirmation')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('admin.Re_type_Password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="picture" class="col-xs-12 col-form-label">@lang('admin.Picture')</label>
					<div class="col-xs-10">
						<input type="file" accept="image/*" name="picture" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.Add_User')</button>
						<a href="{{route('admin.user.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
