@extends('admin.layout.base')

@section('title', 'Edit Car Class ')

@section('content')

<div class="content-area py-1">
    <div class="container-fluid">
    	<div class="box box-block bg-white">
            <a href="{{ route('admin.car.index') }}" class="btn btn-default pull-right"><i class="fa fa-angle-left"></i> Back</a>

			<h5 style="margin-bottom: 2em;">Add Car Class</h5>

            <form class="form-horizontal" action="{{route('admin.car.update')}}" method="POST" enctype="multipart/form-data" role="form">
            	{{csrf_field()}}
            	<input type="hidden" name="id" value="{{$car->id}}" />
				<div class="form-group row">
					<label for="first_name" class="col-xs-12 col-form-label">Class Name</label>
					<div class="col-xs-10">
						<input class="form-control" type="text" value="{{$car->name}}" name="name" required id="name" placeholder="Car Class Name">
					</div>
				</div>

				<div class="form-group row">
					<label for="logo" class="col-xs-12 col-form-label">logo</label>
					<div class="col-xs-10">
						<input type="file" accept="image/*" name="logo" value="{{$car->logo}}" class="dropify form-control-file" id="logo" aria-describedby="fileHelp">
					</div>
				</div>

				<div class="form-group row">
					<label for="zipcode" class="col-xs-12 col-form-label"></label>
					<div class="col-xs-10">
						<button type="submit" class="btn btn-primary">Update Car</button>
						<a href="{{route('admin.car.index')}}" class="btn btn-default">Cancel</a>
					</div>
				</div>
			</form>
		</div>
    </div>
</div>

@endsection
