@extends('admin.layout.base')

@section('title', $name.' Models ')

@section('content')
<div class="content-area py-1">
    <div class="container-fluid">
        <div class="box box-block bg-white">
            <h5 class="mb-1">{{$name}} Models</h5>
            <a href="{{ route('admin.carmodel.create','name='.$name ) }}" style="margin-left: 1em;" class="btn btn-primary pull-right"><i class="fa fa-plus"></i> Add New model</a>
            <table class="table table-striped table-bordered dataTable" id="table-2">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>model Name</th>
                        <th>model production date</th>
                        <th>Number of seats</th>
                        <th style="width:100px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($carmodels as $index => $model)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $model->model_name }}</td>
                        <td>{{ $model->model_date }}</td>
                        <td>{{ $model->sets_num }}</td>
                        <td>
                            <div class="input-group-btn">
                                @if($model->status == 1)
                                <a class="btn btn-danger" href="{{ route('admin.carmodel.changestatus', $model->id ) }}">Disable</a>
                                @else
                                <a class="btn btn-success" href="{{ route('admin.carmodel.changestatus', $model->id ) }}">Enable</a>
                                @endif
                                <button type="button" 
                                    class="btn btn-info dropdown-toggle"
                                    data-toggle="dropdown">Action
                                    <span class="caret"></span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('admin.carmodel.edit', $model->id) }}" class="btn btn-default"><i class="fa fa-pencil"></i> Edit</a>
                                    </li>
                                    <li>
                                        <form action="{{ route('admin.carmodel.destroy', $model->id) }}" method="POST">
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
                        <th>model Name</th>
                        <th>model production date</th>
                        <th>Number of seats</th>
                        <th style="width:100px;">Action</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
@endsection