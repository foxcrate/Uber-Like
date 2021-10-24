@extends('admin.layout.base')
@inject('role',Spatie\Permission\Models\Role)
<?php
$roles = $role->pluck('name', 'id')->toArray();
?>
@section('title', 'Update Admin')

@section('content')
	@include('flash::message')
<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    <a href="{{ route('admin.admin.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.Back')</a>

			<h5 style="margin-bottom: 2em;">@lang('admin.Update Account Manager')</h5>

            <form class="form-horizontal" action="{{route('admin.admin.update', $model->id )}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="_method" value="PATCH">

				<div class="form-group row">
					<label for="name" class="col-xs-2 col-form-label">@lang('admin.Full Name')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $model->name }}" name="name" required id="name" placeholder="@lang('admin.Full Name')">
					</div>
				</div>

				<div class="form-group row">
					<label for="email" class="col-xs-2 col-form-label">@lang('admin.Email')</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ $model->email }}" name="email" required id="email" placeholder="Full Name">
					</div>
				</div>

				<div class="form-group row">
					<label for="password" class="col-xs-2 col-form-label">@lang('admin.Password')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password" id="password" placeholder="@lang('admin.Password')">
					</div>
				</div>

				<div class="form-group row">
					<label for="password_confirmation" class="col-xs-2 col-form-label">@lang('admin.Password Confirmation')</label>
					<div class="col-xs-10">
						<input class="form-control" type="password" name="password_confirmation" id="password_confirmation" placeholder="@lang('admin.Password Confirmation')">
					</div>
				</div>

				<div class="form-group row">
					<label for="picture" class="col-xs-2 col-form-label">@lang('admin.picture')</label>
					<div class="col-xs-10">
						<input class="form-control" type="file" value="{{ asset($model->picture) }}" name="picture" id="picture" placeholder="@lang('admin.picture')">
						<img src="{{asset($model->picture) }}" style="width: 100px;margin-top: 20px" alt="">
					</div>
				</div>
				@if(count($roles) != 0)
{{--					<div class="form-group row">--}}
{{--						<label for="roles_list" class="col-xs-2 col-form-label">{{trans('admin.roles_list')}}</label>--}}
{{--						<div class=" col-xs-10">--}}
{{--							{!! Form::select('roles_list[]',$roles,null, [--}}
{{--                                'class'=>'select2 js-states form-control',--}}
{{--                                'multiple' => 'multiple',--}}
{{--                                'id' => 'name',--}}
{{--                            ]) !!}--}}
{{--						</div>--}}
{{--					</div>--}}
{{--					<div class="form-group row">--}}
{{--						<label for="roles_list" class="col-xs-2 col-form-label">{{trans('admin.roles_list')}}</label>--}}
{{--						<div class="col-xs-10">--}}
{{--							<select name="roles_list[]" class='select2 js-states form-control' multiple required id="roles_list">--}}
{{--								@foreach($roles as $role)--}}
{{--									<option  {{ in_array($role,$roles) ? 'selected' : '' }}  value="{{ $role }}">{{ $role }}</option>--}}
{{--								@endforeach--}}
{{--							</select>--}}
{{--						</div>--}}
{{--					</div>--}}
					<div class="form-group">
						<label for="name">الصلاحيات</label><br>
						<input id="select-all" type="checkbox"><label for='select-all'>اختيار الكل</label>
						<br>
						<div class="row">
							@foreach($role->all() as $roles)
								<div class="col-sm-3">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="roles_list[]" value="{{$roles->id}}"
												   @if($model->hasRole($roles->id))
												   checked="checked"
													@endif
											>
											{{$roles->name}}
										</label>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				@else
					<div class="form-group">
						<label for="roles_list">{{trans('admin.roles_list')}}</label>
						<div class="alert alert-danger"> {{trans('admin.rolesListZero')}}
							<a href="{{route('role.create')}}" class="btn btn-primary"
							   style="margin-left: 200px;text-decoration: none;">{{trans('admin.addRole')}}</a>
						</div>
					</div>
				@endif
				<div class="form-group row">
					<label for="zipcode" class="col-xs-2 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.Update Account Manager')</button>
						<a href="{{route('admin.admin.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
