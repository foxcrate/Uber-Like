@extends('admin.layout.base')

@section('title', 'Update Dispatcher ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    <a href="{{ route('admin.dispatch-manager.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.Back')</a>

			<h5 style="margin-bottom: 2em;">@lang('admin.Update Dispatcher')</h5>

            <form class="form-horizontal" action="{{route('admin.dispatch-manager.update', $dispatcher->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">

				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">@lang('admin.Full Name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $dispatcher->name }}" name="name" required id="name" placeholder="@lang('admin.Full Name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-2 col-form-label">@lang('admin.Email')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $dispatcher->email }}" readonly="true" name="email" required id="email" placeholder="@lang('admin.Email')">
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-2 col-form-label">@lang('admin.Mobile')</label>
					<div class="col-xs-10">
						<input class="form-control" type="floa" value="{{ $dispatcher->mobile }}" name="mobile" required id="mobile" placeholder="@lang('admin.Mobile')">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.Update Dispatcher')</button>
						<a href="{{route('admin.dispatch-manager.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
