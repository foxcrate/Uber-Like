@extends('admin.layout.base')

@section('title', 'Update User ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
		@include('flash::message')
    	<div class="box box-block bg-white">
    	    <a href="{{ route('admin.user.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.Back')</a>

			<h5 style="margin-bottom: 2em;">@lang('admin.Update_User')</h5>

            <form class="form-horizontal" action="{{route('admin.user.update', $user->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">
				<div class="form-group row">
					<label for="first_name" class="col-xs-2 col-form-label">@lang('admin.First_Name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ base64_decode($user->first_name) }}" name="first_name" required id="first_name" placeholder="@lang('admin.First_Name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="last_name" class="col-xs-2 col-form-label">@lang('admin.Last_Name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ base64_decode($user->last_name) }}" name="last_name" required id="last_name" placeholder="@lang('admin.Last_Name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-2 col-form-label">@lang('admin.Email')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $user->email }}" name="email"
							   required id="email" placeholder="@lang('admin.Email')">
					</div>
				</div>

				<div class="form-group row">
					<label for="mobile" class="col-xs-2 col-form-label">@lang('admin.Mobile')</label>
					<div class="col-xs-10">
						<input class="form-control" type="floa" value="{{ $user->mobile }}" name="mobile" required id="mobile" placeholder="@lang('admin.Mobile')">
					</div>
				</div>

				<div class="form-group row">

					<label for="picture" class="col-xs-2 col-form-label">@lang('admin.Picture')</label>
					<div class="col-xs-10">
					@if(isset($user->picture))
                    	<img style="height: 90px; margin-bottom: 15px; border-radius:2em;" src="{{asset($user->picture)}}">
                    @endif
						<input type="file" accept="image/*" name="picture" class="dropify form-control-file" id="picture" aria-describedby="fileHelp">
					</div>
				</div>



				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.Update_User')</button>
						<a href="{{route('admin.user.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
