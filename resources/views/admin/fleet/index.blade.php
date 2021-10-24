@extends('admin.layout.base')

@section('title', 'Fleets ')

@section('content')
    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">
                    @lang('admin.Fleet Owners')
                    @if(Setting::get('demo_mode', 0) == 1)
                        <span class="pull-right">(*personal information hidden in demo)</span>
                    @endif
                </h5>
                @can('اضافه مالك الشركه')
                    <a href="{{ route('admin.fleet.create') }}" style="margin-left: 1em;"
                       class="btn btn-primary pull-right"><i class="fa fa-plus"></i> @lang('admin.Add New Fleet Owner')
                    </a>
                @endcan
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Full Name')</th>
                        <th>@lang('admin.Company Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>
{{--                        <th>@lang('admin.Number_tax_card')</th>--}}
                        <th>@lang('admin.Wallet_Amount')</th>
                        <th>@lang('admin.Company_logo')</th>
{{--                        <th>@lang('admin.Company_commercial_register')</th>--}}
{{--                        <th>@lang('admin.Company_tax_card')</th>--}}
                        @if(auth()->user()->can('تعديل مالك الشركه') || auth()->user()->can('حذف مالك الشركه'))
                            <th>@lang('admin.Action')</th>
                        @endif
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($fleets as $index => $fleet)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $fleet->name }}</td>
                            <td>{{ $fleet->company }}</td>
                            @if(Setting::get('demo_mode', 0) == 1)
                                <td>{{ substr($fleet->email, 0, 3).'****'.substr($fleet->email, strpos($fleet->email, "@")) }}</td>
                            @else
                                <td>{{ $fleet->email }}</td>
                            @endif
                            @if(Setting::get('demo_mode', 0) == 1)
                                <td>+919876543210</td>
                            @else
                                <td>{{ $fleet->mobile }}</td>
                            @endif

{{--                            @if(Setting::get('demo_mode', 0) == 1)--}}
{{--                                <td>+919876543210</td>--}}
{{--                            @else--}}
{{--                                <td>{{ $fleet->number_tax_card }}</td>--}}
{{--                            @endif--}}
                            <td>{{ number_format($fleet->wallet_balance, 2, '.', ',')}}</td>
                            <td>
                                <img src="{{asset($fleet->logo)}}" style="height: 75px;width: 75px">
                            </td>
{{--                            <td>--}}
{{--                                <img src="{{asset($fleet->commercial_register)}}" style="height: 75px;width: 75px">--}}
{{--                            </td>--}}
{{--                            <td>--}}
{{--                                <img src="{{asset($fleet->tax_card)}}" style="height: 75px;width: 75px">--}}
{{--                            </td>--}}
                            @if(auth()->user()->can('تعديل مالك الشركه') || auth()->user()->can('حذف مالك الشركه'))
                                <td>
{{--                                    @can('الاضافه فى محفظة السائق')--}}
                                        <button type="button" class="btn btn-info btn-md Open-Wallet"
                                                data-toggle="modal" data-id="{{$fleet->id}}"
                                                data-target="#Wallet" style="margin-bottom: 10px">
                                            @lang('admin.Wallet')
                                        </button>
{{--                                    @endcan--}}
                                    <form action="{{ route('admin.fleet.destroy', $fleet->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="_method" value="DELETE">
                                        {{--                                <a href="{{ route('admin.provider.index') }}?fleet={{$fleet->id}}" class="btn btn-info"> @lang('admin.Show Provider')</a>--}}
                                        @can('تعديل مالك الشركه')
                                            <a href="{{ route('admin.fleet.edit', $fleet->id) }}"
                                               class="btn btn-info"><i class="fa fa-pencil"></i> @lang('admin.Edit')</a>
                                        @endcan
                                        @can('حذف مالك الشركه')
                                            <button class="btn btn-danger" onclick="return confirm('Are you sure?')"><i
                                                        class="fa fa-trash"></i> @lang('admin.Delete')</button>
                                        @endcan
                                    </form>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.ID')</th>
                        <th>@lang('admin.Full Name')</th>
                        <th>@lang('admin.Company Name')</th>
                        <th>@lang('admin.Email')</th>
                        <th>@lang('admin.Mobile')</th>
{{--                        <th>@lang('admin.Number_tax_card')</th>--}}
                        <th>@lang('admin.Wallet_Amount')</th>
                        <th>@lang('admin.Company_logo')</th>
{{--                        <th>@lang('admin.Company_commercial_register')</th>--}}
{{--                        <th>@lang('admin.Company_tax_card')</th>--}}
                        @if(auth()->user()->can('تعديل مالك الشركه') || auth()->user()->can('حذف مالك الشركه'))
                            <th>@lang('admin.Action')</th>
                        @endif
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
@section('scripts')
    <script>
        $('body').on('click', '.Open-Wallet', function () {
            var first_name = $(this).closest('tr').find('td').eq(1).text();
            $('#Wallet .modal-header h4').text(' ' + first_name + '@lang('admin.Add Or Remove Balanace For')');

            $('#Wallet').find('input[name="user_id"]').val($(this).data('id'));
            $('#Wallet').find('input[name="wallet_balance"]').val($(this).data('wallet'));
            // alert($(this).data('id'));
        })
    </script>
@endsection