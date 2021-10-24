@extends('admin.layout.base')

@section('title', 'Car Classes ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">Car Classes</h5>
            <a href="{{ route('admin.car.create') }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New Car Class</a>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Car logo</th>
                        <th style="width:100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($cars as $index => $car)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td><a href="{{route('admin.carmodel.index', $car->name )}}">{{ $car->name }}</a></td>
                        <td>
                            @if($car->logo)
                                <img src="{{$car->logo}}" style="height: 50px" >
                            @else
                                N/A
                            @endif
                        </td>
                        <td>
                            <div class="input-group-btn">
                                @if($car->status == 1)
                                <a class="btn btn-danger" href="{{ route('admin.car.changestatus', $car->id ) }}">Disable</a>
                                @else
                                <a class="btn btn-success" href="{{ route('admin.car.changestatus', $car->id ) }}">Enable</a>
                                @endif
                                <button type="button"
                                    class="btn btn-info dropdown-toggle"
                                    data-toggle="dropdown">Action
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.car.edit', $car->id) }}" class="btn btn-default"><i class="fa fa-pencil"></i> Edit</a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.car.destroy', $car->id) }}" method="POST">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="btn btn-default look-a-like" onclick="return confirm('Are you sure?')"><i class="fa fa-trash"></i> Delete</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Car logo</th>
                        <th>Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection