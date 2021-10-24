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
                        <th>@lang('admin.code request')</th>
                        <th>@lang('admin.code bill')</th>
                        <th>@lang('admin.payment_mode')</th>
                        <th>@lang('admin.Base Price')</th>
                        <th>@lang('admin.price companyes')</th>
                        <th>@lang('admin.Date')</th>

                    </tr>
                    </thead>
                    <tbody>
                        @isset($revenues)
                            @foreach($revenues as $index => $revenue)
                                @if($revenue->surge != 0)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $revenue->request_id }}</td>
                                        <td>{{ $revenue->id }}</td>
                                        <td>{{ $revenue->payment_mode }}</td>
                                        <td>{{ $revenue->surge }}</td>
                                        @if($revenue->commision==0)
                                            <td><h2 class="tag tag-success">اشتراك شهري</h2></td>
                                        @else
                                        <td>{{ $revenue->commision }}</td>
                                        @endif
                                        <td>{{ date('d - F - Y', strtotime($revenue->created_at)) }}</td>
                                    </tr>
                                @endif
                            @endforeach
                        @endisset
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.code request')</th>
                        <th>@lang('admin.code bill')</th>
                        <th>@lang('admin.payment_mode')</th>
                        <th>@lang('admin.Base Price')</th>
                        <th>@lang('admin.price companyes')</th>
                        <th>@lang('admin.Date')</th>
                    </tr>
                    </tfoot>
                </table>
                <div class="alert alert-success " style="font-weight: bold;
                    text-align: center;
                    font-size: x-large;">
                  @lang('admin.Total_all')
                </div>
                <table class="table table-striped table-bordered dataTable" id="table-2" >
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>@lang('admin.Total trips')</th>
                        <th>@lang('admin.total_cost')</th>
                        <th>@lang('admin.total_r')</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            @isset($revenuesR)
                            <td class="alert alert-danger text-cente" style="font-weight: bold;
                    text-align: center;
                    font-size: large;" >@lang('admin.only_rit')</td>
                            <td>{{$revenuesR->count()}}</td>
                            <td>{{$revenuesR->sum('total')}}</td>
                            <td>{{$revenuesR->sum('commision')}}</td>
                            @endisset
                        </tr>
                        <tr>
                            @isset($revenuesSub)
                            <td class="alert alert-danger text-center" style="font-weight: bold;
                    text-align: center;
                    font-size: large;" >@lang('admin.only_sub')</td>
                            <td>{{$revenuesSub->count()}}</td>
                            <td>{{$revenuesSub->sum('total')}}</td>
                                @isset($revenuesMonthly)
                                <td>{{$revenuesMonthly}}</td>
                                @endisset
                            @endisset
                        </tr>

                    </tbody>
                    <tfoot>
                    <tr>
                    <th>#</th>
                        <th>@lang('admin.Total trips')</th>
                        <th>@lang('admin.total_cost')</th>
                        <th>@lang('admin.total_r')</th>

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
