@extends('admin.layout.base')

@section('title', 'Add Car ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.car.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

			<h5 style="margin-bottom: 2em;">Add Car Class</h5>

            <form class="form-horizontal" action="{{route('admin.car.store')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
				<div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label">Class Name</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{ old('first_name') }}" name="name" required id="name" placeholder="Car Class Name">
					</div>
				</div>

				<div class="form-group row">
					<label for="logo" class="col-xs-12 col-form-label">logo</label>
					<div class="col-xs-10">
						<input type="file" accept="image/*" name="logo" class="dropify form-control-file" id="logo" aria-describedby="fileHelp">
					</div>
				</div>
<!-- 
				Models
				<button style="margin-right:10px;" type="button" class="btn btn-info Add_New_Model pull-right">+</button>
				<div id="Car_Models" style="border:1px solid #e4e4e4;padding:10px;">
					<div class="Car_Model">
						<div class="row">
							<div class="col-md-11">
								<div class="form-group row">
									<div class="col-md-5">
										<div class="form-group">
											<label for="model_name" class="col-form-label">Model Name</label>
											<input type="text" name="model_name" class="form-control" id="model_name">
										</div>
									</div>
									<div class="col-md-2">
										<div class="form-group">
											<label for="sets_num" class="col-form-label">Number Of Seats</label>
											<input type="number" name="sets_num" class="form-control" id="sets_num">
										</div>
									</div>
									<div class="col-md-5">
										<div class="form-group">
											<label for="model_date" class="col-form-label">Production Date</label>
											<select class="form-control" multiple="" name="model_date[]" required="" id="model_date">
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
								</div>
							</div>
							<div class="col-md-1">
								<label for="logo" class="col-form-label">Delete</label>
								<button style="margin-right:10px;" type="button" class="btn btn-danger Delete_Model">×</button>
							</div>
						</div>
					</div>
				</div>
				 -->

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Add Car</button>
						<a href="{{route('admin.car.index')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
@section('scripts')
	$('body').on('click','.Delete_Model',function(){
		$(this).closest('.Car_Model').remove();
	});
@endsection