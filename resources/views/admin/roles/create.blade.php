@extends('admin.layout.base')
@inject('perm',Spatie\Permission\Models\Permission)

<?php
if(app()->getLocale()=="en") {
	$permissions = $perm->pluck('name_en')->toArray();
} else {
	$permissions = $perm->pluck('name')->toArray();

}
?>
@section('title', 'Add Account Manager ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.role.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.Back')</a>

			<h5 style="margin-bottom: 2em;">@lang('admin.Add Role')</h5>

            <form class="form-horizontal" action="{{route('admin.role.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="name" class="col-xs-12 col-form-label">@lang('admin.name_ar')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('name') }}" name="name" required id="name" placeholder="@lang('admin.name_ar')">
					</div>
				</div>

				<div class="form-group row">
					<label for="name_en" class="col-xs-12 col-form-label">@lang('admin.name_en')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" required name="name_en" value="{{old('name_en')}}" id="name_en" placeholder="@lang('admin.name_en')">
					</div>
				</div>

				@if(count($permissions) != 0)
					<div class="form-group row">
						<label for="permissions_list" class="col-xs-12 col-form-label">{{trans('admin.permissions_list')}}</label>
						<div class="col-xs-10">
							<select name="permissions_list[]" class='select2 js-states form-control' multiple required id="permissions_list">
								@foreach($permissions as $permission)
									<option value="{{ $permission }}">{{ $permission }}</option>
									@endforeach
							</select>
						</div>
					</div>
				@else
					    <div class="form-group">
					        <label for="permissions_list">{{trans('admin.permissions_list')}}</label>
					        <div class="alert alert-danger"> {{trans('admin.permissions_list_zero')}}</div>
					    </div>
                    @endif

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.Add Role')</button>
						<a href="{{route('admin.role.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
