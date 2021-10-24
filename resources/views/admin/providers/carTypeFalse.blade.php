@extends('admin.layout.base')

@section('title', 'Add Provider ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.provider.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i>@lang('admin.Back') </a>

			<h5 style="margin-bottom: 2em;">@lang('admin.Add_Provider')</h5>

            <form class="form-horizontal" action="{{route('admin.postCarTypeFalse',$provider->id)}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group col-md-12">
					<select class="form-control" name="service_type_id" id="service_type_id">
						<option value="0">@lang('user.chose_car')</option>
						@foreach( $car_models as $type)
							<option value="{{$type->id}}">{{$type->name}} - {{$type->date}}</option>
						@endforeach
					</select>
					@if ($errors->has('service_type_id'))
						<span class="help-block">
                                    <strong>{{ $errors->first('service_type_id')}}</strong>
                                </span>
					@endif
				</div>

                <div class="form-group col-md-12">
                    <label> @lang('user.number_car') </label>
                    {{-- <div class="col-xs-6"> --}}
                    <div>
                        <div class="col-sm-6" style="padding-left: 0px">
                            <input id="car_number1" type="number" class="form-control" name="car_number1"
                            value="{{ old('car_number1') }}" placeholder="@lang('user.numbersInCarNumber')">

                            @if ($errors->has('car_number1'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('car_number1') }}</strong>
                                        </span>
                            @endif
                        </div>

                        {{-- <div class="col-xs-6"> --}}
                        <div class="col-sm-6" style="padding-left: 0px; padding-right: 0px;">
                            <input id="car_number2" type="text" class="form-control" name="car_number2"
                                value="{{ old('car_number2') }}" placeholder="@lang('user.charsInCarNumber')">

                            @if ($errors->has('car_number2'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('car_number2') }}</strong>
                                        </span>
                            @endif
                        </div>
                    </div>
                </div>

				<div class="form-group col-md-12">
					<label> @lang('user.color') </label>
					<input id="color" type="text" class="form-control" name="color"
						   value="{{ old('color') }}" placeholder="@lang('user.color')">

					@if ($errors->has('color'))
						<span class="help-block">
                                    <strong>{{ $errors->first('color') }}</strong>
                                </span>
					@endif
				</div>

				<div class="form-group col-md-12">
					<label> @lang('user.car_front') </label>
					<input id="car-front-model" type="file" class="form-control" name="car_front">
					@if ($errors->has('car_front'))
						<span class="help-block">
                                        <strong>{{ $errors->first('car_front') }}</strong>
                                    </span>
					@endif
				</div>

				<div class="form-group col-md-12">
					<label> @lang('user.car_back') </label>
					<input id="car-front-model" type="file" class="form-control" name="car_back">
					@if ($errors->has('car_back'))
						<span class="help-block">
                                        <strong>{{ $errors->first('car_back') }}</strong>
                                    </span>
					@endif
				</div>

				<div class="form-group col-md-12">
					<label> @lang('user.car_left') </label>
					<input id="car-front-model" type="file" class="form-control" name="car_left">
					@if ($errors->has('car_left'))
						<span class="help-block">
                                        <strong>{{ $errors->first('car_left') }}</strong>
                                    </span>
					@endif
				</div>


				<div class="form-group col-md-12">
					<label> @lang('user.car_right') </label>
					<input id="car-front-model" type="file" class="form-control" name="car_right">
					@if ($errors->has('car_right'))
						<span class="help-block">
                                        <strong>{{ $errors->first('car_right') }}</strong>
                                    </span>
					@endif
				</div>

				<div class="form-group col-md-12">
					<label> @lang('user.car_licence_front') </label>
					<input id="car-front-model" type="file" class="form-control" name="car_licence_front">
					@if ($errors->has('car_licence_front'))
						<span class="help-block">
                                        <strong>{{ $errors->first('car_licence_front') }}</strong>
                                    </span>
					@endif
				</div>

				<div class="form-group col-md-12">
					<label> @lang('user.car_licence_back') </label>
					<input id="car-front-model" type="file" class="form-control" name="car_licence_back">
					@if ($errors->has('car_licence_back'))
						<span class="help-block">
                                        <strong>{{ $errors->first('car_licence_back') }}</strong>
                                    </span>
					@endif
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">@lang('admin.Add_Provider')</button>
						<a href="{{route('admin.provider.index')}}" class="btn btn-default">@lang('admin.Cancel')</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
