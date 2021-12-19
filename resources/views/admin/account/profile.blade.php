@extends('admin.layout.base')

@section('title', 'Update Profile ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">

			<h5 style="margin-bottom: 2em;">@lang('admin.Update Profile')</h5>

            <form class="form-horizontal" action="{{route('admin.profile.update')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}

				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">@lang('admin.Name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ Auth::guard('admin')->user()->name }}" name="name" required id="name" placeholder="@lang('admin.Name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-2 col-form-label">@lang('admin.Email')</label>
					<div class="col-xs-10">
						<input class="form-control" required="" type="email" name="email"   required id="email" placeholder="@lang('admin.Email')" value="{{ isset(Auth::guard('admin')->user()->email) ? Auth::guard('admin')->user()->email : '' }}">
					</div>
				</div>

				<div class="form-group row">
					<label for="picture" class="col-xs-2 col-form-label">@lang('admin.Picture')</label>
					<div class="col-xs-10">
						@if(isset(Auth::guard('admin')->user()->picture))
	                    	<img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{asset(auth()->guard('admin')->user()->picture)}}">
	                    @endif
						<input type="file" accept="image/*" name="picture" class=" dropify form-control-file" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.Update Profile')</button>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
