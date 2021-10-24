@extends('admin.layout.base')
@section('title', 'Update Permission')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    <a href="{{ route('admin.permission.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.Back')</a>

			<h5 style="margin-bottom: 2em;">@lang('admin.Update Permission')</h5>

            <form class="form-horizontal" action="{{route('admin.permission.update', $model->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">

				<div class="form-group row">
					<label for="name" class="col-xs-12 col-form-label">@lang('admin.name_ar')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text"  value="{{ $model->name }}" name="name" required id="name" placeholder="@lang('admin.name_ar')">
					</div>
				</div>

				<div class="form-group row">
					<label for="name_en" class="col-xs-12 col-form-label">@lang('admin.name_en')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" required name="name_en" value="{{ $model->name_en }}"  id="name_en" placeholder="@lang('admin.name_en')">
					</div>
				</div>

				<div class="form-group row">
					<label for="routes" class="col-xs-12 col-form-label">@lang('admin.routes')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" required name="routes" value="{{ $model->routes }}"  id="routes" placeholder="@lang('admin.routes')">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.Update Role')</button>
						<a href="{{route('admin.role.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
