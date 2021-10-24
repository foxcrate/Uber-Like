@extends('admin.layout.base')

@section('title', 'Add Car Model ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.car.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

			<h5 style="margin-bottom: 2em;">Add Car Model</h5>

            <form class="form-horizontal" action="{{isset($car_model)?route('admin.carmodel.update'):route('admin.carmodel.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				@if(isset($car_model))
				<input type="hidden" value="{{$car_model->id}}" name="model_id">
				@endif
				
            	<div class="form-group row">
					<label for="car_id" class="col-xs-12 col-form-label">Car Class</label>
					<div class="col-xs-10">
					    <select class="form-control" name="car_id" id="car_id">
					        @foreach($cars as $car)
					            @if($carname && $car->name == $carname)
					                <option selected value="{{$car->id}}">{{$car->name}}</option>
					            @else
					                <option value="{{$car->id}}">{{$car->name}}</option>
					            @endif
					        @endforeach
					    </select>
					</div>
				</div>
            	
				<div class="form-group row">
					<label for="model_name" class="col-xs-12 col-form-label">Model Name</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{isset($car_model)?$car_model->model_name:old('model_name')}}" name="model_name" required id="model_name" placeholder="Car Model Name">
					</div>
				</div>

                <div class="form-group row">		
					<label for="sets_num" class="col-xs-12 col-form-label">date</label>
					<div class="col-xs-10">
						<select class="form-control" multiple name="model_date[]" required id="sets_num">
							<option value="2004">2004</option>
							<option value="2005">2005</option>
							<option value="2006">2006</option>
							<option value="2007">2007</option>
							<option value="2008">2008</option>
							<option value="2009">2009</option>
							<option value="2010">2010</option>
							<option value="2011">2011</option>
							<option value="2012">2012</option>
							<option value="2013">2013</option>
							<option value="2014">2014</option>
							<option value="2015">2015</option>
							<option value="2016">2016</option>
							<option value="2017">2017</option>
							<option value="2018">2018</option>
							<option value="2019">2019</option>
						</select>
					</div>
				</div>
				
				<div class="form-group row">
					<label for="sets_num" class="col-xs-12 col-form-label">Number of Seats</label>
					<div class="col-xs-10">
						<input class="form-control" type="number" value="{{isset($car_model)?$car_model->sets_num:old('sets_num')}}" name="sets_num" required id="sets_num" placeholder="Number of Seats">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">{{isset($car_model)?'Update Model':'Add Model'}}</button>
						<a href="{{route('admin.car.index')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection

@section('scripts')
<script>
	$('.date-picker').datepicker( {	
		format: "yyyy",
		viewMode: "years", 
		minViewMode: "years"
	});
	@if(isset($car_model))
		$('select[name="car_id"]').val({{$car_model->car_id}}).change();
		@if(count(json_decode($car_model->model_date)) > 1)
			@foreach(json_decode($car_model->model_date) as $value)
				$('select[name="model_date[]"] option[value="{{$value}}"]').prop("selected", true);
			@endforeach
		@else
		$('select[name="model_date[]"]').val({{$car_model->model_date}});
		@endif
	@endif
</script>
@endsection