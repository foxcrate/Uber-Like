@extends('admin.layout.base')

@section('title', 'Add Fleet Owner ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.fleet.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.Back')</a>

			<h5 style="margin-bottom: 2em;">@lang('admin.Add Fleet Owner')</h5>

            <form class="form-horizontal" action="{{route('admin.fleet.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="name" class="col-xs-12 col-form-label">@lang('admin.Full Name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('name') }}" name="name" required id="name" placeholder="@lang('admin.Full Name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="company" class="col-xs-12 col-form-label">@lang('admin.Company Name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('company') }}" name="company" required id="company" placeholder="@lang('admin.Company Name')">
					</div>
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
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('admin.Password Confirmation')">
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-12 col-form-label">@lang('admin.Mobile')</label>
					<div class="col-xs-10">
						<input class="form-control" type="floa" value="{{ old('mobile') }}" name="mobile" required id="mobile" placeholder="@lang('admin.Mobile')">
					</div>
				</div>

				<div class="form-group row">
					<label for="number_tax_card" class="col-xs-12 col-form-label">@lang('admin.Number_tax_card')</label>
					<div class="col-xs-10">
						<input class="form-control" type="floa" value="{{ old('number_tax_card') }}" name="number_tax_card" required id="number_tax_card" placeholder="@lang('admin.Number_tax_card')">
					</div>
				</div>

				<div class="form-group row">
					<label for="logo" class="col-xs-12 col-form-label">@lang('admin.Company_logo')</label>
					<div class="col-xs-10">
						<input type="file" accept="image/*" name="logo" class="dropify form-control-file" id="logo" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="commercial_register" class="col-xs-12 col-form-label">@lang('admin.Company_commercial_register')</label>
					<div class="col-xs-10">
						<input type="file" accept="image/*" name="commercial_register" class="dropify form-control-file" id="commercial_register" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="tax_card" class="col-xs-12 col-form-label">@lang('admin.Company_tax_card')</label>
					<div class="col-xs-10">
						<input type="file" accept="image/*" name="tax_card" class="dropify form-control-file" id="tax_card" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.Add Fleet Owner')</button>
						<a href="{{route('admin.fleet.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
