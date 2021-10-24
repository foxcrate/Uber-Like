@extends('admin.layout.base')

@section('title', 'Payment History ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.payment_history')</h5>
                <table class="table table-striped table-bordered dataTable" id="table-2">
                    <thead>
                        <tr>
                            <th>@lang('admin.request_id')</th>
                            <th>@lang('admin.transaction_id')</th>
                            <th>@lang('admin.form')</th>
                            <th>@lang('admin.to')</th>
                            <th>@lang('admin.total_amount') </th>
                            <th>@lang('admin.payment_mode') </th>
                            <th>@lang('admin.payment_status')</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $index => $payment)
                        <tr>
                            <td>{{$payment->id}}</td>
                            <td>{{$payment->payment->payment_id}}</td>
                            <td>{{base64_decode($payment->user->first_name)}} {{base64_decode($payment->user->last_name)}}</td>
                            <td>{{base64_decode($payment->provider->first_name)}} {{base64_decode($payment->provider->last_name)}}</td>
                            <td>{{currency($payment->payment->total)}}</td>
                            <td>{{$payment->payment_mode}}</td>
                            <td>
                                @if($payment->paid)
                                    @lang('admin.Paid')
                                @else
                                    @lang('admin.Not Paid')
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <th>@lang('admin.request_id')</th>
                        <th>@lang('admin.transaction_id')</th>
                        <th>@lang('admin.form')</th>
                        <th>@lang('admin.to')</th>
                        <th>@lang('admin.total_amount') </th>
                        <th>@lang('admin.payment_mode') </th>
                        <th>@lang('admin.payment_status')</th>
                    </tr>
                    </tfoot>
                </table>
            </div>

        </div>
    </div>
@endsection
