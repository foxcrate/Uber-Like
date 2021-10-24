@extends('admin.layout.base')

@section('title', 'Request History ')

@section('content')

    <div class="content-area py-1">
        <div class="container-fluid">
            <div class="box box-block bg-white">
                <h5 class="mb-1">@lang('admin.Request History')</h5>
                @if(count($requests) != 0)
                    <table class="table table-striped table-bordered dataTable" id="table-2">
                        <thead>
                        <tr>
                            <th>@lang('admin.ID')</th>
                            <th>@lang('admin.Booking ID')</th>
                            <th>@lang('admin.User Name')</th>
                            <th>@lang('admin.Provider Name')</th>
                            <th>@lang('admin.Date') &amp; @lang('admin.Time')</th>
                            <th>@lang('admin.Status')</th>
                            <th>@lang('admin.Total')</th>
                            <th>@lang('admin.Payment Mode')</th>
                            <th>@lang('admin.Payment Status')</th>
                            <th>@lang('admin.commission')</th>
                            <th>@lang('admin.Action')</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($requests as $index => $request)
                            <tr>
                                <td>{{ $request->id }}</td>
                                <td>{{ $request->booking_id }}</td>
                                <td>
                                    @if($request->user)
                                        {{ $request->user->first_name }} {{ $request->user->last_name }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($request->provider)
                                        {{ $request->provider->first_name }} {{ $request->provider->last_name }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    @if($request->created_at)
                                        <span class="text-muted">{{$request->created_at}}</span> {{--  ->diffForHumans()--}}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $request->status }}</td>
                                <td>
                                    @if($request->payment != "")
                                        {{ currency($request->payment->total) }}
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>{{ $request->payment_mode }}</td>
                                <td>
                                    @if($request->paid)
                                        @lang('admin.Paid')
                                    @else
                                        @lang('admin.Not Paid')
                                    @endif
                                </td>
                                <td>
                                    @if($request->payment)
                                        @if($request->payment->commision)
                                            {{ $request->payment->commision }}
                                        @elseif ($request->payment->commision == '0')
                                            @lang('admin.Mutual')
                                        @endif
                                    @else
                                        N/A
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-primary waves-effect dropdown-toggle"
                                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            @lang('admin.Action')
                                        </button>
                                        <div class="dropdown-menu">
                                            <a href="{{ route('admin.requests.show', $request->id) }}"
                                               class="dropdown-item">
                                                <i class="fa fa-search"></i> @lang('admin.More Details')
                                            </a>
                                            @can('حذف الرحلات')
                                                <form action="{{ route('admin.requests.destroy', $request->id) }}"
                                                      method="POST">
                                                    {{ csrf_field() }}
                                                    {{ method_field('DELETE') }}
                                                    <button type="submit" class="dropdown-item">
                                                        <i class="fa fa-trash"></i> @lang('admin.Delete')
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                        <tfoot>
                        <tr>
                            <th>@lang('admin.ID')</th>
                            <th>@lang('admin.Booking ID')</th>
                            <th>@lang('admin.User Name')</th>
                            <th>@lang('admin.Provider Name')</th>
                            <th>@lang('admin.Date') &amp; @lang('admin.Time')</th>
                            <th>@lang('admin.Status')</th>
                            <th>@lang('admin.Total')</th>
                            <th>@lang('admin.Payment Mode')</th>
                            <th>@lang('admin.Payment Status')</th>
                            <th>@lang('admin.commission')</th>
                            <th>@lang('admin.Action')</th>
                        </tr>
                        </tfoot>
                    </table>
                @else
                    <h6 class="no-result">@lang('admin.No results found')</h6>
                @endif
            </div>
        </div>
    </div>
@endsection