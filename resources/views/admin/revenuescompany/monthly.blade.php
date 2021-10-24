@extends('admin.layout.base')

@section('title', 'Ail-Baz Company Revenue')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
               
               
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.sub_mon')</th>
                        <th>@lang('admin.rit_mon')</th>
                        <th>@lang('admin.Date2')</th>
                       
                    </tr>
                    </thead>
                    <tbody>
                        @isset($revenuesMonthly)
                            @foreach($revenuesMonthly as $index => $revenue)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $revenue->revenues_sub }}</td>
                                        <td>{{ $revenue->revenues_ri }}</td>
                                        <td>{{ $revenue->history }}</td>
                                    </tr>
                            @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.sub_mon')</th>
                        <th>@lang('admin.rit_mon')</th>
                        <th>@lang('admin.Date2')</th>                      
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div id="Wallet" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" action="{{route('admin.fleet.wallet')}}" method="POST"
                      enctype="multipart/form-data" role="form">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">@lang('admin.Wallet')</h4>
                    </div>
                    <div class="modal-body">
                        {{csrf_field()}}
                        <input type="hidden" name="user_id">
                        <div class="form-group row">
                            <label for="cash" class="col-xs-12 col-form-label">@lang('admin.Blanace')</label>
                            <div class="col-xs-10">
                                <input class="form-control" type="number" name="cash" step=".01" id="cash"
                                       placeholder="@lang('admin.Blanace')">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">@lang('admin.Close')</button>
                        <button type="submit" class="btn btn-success">@lang('admin.Update_Wallet')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
