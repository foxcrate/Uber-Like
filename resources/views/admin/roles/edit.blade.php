@extends('admin.layout.base')
@inject('perm', Spatie\Permission\Models\Permission)

<?php
	$permissions = $perm->pluck('name', 'id')->toArray();
?>
@section('title', 'Update Role')

@section('content')
	@push('scripts')
		<script>
			$("#select-all").click(function(){
				$("input[type=checkbox]").prop('checked', $(this).prop('checked'));
			});
		</script>
	@endpush
<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
    	    <a href="{{ route('admin.role.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> @lang('admin.Back')</a>

			<h5 style="margin-bottom: 2em;">@lang('admin.Update Role')</h5>

            <form class="form-horizontal" action="{{route('admin.role.update', $model->id )}}" method="POST" enctype="multipart/form-data" role="form">
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

				@if(count($permissions) != 0)
{{--					<div class="form-group row">--}}
{{--						<label for="permissions_list" class="col-xs-12 col-form-label">{{trans('admin.permissions_list')}}</label>--}}
{{--						<div class="col-xs-10">--}}
{{--							<select name="permissions_list[]" class='select2 js-states form-control' multiple required id="permissions_list">--}}
{{--								@foreach($permissions as $permission)--}}
{{--									<option  {{ in_array($permission,$permissions) ? 'selected' : '' }}  value="{{ $permission }}">{{ $permission }}</option>--}}
{{--								@endforeach--}}
{{--							</select>--}}
{{--						</div>--}}
{{--					</div>--}}
					<div class="form-group">
						<label for="name">الصلاحيات</label><br>
						<input id="select-all" type="checkbox"><label for='select-all'>اختيار الكل</label>
						<br>
						<div class="row">
							@foreach($perm->all() as $permission)
								<div class="col-sm-3">
									<div class="checkbox">
										<label>
											<input type="checkbox" name="permissions_list[]" value="{{$permission->id}}"
												   @if($model->hasPermissionTo($permission->id))
												   checked="checked"
													@endif
											>
											{{$permission->name}}
										</label>
									</div>
								</div>
							@endforeach
						</div>
					</div>
				@else
					<div class="form-group">
						<label for="permissions_list">{{trans('admin.permissions_list')}}</label>
						<div class="alert alert-danger"> {{trans('admin.permissions_list_zero')}}</div>
					</div>
				@endif


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

